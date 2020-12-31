<?php

/////////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <info@getid3.org>               //
//  available at https://github.com/JamesHeinrich/getID3       //
//            or https://www.getid3.org                        //
//            or http://getid3.sourceforge.net                 //
//  see readme.txt for more details                            //
/////////////////////////////////////////////////////////////////
//                                                             //
// module.audio.ac3.php                                        //
// module for analyzing AC-3 (aka Dolby Digital) audio files   //
// dependencies: NONE                                          //
//                                                            ///
/////////////////////////////////////////////////////////////////


class getid3_ac3 extends getid3_handler
{
	/**
	 * @var array
	 */
	private $AC3header = array();

	/**
	 * @var int
	 */
	private $BSIoffset = 0;

	const syncword = 0x0B77;

	/**
	 * @return bool
	 */
	public function Analyze() {
		$info = &$this->getid3->info;

		///AH
		$info['ac3']['raw']['bsi'] = array();
		$thisfile_ac3              = &$info['ac3'];
		$thisfile_ac3_raw          = &$thisfile_ac3['raw'];
		$thisfile_ac3_raw_bsi      = &$thisfile_ac3_raw['bsi'];


		// http://www.atsc.org/standards/a_52a.pdf

		$info['fileformat'] = 'ac3';

		// An AC-3 serial coded audio bit stream is made up of a sequence of synchronization frames
		// Each synchronization frame contains 6 coded audio blocks (AB), each of which represent 256
		// new audio samples per channel. A synchronization information (SI) header at the beginning
		// of each frame contains information needed to acquire and maintain synchronization. A
		// bit stream information (BSI) header follows SI, and contains parameters describing the coded
		// audio service. The coded audio blocks may be followed by an auxiliary data (Aux) field. At the
		// end of each frame is an error check field that includes a CRC word for error detection. An
		// additional CRC word is located in the SI header, the use of which, by a decoder, is optional.
		//
		// syncinfo() | bsi() | AB0 | AB1 | AB2 | AB3 | AB4 | AB5 | Aux | CRC

		// syncinfo() {
		// 	 syncword    16
		// 	 crc1        16
		// 	 fscod        2
		// 	 frmsizecod   6
		// } /* end of syncinfo */

		$this->fseek($info['avdataoffset']);
		$tempAC3header = $this->fread(100); // should be enough to cover all data, there are some variable-length fields...?
		$this->AC3header['syncinfo']  =     getid3_lib::BigEndian2Int(substr($tempAC3header, 0, 2));
		$this->AC3header['bsi']       =     getid3_lib::BigEndian2Bin(substr($tempAC3header, 2));
		$thisfile_ac3_raw_bsi['bsid'] = (getid3_lib::LittleEndian2Int(substr($tempAC3header, 5, 1)) & 0xF8) >> 3; // AC3 and E-AC3 put the "bsid" version identifier in the same place, but unfortnately the 4 bytes between the syncword and the version identifier are interpreted differently, so grab it here so the following code structure can make sense
		unset($tempAC3header);

		if ($this->AC3header['syncinfo'] !== self::syncword) {
			if (!$this->isDependencyFor('matroska')) {
				unset($info['fileformat'], $info['ac3']);
				return $this->error('Expecting "'.dechex(self::syncword).'" at offset '.$info['avdataoffset'].', found "'.dechex($this->AC3header['syncinfo']).'"');
			}
		}

		$info['audio']['dataformat']   = 'ac3';
		$info['audio']['bitrate_mode'] = 'cbr';
		$info['audio']['lossless']     = false;

		if ($thisfile_ac3_raw_bsi['bsid'] <= 8) {

			$thisfile_ac3_raw_bsi['crc1']       = getid3_lib::Bin2Dec($this->readHeaderBSI(16));
			$thisfile_ac3_raw_bsi['fscod']      =                     $this->readHeaderBSI(2);   // 5.4.1.3
			$thisfile_ac3_raw_bsi['frmsizecod'] =                     $this->readHeaderBSI(6);   // 5.4.1.4
			if ($thisfile_ac3_raw_bsi['frmsizecod'] > 37) { // binary: 100101 - see Table 5.18 Frame Size Code Table (1 word = 16 bits)
				$this->warning('Unexpected ac3.bsi.frmsizecod value: '.$thisfile_ac3_raw_bsi['frmsizecod'].', bitrate not set correctly');
			}

			$thisfile_ac3_raw_bsi['bsid']  = $this->readHeaderBSI(5); // we already know this from pre-parsing the version identifier, but re-read it to let the bitstream flow as intended
			$thisfile_ac3_raw_bsi['bsmod'] = $this->readHeaderBSI(3);
			$thisfile_ac3_raw_bsi['acmod'] = $this->readHeaderBSI(3);

			if ($thisfile_ac3_raw_bsi['acmod'] & 0x01) {
				// If the lsb of acmod is a 1, center channel is in use and cmixlev follows in the bit stream.
				$thisfile_ac3_raw_bsi['cmixlev'] = $this->readHeaderBSI(2);
				$thisfile_ac3['center_mix_level'] = self::centerMixLevelLookup($thisfile_ac3_raw_bsi['cmixlev']);
			}

			if ($thisfile_ac3_raw_bsi['acmod'] & 0x04) {
				// If the msb of acmod is a 1, surround channels are in use and surmixlev follows in the bit stream.
				$thisfile_ac3_raw_bsi['surmixlev'] = $this->readHeaderBSI(2);
				$thisfile_ac3['surround_mix_level'] = self::surroundMixLevelLookup($thisfile_ac3_raw_bsi['surmixlev']);
			}

			if ($thisfile_ac3_raw_bsi['acmod'] == 0x02) {
				// When operating in the two channel mode, this 2-bit code indicates whether or not the program has been encoded in Dolby Surround.
				$thisfile_ac3_raw_bsi['dsurmod'] = $this->readHeaderBSI(2);
				$thisfile_ac3['dolby_surround_mode'] = self::dolbySurroundModeLookup($thisfile_ac3_raw_bsi['dsurmod']);
			}

			$thisfile_ac3_raw_bsi['flags']['lfeon'] = (bool) $this->readHeaderBSI(1);

			// This indicates how far the average dialogue level is below digital 100 percent. Valid values are 1-31.
			// The value of 0 is reserved. The values of 1 to 31 are interpreted as -1 dB to -31 dB with respect to digital 100 percent.
			$thisfile_ac3_raw_bsi['dialnorm'] = $this->readHeaderBSI(5);                 // 5.4.2.8 dialnorm: Dialogue Normalization, 5 Bits

			$thisfile_ac3_raw_bsi['flags']['compr'] = (bool) $this->readHeaderBSI(1);       // 5.4.2.9 compre: Compression Gain Word Exists, 1 Bit
			if ($thisfile_ac3_raw_bsi['flags']['compr']) {
				$thisfile_ac3_raw_bsi['compr'] = $this->readHeaderBSI(8);                // 5.4.2.10 compr: Compression Gain Word, 8 Bits
				$thisfile_ac3['heavy_compression'] = self::heavyCompression($thisfile_ac3_raw_bsi['compr']);
			}

			$thisfile_ac3_raw_bsi['flags']['langcod'] = (bool) $this->readHeaderBSI(1);     // 5.4.2.11 langcode: Language Code Exists, 1 Bit
			if ($thisfile_ac3_raw_bsi['flags']['langcod']) {
				$thisfile_ac3_raw_bsi['langcod'] = $this->readHeaderBSI(8);              // 5.4.2.12 langcod: Language Code, 8 Bits
			}

			$thisfile_ac3_raw_bsi['flags']['audprodinfo'] = (bool) $this->readHeaderBSI(1);  // 5.4.2.13 audprodie: Audio Production Information Exists, 1 Bit
			if ($thisfile_ac3_raw_bsi['flags']['audprodinfo']) {
				$thisfile_ac3_raw_bsi['mixlevel'] = $this->readHeaderBSI(5);             // 5.4.2.14 mixlevel: Mixing Level, 5 Bits
				$thisfile_ac3_raw_bsi['roomtyp']  = $this->readHeaderBSI(2);             // 5.4.2.15 roomtyp: Room Type, 2 Bits

				$thisfile_ac3['mixing_level'] = (80 + $thisfile_ac3_raw_bsi['mixlevel']).'dB';
				$thisfile_ac3['room_type']    = self::roomTypeLookup($thisfile_ac3_raw_bsi['roomtyp']);
			}


			$thisfile_ac3_raw_bsi['dialnorm2'] = $this->readHeaderBSI(5);                // 5.4.2.16 dialnorm2: Dialogue Normalization, ch2, 5 Bits
			$thisfile_ac3['dialogue_normalization2'] = '-'.$thisfile_ac3_raw_bsi['dialnorm2'].'dB';  // This indicates how far the average dialogue level is below digital 100 percent. Valid values are 1-31. The value of 0 is reserved. The values of 1 to 31 are interpreted as -1 dB to -31 dB with respect to digital 100 percent.

			$thisfile_ac3_raw_bsi['flags']['compr2'] = (bool) $this->readHeaderBSI(1);       // 5.4.2.17 compr2e: Compression Gain Word Exists, ch2, 1 Bit
			if ($thisfile_ac3_raw_bsi['flags']['compr2']) {
				$thisfile_ac3_raw_bsi['compr2'] = $this->readHeaderBSI(8);               // 5.4.2.18 compr2: Compression Gain Word, ch2, 8 Bits
				$thisfile_ac3['heavy_compression2'] = self::heavyCompression($thisfile_ac3_raw_bsi['compr2']);
			}

			$thisfile_ac3_raw_bsi['flags']['langcod2'] = (bool) $this->readHeaderBSI(1);    // 5.4.2.19 langcod2e: Language Code Exists, ch2, 1 Bit
			if ($thisfile_ac3_raw_bsi['flags']['langcod2']) {
				$thisfile_ac3_raw_bsi['langcod2'] = $this->readHeaderBSI(8);             // 5.4.2.20 langcod2: Language Code, ch2, 8 Bits
			}

			$thisfile_ac3_raw_bsi['flags']['audprodinfo2'] = (bool) $this->readHeaderBSI(1); // 5.4.2.21 audprodi2e: Audio Production Information Exists, ch2, 1 Bit
			if ($thisfile_ac3_raw_bsi['flags']['audprodinfo2']) {
				$thisfile_ac3_raw_bsi['mixlevel2'] = $this->readHeaderBSI(5);            // 5.4.2.22 mixlevel2: Mixing Level, ch2, 5 Bits
				$thisfile_ac3_raw_bsi['roomtyp2']  = $this->readHeaderBSI(2);            // 5.4.2.23 roomtyp2: Room Type, ch2, 2 Bits

				$thisfile_ac3['mixing_level2'] = (80 + $thisfile_ac3_raw_bsi['mixlevel2']).'dB';
				$thisfile_ac3['room_type2']    = self::roomTypeLookup($thisfile_ac3_raw_bsi['roomtyp2']);
			}

			$thisfile_ac3_raw_bsi['copyright'] = (bool) $this->readHeaderBSI(1);         // 5.4.2.24 copyrightb: Copyright Bit, 1 Bit

			$thisfile_ac3_raw_bsi['original']  = (bool) $this->readHeaderBSI(1);         // 5.4.2.25 origbs: Original Bit Stream, 1 Bit

			$thisfile_ac3_raw_bsi['flags']['timecod1'] = $this->readHeaderBSI(2);            // 5.4.2.26 timecod1e, timcode2e: Time Code (first and second) Halves Exist, 2 Bits
			if ($thisfile_ac3_raw_bsi['flags']['timecod1'] & 0x01) {
				$thisfile_ac3_raw_bsi['timecod