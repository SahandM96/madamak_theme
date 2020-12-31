<?php

/////////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <info@getid3.org>               //
//  available at https://github.com/JamesHeinrich/getID3       //
//            or https://www.getid3.org                        //
//            or http://getid3.sourceforge.net                 //
//  see readme.txt for more details                            //
/////////////////////////////////////////////////////////////////
//                                                             //
// module.audio-video.riff.php                                 //
// module for analyzing RIFF files                             //
// multiple formats supported by this module:                  //
//    Wave, AVI, AIFF/AIFC, (MP3,AC3)/RIFF, Wavpack v3, 8SVX   //
// dependencies: module.audio.mp3.php                          //
//               module.audio.ac3.php                          //
//               module.audio.dts.php                          //
//                                                            ///
/////////////////////////////////////////////////////////////////

/**
* @todo Parse AC-3/DTS audio inside WAVE correctly
* @todo Rewrite RIFF parser totally
*/

getid3_lib::IncludeDependency(GETID3_INCLUDEPATH.'module.audio.mp3.php', __FILE__, true);
getid3_lib::IncludeDependency(GETID3_INCLUDEPATH.'module.audio.ac3.php', __FILE__, true);
getid3_lib::IncludeDependency(GETID3_INCLUDEPATH.'module.audio.dts.php', __FILE__, true);

class getid3_riff extends getid3_handler
{
	protected $container = 'riff'; // default

	/**
	 * @return bool
	 *
	 * @throws getid3_exception
	 */
	public function Analyze() {
		$info = &$this->getid3->info;

		// initialize these values to an empty array, otherwise they default to NULL
		// and you can't append array values to a NULL value
		$info['riff'] = array('raw'=>array());

		// Shortcuts
		$thisfile_riff             = &$info['riff'];
		$thisfile_riff_raw         = &$thisfile_riff['raw'];
		$thisfile_audio            = &$info['audio'];
		$thisfile_video            = &$info['video'];
		$thisfile_audio_dataformat = &$thisfile_audio['dataformat'];
		$thisfile_riff_audio       = &$thisfile_riff['audio'];
		$thisfile_riff_video       = &$thisfile_riff['video'];
		$thisfile_riff_WAVE        = array();

		$Original['avdataoffset'] = $info['avdataoffset'];
		$Original['avdataend']    = $info['avdataend'];

		$this->fseek($info['avdataoffset']);
		$RIFFheader = $this->fread(12);
		$offset = $this->ftell();
		$RIFFtype    = substr($RIFFheader, 0, 4);
		$RIFFsize    = substr($RIFFheader, 4, 4);
		$RIFFsubtype = substr($RIFFheader, 8, 4);

		switch ($RIFFtype) {

			case 'FORM':  // AIFF, AIFC
				//$info['fileformat']   = 'aiff';
				$this->container = 'aiff';
				$thisfile_riff['header_size'] = $this->EitherEndian2Int($RIFFsize);
				$thisfile_riff[$RIFFsubtype]  = $this->ParseRIFF($offset, ($offset + $thisfile_riff['header_size'] - 4));
				break;

			case 'RIFF':  // AVI, WAV, etc
			case 'SDSS':  // SDSS is identical to RIFF, just renamed. Used by SmartSound QuickTracks (www.smartsound.com)
			case 'RMP3':  // RMP3 is identical to RIFF, just renamed. Used by [unknown program] when creating RIFF-MP3s
				//$info['fileformat']   = 'riff';
				$this->container = 'riff';
				$thisfile_riff['header_size'] = $this->EitherEndian2Int($RIFFsize);
				if ($RIFFsubtype == 'RMP3') {
					// RMP3 is identical to WAVE, just renamed. Used by [unknown program] when creating RIFF-MP3s
					$RIFFsubtype = 'WAVE';
				}
				if ($RIFFsubtype != 'AMV ') {
					// AMV files are RIFF-AVI files with parts of the spec deliberately broken, such as chunk size fields hardcoded to zero (because players known in hardware that these fields are always a certain size
					// Handled separately in ParseRIFFAMV()
					$thisfile_riff[$RIFFsubtype]  = $this->ParseRIFF($offset, ($offset + $thisfile_riff['header_size'] - 4));
				}
				if (($info['avdataend'] - $info['filesize']) == 1) {
					// LiteWave appears to incorrectly *not* pad actual output file
					// to nearest WORD boundary so may appear to be short by one
					// byte, in which case - skip warning
					$info['avdataend'] = $info['filesize'];
				}

				$nextRIFFoffset = $Original['avdataoffset'] + 8 + $thisfile_riff['header_size']; // 8 = "RIFF" + 32-bit offset
				while ($nextRIFFoffset < min($info['filesize'], $info['avdataend'])) {
					try {
						$this->fseek($nextRIFFoffset);
					} catch (getid3_exception $e) {
						if ($e->getCode() == 10) {
							//$this->warning('RIFF parser: '.$e->getMessage());
							$this->error('AVI extends beyond '.round(PHP_INT_MAX / 1073741824).'GB and PHP filesystem functions cannot read that far, playtime may be wrong');
							$this->warning('[avdataend] value may be incorrect, multiple AVIX chunks may be present');
							break;
						} else {
							throw $e;
						}
					}
					$nextRIFFheader = $this->fread(12);
					if ($nextRIFFoffset == ($info['avdataend'] - 1)) {
						if (substr($nextRIFFheader, 0, 1) == "\x00") {
							// RIFF padded to WORD boundary, we're actually already at the end
							break;
						}
					}
					$nextRIFFheaderID =                         substr($nextRIFFheader, 0, 4);
					$nextRIFFsize     = $this->EitherEndian2Int(substr($nextRIFFheader, 4, 4));
					$nextRIFFtype     =                         substr($nextRIFFheader, 8, 4);
					$chunkdata = array();
					$chunkdata['offset'] = $nextRIFFoffset + 8;
					$chunkdata['size']   = $nextRIFFsize;
					$nextRIFFoffset = $chunkdata['offset'] + $chunkdata['size'];

					switch ($nextRIFFheaderID) {
						case 'RIFF':
							$chunkdata['chunks'] = $this->ParseRIFF($chunkdata['offset'] + 4, $nextRIFFoffset);
							if (!isset($thisfile_riff[$nextRIFFtype])) {
								$thisfile_riff[$nextRIFFtype] = array();
							}
							$thisfile_riff[$nextRIFFtype][] = $chunkdata;
							break;

						case 'AMV ':
							unset($info['riff']);
							$info['amv'] = $this->ParseRIFFAMV($chunkdata['offset'] + 4, $nextRIFFoffset);
							break;

						case 'JUNK':
							// ignore
							$thisfile_riff[$nextRIFFheaderID][] = $chunkdata;
							break;

						case 'IDVX':
							$info['divxtag']['comments'] = self::ParseDIVXTAG($this->fread($chunkdata['size']));
							break;

						default:
							if ($info['filesize'] == ($chunkdata['offset'] - 8 + 128)) {
								$DIVXTAG = $nextRIFFheader.$this->fread(128 - 12);
								if (substr($DIVXTAG, -7) == 'DIVXTAG') {
									// DIVXTAG is supposed to be inside an IDVX chunk in a LIST chunk, but some bad encoders just slap it on the end of a file
									$this->warning('Found wrongly-structured DIVXTAG at offset '.($this->ftell() - 128).', parsing anyway');
									$info['divxtag']['comments'] = self::ParseDIVXTAG($DIVXTAG);
									break 2;
								}
							}
							$this->warning('Expecting "RIFF|JUNK|IDVX" at '.$nextRIFFoffset.', found "'.$nextRIFFheaderID.'" ('.getid3_lib::PrintHexBytes($nextRIFFheaderID).') - skipping rest of file');
							break 2;

					}

				}
				if ($RIFFsubtype == 'WAVE') {
					$thisfile_riff_WAVE = &$thisfile_riff['WAVE'];
				}
				break;

			default:
				$this->error('Cannot parse RIFF (this is maybe not a RIFF / WAV / AVI file?) - expecting "FORM|RIFF|SDSS|RMP3" found "'.$RIFFsubtype.'" instead');
				//unset($info['fileformat']);
				return false;
		}

		$streamindex = 0;
		switch ($RIFFsubtype) {

			// http://en.wikipedia.org/wiki/Wav
			case 'WAVE':
				$info['fileformat'] = 'wav';

				if (empty($thisfile_audio['bitrate_mode'])) {
					$thisfile_audio['bitrate_mode'] = 'cbr';
				}
				if (empty($thisfile_audio_dataformat)) {
					$thisfile_audio_dataformat = 'wav';
				}

				if (isset($thisfile_riff_WAVE['data'][0]['offset'])) {
					$info['avdataoffset'] = $thisfile_riff_WAVE['data'][0]['offset'] + 8;
					$info['avdataend']    = $info['avdataoffset'] + $thisfile_riff_WAVE['data'][0]['size'];
				}
				if (isset($thisfile_riff_WAVE['fmt '][0]['data'])) {

					$thisfile_riff_audio[$streamindex] = self::parseWAVEFORMATex($thisfile_riff_WAVE['fmt '][0]['data']);
					$thisfile_audio['wformattag'] = $thisfile_riff_audio[$streamindex]['raw']['wFormatTag'];
					if (!isset($thisfile_riff_audio[$streamindex]['bitrate']) || ($thisfile_riff_audio[$streamindex]['bitrate'] == 0)) {
						$this->error('Corrupt RIFF file: bitrate_audio == zero');
						return false;
					}
					$thisfile_riff_raw['fmt '] = $thisfile_riff_audio[$streamindex]['raw'];
					unset($thisfile_riff_audio[$streamindex]['raw']);
					$thisfile_audio['streams'][$streamindex] = $thisfile_riff_audio[$streamindex];

					$thisfile_audio = getid3_lib::array_merge_noclobber($thisfile_audio, $thisfile_riff_audio[$streamindex]);
					if (substr($thisfile_audio['codec'], 0, strlen('unknown: 0x')) == 'unknown: 0x') {
						$this->warning('Audio codec = '.$thisfile_audio['codec']);
					}
					$thisfile_audio['bitrate'] = $thisfile_riff_audio[$streamindex]['bitrate'];

					if (empty($info['playtime_seconds'])) { // may already be set (e.g. DTS-WAV)
						$info['playtime_seconds'] = (float) ((($info['avdataend'] - $info['avdataoffset']) * 8) / $thisfile_audio['bitrate']);
					}

					$thisfile_audio['lossless'] = false;
					if (isset($thisfile_riff_WAVE['data'][0]['offset']) && isset($thisfile_riff_raw['fmt ']['wFormatTag'])) {
						switch ($thisfile_riff_raw['fmt ']['wFormatTag']) {

							case 0x0001:  // PCM
								$thisfile_audio['lossless'] = true;
								break;

							case 0x2000:  // AC-3
								$thisfile_audio_dataformat = 'ac3';
								break;

							default:
								// do nothing
								break;

						}
					}
					$thisfile_audio['streams'][$streamindex]['wformattag']   = $thisfile_audio['wformattag'];
					$thisfile_audio['streams'][$streamindex]['bitrate_mode'] = $thisfile_audio['bitrate_mode'];
					$thisfile_audio['streams'][$streamindex]['lossless']     = $thisfile_audio['lossless'];
					$thisfile_audio['streams'][$streamindex]['dataformat']   = $thisfile_audio_dataformat;
				}

				if (isset($thisfile_riff_WAVE['rgad'][0]['data'])) {

					// shortcuts
					$rgadData = &$thisfile_riff_WAVE['rgad'][0]['data'];
					$thisfile_riff_raw['rgad']    = array('track'=>array(), 'album'=>array());
					$thisfile_riff_raw_rgad       = &$thisfile_riff_raw['rgad'];
					$thisfile_riff_raw_rgad_track = &$thisfile_riff_raw_rgad['track'];
					$thisfile_riff_raw_rgad_album = &$thisfile_riff_raw_rgad['album'];

					$thisfile_riff_raw_rgad['fPeakAmplitude']      = getid3_lib::LittleEndian2Float(substr($rgadData, 0, 4));
					$thisfile_riff_raw_rgad['nRadioRgAdjust']      =        $this->EitherEndian2Int(substr($rgadData, 4, 2));
					$thisfile_riff_raw_rgad['nAudiophileRgAdjust'] =        $this->EitherEndian2Int(substr($rgadData, 6, 2));

					$nRadioRgAdjustBitstring      = str_pad(getid3_lib::Dec2Bin($thisfile_riff_raw_rgad['nRadioRgAdjust']), 16, '0', STR_PAD_LEFT);
					$nAudiophileRgAdjustBitstring = str_pad(getid3_lib::Dec2Bin($thisfile_riff_raw_rgad['nAudiophileRgAdjust']), 16, '0', STR_PAD_LEFT);
					$thisfile_riff_raw_rgad_track['name']       = getid3_lib::Bin2Dec(substr($nRadioRgAdjustBitstring, 0, 3));
					$thisfile_riff_raw_rgad_track['originator'] = getid3_lib::Bin2Dec(substr($nRadioRgAdjustBitstring, 3, 3));
					$thisfile_riff_raw_rgad_track['signbit']    = getid3_lib::Bin2Dec(substr($nRadioRgAdjustBitstring, 6, 1));
					$thisfile_riff_raw_rgad_track['adjustment'] = getid3_lib::Bin2Dec(substr($nRadioRgAdjustBitstring, 7, 9));
					$thisfile_riff_raw_rgad_album['name']       = getid3_lib::Bin2Dec(substr($nAudiophileRgAdjustBitstring, 0, 3));
					$thisfile_riff_raw_rgad_album['originator'] = getid3_lib::Bin2Dec(substr($nAudiophileRgAdjustBitstring, 3, 3));
					$thisfile_riff_raw_rgad_album['signbit']    = getid3_lib::Bin2Dec(substr($nAudiophileRgAdjustBitstring, 6, 1));
					$thisfile_riff_raw_rgad_album['adjustment'] = getid3_lib::Bin2Dec(substr($nAudiophileRgAdjustBitstring, 7, 9));

					$thisfile_riff['rgad']['peakamplitude'] = $thisfile_riff_raw_rgad['fPeakAmplitude'];
					if (($thisfile_riff_raw_rgad_track['name'] != 0) && ($thisfile_riff_raw_rgad_track['originator'] != 0)) {
						$thisfile_riff['rgad']['track']['name']            = getid3_lib::RGADnameLookup($thisfile_riff_raw_rgad_track['name']);
						$thisfile_riff['rgad']['track']['originator']      = getid3_lib::RGADoriginatorLookup($thisfile_riff_raw_rgad_track['originator']);
						$thisfile_riff['rgad']['track']['adjustment']      = getid3_lib::RGADadjustmentLookup($thisfile_riff_raw_rgad_track['adjustment'], $thisfile_riff_raw_rgad_track['signbit']);
					}
					if (($thisfile_riff_raw_rgad_album['name'] != 0) && ($thisfile_riff_raw_rgad_album['originator'] != 0)) {
						$thisfile_riff['rgad']['album']['name']       = getid3_lib::RGADnameLookup($thisfile_riff_raw_rgad_album['name']);
						$thisfile_riff['rgad']['album']['originator'] = getid3_lib::RGADoriginatorLookup($thisfile_riff_raw_rgad_album['originator']);
						$thisfile_riff['rgad']['album']['adjustment'] = getid3_lib::RGADadjustmentLookup($thisfile_riff_raw_rgad_album['adjustment'], $thisfile_riff_raw_rgad_album['signbit']);
					}
				}

				if (isset($thisfile_riff_WAVE['fact'][0]['data'])) {
					$thisfile_riff_raw['fact']['NumberOfSamples'] = $this->EitherEndian2Int(substr($thisfile_riff_WAVE['fact'][0]['data'], 0, 4));

					// This should be a good way of calculating exact playtime,
					// but some sample files have had incorrect number of samples,
					// so cannot use this method

					// if (!empty($thisfile_riff_raw['fmt ']['nSamplesPerSec'])) {
					//     $info['playtime_seconds'] = (float) $thisfile_riff_raw['fact']['NumberOfSamples'] / $thisfile_riff_raw['fmt ']['nSamplesPerSec'];
					// }
				}
				if (!empty($thisfile_riff_raw['fmt ']['nAvgBytesPerSec'])) {
					$thisfile_audio['bitrate'] = getid3_lib::CastAsInt($thisfile_riff_raw['fmt ']['nAvgBytesPerSec'] * 8);
				}

				if (isset($thisfile_riff_WAVE['bext'][0]['data'])) {
					// shortcut
					$thisfile_riff_WAVE_bext_0 = &$thisfile_riff_WAVE['bext'][0];

					$thisfile_riff_WAVE_bext_0['title']          =                         trim(substr($thisfile_riff_WAVE_bext_0['data'],   0, 256));
					$thisfile_riff_WAVE_bext_0['author']         =                         trim(substr($thisfile_riff_WAVE_bext_0['data'], 256,  32));
					$thisfile_riff_WAVE_bext_0['reference']      =                         trim(substr($thisfile_riff_WAVE_bext_0['data'], 288,  32));
					$thisfile_riff_WAVE_bext_0['origin_date']    =                              substr($thisfile_riff_WAVE_bext_0['data'], 320,  10);
					$thisfile_riff_WAVE_bext_0['origin_time']    =                              substr($thisfile_riff_WAVE_bext_0['data'], 330,   8);
					$thisfile_riff_WAVE_bext_0['time_reference'] = getid3_lib::LittleEndian2Int(substr($thisfile_riff_WAVE_bext_0['data'], 338,   8));
					$thisfile_riff_WAVE_bext_0['bwf_version']    = getid3_lib::LittleEndian2Int(substr($thisfile_riff_WAVE_bext_0['data'], 346,   1));
					$thisfile_riff_WAVE_bext_0['reserved']       =                              substr($thisfile_riff_WAVE_bext_0['data'], 347, 254);
					$thisfile_riff_WAVE_bext_0['coding_history'] =         explode("\r\n", trim(substr($thisfile_riff_WAVE_bext_0['data'], 601)));
					if (preg_match('#^([0-9]{4}).([0-9]{2}).([0-9]{2})$#', $thisfile_riff_WAVE_bext_0['origin_date'], $matches_bext_date)) {
						if (preg_match('#^([0-9]{2}).([0-9]{2}).([0-9]{2})$#', $thisfile_riff_WAVE_bext_0['origin_time'], $matches_bext_time)) {
							list($dummy, $bext_timestamp['year'], $bext_timestamp['month'],  $bext_timestamp['day'])    = $matches_bext_date;
							list($dummy, $bext_timestamp['hour'], $bext_timestamp['minute'], $bext_timestamp['second']) = $matches_bext_time;
							$thisfile_riff_WAVE_bext_0['origin_date_unix'] = gmmktime($bext_timestamp['hour'], $bext_timestamp['minute'], $bext_timestamp['second'], $bext_timestamp['month'], $bext_timestamp['day'], $bext_timestamp['year']);
						} else {
							$this->warning('RIFF.WAVE.BEXT.origin_time is invalid');
						}
					} else {
						$this->warning('RIFF.WAVE.BEXT.origin_date is invalid');
					}
					$thisfile_riff['comments']['author'][] = $thisfile_riff_WAVE_bext_0['author'];
					$thisfile_riff['comments']['title'][]  = $thisfile_riff_WAVE_bext_0['title'];
				}

				if (isset($thisfile_riff_WAVE['MEXT'][0]['data'])) {
					// shortcut
					$thisfile_riff_WAVE_MEXT_0 = &$thisfile_riff_WAVE['MEXT'][0];

					$thisfile_riff_WAVE_MEXT_0['raw']['sound_information']      = getid3_lib::LittleEndian2Int(substr($thisfile_riff_WAVE_MEXT_0['data'], 0, 2));
					$thisfile_riff_WAVE_MEXT_0['flags']['homogenous']           = (bool) ($thisfile_riff_WAVE_MEXT_0['raw']['sound_information'] & 0x0001);
					if ($thisfile_riff_WAVE_MEXT_0['flags']['homogenous']) {
						$thisfile_riff_WAVE_MEXT_0['flags']['padding']          = ($thisfile_riff_WAVE_MEXT_0['raw']['sound_information'] & 0x0002) ? false : true;
						$thisfile_riff_WAVE_MEXT_0['flags']['22_or_44']         =        (bool) ($thisfile_riff_WAVE_MEXT_0['raw']['sound_information'] & 0x0004);
						$thisfile_riff_WAVE_MEXT_0['flags']['free_format']      =        (bool) ($thisfile_riff_WAVE_MEXT_0['raw']['sound_information'] & 0x0008);

						$thisfile_riff_WAVE_MEXT_0['nominal_frame_size']        = getid3_lib::LittleEndian2Int(substr($thisfile_riff_WAVE_MEXT_0['data'], 2, 2));
					}
					$thisfile_riff_WAVE_MEXT_0['anciliary_data_length']         = getid3_lib::LittleEndian2Int(substr($thisfile_riff_WAVE_MEXT_0['data'], 6, 2));
					$thisfile_riff_WAVE_MEXT_0['raw']['anciliary_data_def']     = getid3_lib::LittleEndian2Int(substr($thisfile_riff_WAVE_MEXT_0['data'], 8, 2));
					$thisfile_riff_WAVE_MEXT_0['flags']['anciliary_data_left']  = (bool) ($thisfile_riff_WAVE_MEXT_0['raw']['anciliary_data_def'] & 0x0001);
					$thisfile_riff_WAVE_MEXT_0['flags']['anciliary_data_free']  = (bool) ($thisfile_riff_WAVE_MEXT_0['raw']['anciliary_data_def'] & 0x0002);
					$thisfile_riff_WAVE_MEXT_0['flags']['anciliary_data_right'] = (bool) ($thisfile_riff_WAVE_MEXT_0['raw']['anciliary_data_def'] & 0x0004);
				}

				if (isset($thisfile_riff_WAVE['cart'][0]['data'])) {
					// shortcut
					$thisfile_riff_WAVE_cart_0 = &$thisfile_riff_WAVE['cart'][0];

					$thisfile_riff_WAVE_cart_0['version']              =                              substr($thisfile_riff_WAVE_cart_0['data'],   0,  4);
					$thisfile_riff_WAVE_cart_0['title']                =                         trim(substr($thisfile_riff_WAVE_cart_0['data'],   4, 64));
					$thisfile_riff_WAVE_cart_0['artist']               =                         trim(substr($thisfile_riff_WAVE_cart_0['data'],  68, 64));
					$thisfile_riff_WAVE_cart_0['cut_id']               =                         trim(substr($thisfile_riff_WAVE_cart_0['data'], 132, 64));
					$thisfile_riff_WAVE_cart_0['client_id']            =                         trim(substr($thisfile_riff_WAVE_cart_0['data'], 196, 64));
					$thisfile_riff_WAVE_cart_0['category']             =                         trim(substr($thisfile_riff_WAVE_cart_0['data'], 260, 64));
					$thisfile_riff_WAVE_cart_0['classification']       =                         trim(substr($thisfile_riff_WAVE_cart_0['data'], 324, 64));
					$thisfile_riff_WAVE_cart_0['out_cue']              =                         trim(substr($thisfile_riff_WAVE_cart_0['data'], 388, 64));
					$thisfile_riff_WAVE_cart_0['start_date']           =                         trim(substr($thisfile_riff_WAVE_cart_0['data'], 452, 10));
					$thisfile_riff_WAVE_cart_0['start_time']           =                         trim(substr($thisfile_riff_WAVE_cart_0['data'], 462,  8));
					$thisfile_riff_WAVE_cart_0['end_date']             =                         trim(substr($thisfile_riff_WAVE_cart_0['data'], 470, 10));
					$thisfile_riff_WAVE_cart_0['end_time']             =                         trim(substr($thisfile_riff_WAVE_cart_0['data'], 480,  8));
					$thisfile_riff_WAVE_cart_0['producer_app_id']      =                         trim(substr($thisfile_riff_WAVE_cart_0['data'], 488, 64));
					$thisfile_riff_WAVE_cart_0['producer_app_version'] =                         trim(substr($thisfile_riff_WAVE_cart_0['data'], 552, 64));
					$thisfile_riff_WAVE_cart_0['user_defined_text']    =                         trim(substr($thisfile_riff_WAVE_cart_0['data'], 616, 64));
					$thisfile_riff_WAVE_cart_0['zero_db_reference']    = getid3_lib::LittleEndian2Int(substr($thisfile_riff_WAVE_cart_0['data'], 680,  4), true);
					for ($i = 0; $i < 8; $i++) {
						$thisfile_riff_WAVE_cart_0['post_time'][$i]['usage_fourcc'] =                  substr($thisfile_riff_WAVE_cart_0['data'], 684 + ($i * 8), 4);
						$thisfile_riff_WAVE_cart_0['post_time'][$i]['timer_value']  = getid3_lib::LittleEndian2Int(substr($thisfile_riff_WAVE_cart_0['data'], 684 + ($i * 8) + 4, 4));
					}
					$thisfile_riff_WAVE_cart_0['url']              =                 trim(substr($thisfile_riff_WAVE_cart_0['data'],  748, 1024));
					$thisfile_riff_WAVE_cart_0['tag_text']         = explode("\r\n", trim(substr($thisfile_riff_WAVE_cart_0['data'], 1772)));
					$thisfile_riff['comments']['tag_text'][]       =                      substr($thisfile_riff_WAVE_cart_0['data'], 1772);

					$thisfile_riff['comments']['artist'][] = $thisfile_riff_WAVE_cart_0['artist'];
					$thisfile_riff['comments']['title'][]  = $thisfile_riff_WAVE_cart_0['title'];
				}

				if (isset($thisfile_riff_WAVE['SNDM'][0]['data'])) {
					// SoundMiner metadata

					// shortcuts
					$thisfile_riff_WAVE_SNDM_0      = &$thisfile_riff_WAVE['SNDM'][0];
					$thisfile_riff_WAVE_SNDM_0_data = &$thisfile_riff_WAVE_SNDM_0['data'];
					$SNDM_startoffset = 0;
					$SNDM_endoffset   = $thisfile_riff_WAVE_SNDM_0['size'];

					while ($SNDM_startoffset < $SNDM_endoffset) {
						$SNDM_thisTagOffset = 0;
						$SNDM_thisTagSize      = getid3_lib::BigEndian2Int(substr($thisfile_riff_WAVE_SNDM_0_data, $SNDM_startoffset + $SNDM_thisTagOffset, 4));
						$SNDM_thisTagOffset += 4;
						$SNDM_thisTagKey       =                           substr($thisfile_riff_WAVE_SNDM_0_data, $SNDM_startoffset + $SNDM_thisTagOffset, 4);
						$SNDM_thisTagOffset += 4;
						$SNDM_thisTagDataSize  = getid3_lib::BigEndian2Int(substr($thisfile_riff_WAVE_SNDM_0_data, $SNDM_startoffset + $SNDM_thisTagOffset, 2));
						$SNDM_thisTagOffset += 2;
						$SNDM_thisTagDataFlags = getid3_lib::BigEndian2Int(substr($thisfile_riff_WAVE_SNDM_0_data, $SNDM_startoffset + $SNDM_thisTagOffset, 2));
						$SNDM_thisTagOffset += 2;
						$SNDM_thisTagDataText =                            substr($thisfile_riff_WAVE_SNDM_0_data, $SNDM_startoffset + $SNDM_thisTagOffset, $SNDM_thisTagDataSize);
						$SNDM_thisTagOffset += $SNDM_thisTagDataSize;

						if ($SNDM_thisTagSize != (4 + 4 + 2 + 2 + $SNDM_thisTagDataSize)) {
							$this->warning('RIFF.WAVE.SNDM.data contains tag not expected length (expected: '.$SNDM_thisTagSize.', found: '.(4 + 4 + 2 + 2 + $SNDM_thisTagDataSize).') at offset '.$SNDM_startoffset.' (file offset '.($thisfile_riff_WAVE_SNDM_0['offset'] + $SNDM_startoffset).')');
							break;
						} elseif ($SNDM_thisTagSize <= 0) {
							$this->warning('RIFF.WAVE.SNDM.data contains zero-size tag at offset '.$SNDM_startoffset.' (file offset '.($thisfile_riff_WAVE_SNDM_0['offset'] + $SNDM_startoffset).')');
							break;
						}
						$SNDM_startoffset += $SNDM_thisTagSize;

						$thisfile_riff_WAVE_SNDM_0['parsed_raw'][$SNDM_thisTagKey] = $SNDM_thisTagDataText;
						if ($parsedkey = self::waveSNDMtagLookup($SNDM_thisTagKey)) {
							$thisfile_riff_WAVE_SNDM_0['parsed'][$parsedkey] = $SNDM_thisTagDataText;
						} else {
							$this->warning('RIFF.WAVE.SNDM contains unknown tag "'.$SNDM_thisTagKey.'" at offset '.$SNDM_startoffset.' (file offset '.($thisfile_riff_WAVE_SNDM_0['offset'] + $SNDM_startoffset).')');
						}
					}

					$tagmapping = array(
						'tracktitle'=>'title',
						'category'  =>'genre',
						'cdtitle'   =>'album',
					);
					foreach ($tagmapping as $fromkey => $tokey) {
						if (isset($thisfile_riff_WAVE_SNDM_0['parsed'][$fromkey])) {
							$thisfile_riff['comments'][$tokey][] = $thisfile_riff_WAVE_SNDM_0['parsed'][$fromkey];
						}
					}
				}

				if (isset($thisfile_riff_WAVE['iXML'][0]['data'])) {
					// requires functions simplexml_load_string and get_object_vars
					if ($parsedXML = getid3_lib::XML2array($thisfile_riff_WAVE['iXML'][0]['data'])) {
						$thisfile_riff_WAVE['iXML'][0]['parsed'] = $parsedXML;
						if (isset($parsedXML['SPEED']['MASTER_SPEED'])) {
							@list($numerator, $denominator) = explode('/', $parsedXML['SPEED']['MASTER_SPEED']);
							$thisfile_riff_WAVE['iXML'][0]['master_speed'] = $numerator / ($denominator ? $denominator : 1000);
						}
						if (isset($parsedXML['SPEED']['TIMECODE_RATE'])) {
							@list($numerator, $denominator) = explode('/', $parsedXML['SPEED']['TIMECODE_RATE']);
							$thisfile_riff_WAVE['iXML'][0]['timecode_rate'] = $numerator / ($denominator ? $denominator : 1000);
						}
						if (isset($parsedXML['SPEED']['TIMESTAMP_SAMPLES_SINCE_MIDNIGHT_LO']) && !empty($parsedXML['SPEED']['TIMESTAMP_SAMPLE_RATE']) && !empty($thisfile_riff_WAVE['iXML'][0]['timecode_rate'])) {
							$samples_since_midnight = floatval(ltrim($parsedXML['SPEED']['TIMESTAMP_SAMPLES_SINCE_MIDNIGHT_HI'].$parsedXML['SPEED']['TIMESTAMP_SAMPLES_SINCE_MIDNIGHT_LO'], '0'));
							$timestamp_sample_rate = (is_array($parsedXML['SPEED']['TIMESTAMP_SAMPLE_RATE']) ? max($parsedXML['SPEED']['TIMESTAMP_SAMPLE_RATE']) : $parsedXML['SPEED']['TIMESTAMP_SAMPLE_RATE']); // XML could possibly contain more than one TIMESTAMP_SAMPLE_RATE tag, returning as array instead of integer [why? does it make sense? perhaps doesn't matter but getID3 needs to deal with it] - see https://github.com/JamesHeinrich/getID3/issues/105
							$thisfile_riff_WAVE['iXML'][0]['timecode_seconds'] = $samples_since_midnight / $timestamp_sample_rate;
							$h = floor( $thisfile_riff_WAVE['iXML'][0]['timecode_seconds']       / 3600);
							$m = floor(($thisfile_riff_WAVE['iXML'][0]['timecode_seconds'] - ($h * 3600))      / 60);
							$s = floor( $thisfile_riff_WAVE['iXML'][0]['timecode_seconds'] - ($h * 3600) - ($m * 60));
							$f =       ($thisfile_riff_WAVE['iXML'][0]['timecode_seconds'] - ($h * 3600) - ($m * 60) - $s) * $thisfile_riff_WAVE['iXML'][0]['timecode_rate'];
							$thisfile_riff_WAVE['iXML'][0]['timecode_string']       = sprintf('%02d:%02d:%02d:%05.2f', $h, $m, $s,       $f);
							$thisfile_riff_WAVE['iXML'][0]['timecode_string_round'] = sprintf('%02d:%02d:%02d:%02d',   $h, $m, $s, round($f));
							unset($samples_since_midnight, $timestamp_sample_rate, $h, $m, $s, $f);
						}
						unset($parsedXML);
					}
				}



				if (!isset($thisfile_audio['bitrate']) && isset($thisfile_riff_audio[$streamindex]['bitrate'])) {
					$thisfile_audio['bitrate'] = $thisfile_riff_audio[$streamindex]['bitrate'];
					$info['playtime_seconds'] = (float) ((($info['avdataend'] - $info['avdataoffset']) * 8) / $thisfile_audio['bitrate']);
				}

				if (!empty($info['wavpack'])) {
					$thisfile_audio_dataformat = 'wavpack';
					$thisfile_audio['bitrate_mode'] = 'vbr';
					$thisfile_audio['encoder']      = 'WavPack v'.$info['wavpack']['version'];

					// Reset to the way it was - RIFF parsing will have messed this up
					$info['avdataend']        = $Original['avdataend'];
					$thisfile_audio['bitrate'] = (($info['avdataend'] - $info['avdataoffset']) * 8) / $info['playtime_seconds'];

					$this->fseek($info['avdataoffset'] - 44);
					$RIFFdata = $this->fread(44);
					$OrignalRIFFheaderSize = getid3_lib::LittleEndian2Int(substr($RIFFdata,  4, 4)) +  8;
					$OrignalRIFFdataSize   = getid3_lib::LittleEndian2Int(substr($RIFFdata, 40, 4)) + 44;

					if ($OrignalRIFFheaderSize > $OrignalRIFFdataSize) {
						$info['avdataend'] -= ($OrignalRIFFheaderSize - $OrignalRIFFdataSize);
						$this->fseek($info['avdataend']);
						$RIFFdata .= $this->fread($OrignalRIFFheaderSize - $OrignalRIFFdataSize);
					}

					// move the data chunk after all other chunks (if any)
					// so that the RIFF parser doesn't see EOF when trying
					// to skip over the data chunk
					$RIFFdata = substr($RIFFdata, 0, 36).substr($RIFFdata, 44).substr($RIFFdata, 36, 8);
					$getid3_riff = new getid3_riff($this->getid3);
					$getid3_riff->ParseRIFFdata($RIFFdata);
					unset($getid3_riff);
				}

				if (isset($thisfile_riff_raw['fmt ']['wFormatTag'])) {
					switch ($thisfile_riff_raw['fmt ']['wFormatTag']) {
						case 0x0001: // PCM
							if (!empty($info['ac3'])) {
								// Dolby Digital WAV files masquerade as PCM-WAV, but they're not
								$thisfile_audio['wformattag']  = 0x2000;
								$thisfile_audio['codec']       = self::wFormatTagLookup($thisfile_audio['wformattag']);
								$thisfile_audio['lossless']    = false;
								$thisfile_audio['bitrate']     = $info['ac3']['bitrate'];
								$thisfile_audio['sample_rate'] = $info['ac3']['sample_rate'];
							}
							if (!empty($info['dts'])) {
								// Dolby DTS files masquerade as PCM-WAV, but they're not
								$thisfile_audio['wformattag']  = 0x2001;
								$thisfile_audio['codec']       = self::wFormatTagLookup($thisfile_audio['wformattag']);
								$thisfile_audio['lossless']    = false;
								$thisfile_audio['bitrate']     = $info['dts']['bitrate'];
								$thisfile_audio['sample_rate'] = $info['dts']['sample_rate'];
							}
							break;
						case 0x08AE: // ClearJump LiteWave
							$thisfile_audio['bitrate_mode'] = 'vbr';
							$thisfile_audio_dataformat   = 'litewave';

							//typedef struct tagSLwFormat {
							//  WORD    m_wCompFormat;     // low byte defines compression method, high byte is compression flags
							//  DWORD   m_dwScale;         // scale factor for lossy compression
							//  DWORD   m_dwBlockSize;     // number of samples in encoded blocks
							//  WORD    m_wQuality;        // alias for the scale factor
							//  WORD    m_wMarkDistance;   // distance between marks in bytes
							//  WORD    m_wReserved;
							//
							//  //following paramters are ignored if CF_FILESRC is not set
							//  DWORD   m_dwOrgSize;       // original file size in bytes
							//  WORD    m_bFactExists;     // indicates if 'fact' chunk exists in the original file
							//  DWORD   m_dwRiffChunkSize; // riff chunk size in the original file
							//
							//  PCMWAVEFORMAT m_OrgWf;     // original wave format
							// }SLwFormat, *PSLwFormat;

							// shortcut
							$thisfile_riff['litewave']['raw'] = array();
							$riff_litewave     = &$thisfile_riff['litewave'];
							$riff_litewave_raw = &$riff_litewave['raw'];

							$flags = array(
								'compression_method' => 1,
								'compression_flags'  => 1,
								'm_dwScale'          => 4,
								'm_dwBlockSize'      => 4,
								'm_wQuality'         => 2,
								'm_wMarkDistance'    => 2,
								'm_wReserved'        => 2,
								'm_dwOrgSize'        => 4,
								'm_bFactExists'      => 2,
								'm_dwRiffChunkSize'  => 4,
							);
							$litewave_offset = 18;
							foreach ($flags as $flag => $length) {
								$riff_litewave_raw[$flag] = getid3_lib::LittleEndian2Int(substr($thisfile_riff_WAVE['fmt '][0]['data'], $litewave_offset, $length));
								$litewave_offset += $length;
							}

							//$riff_litewave['quality_factor'] = intval(round((2000 - $riff_litewave_raw['m_dwScale']) / 20));
							$riff_litewave['quality_factor'] = $riff_litewave_raw['m_wQuality'];

							$riff_litewave['flags']['raw_source']    = ($riff_litewave_raw['compression_flags'] & 0x01) ? false : true;
							$riff_litewave['flags']['vbr_blocksize'] = ($riff_litewave_raw['compression_flags'] & 0x02) ? false : true;
							$riff_litewave['flags']['seekpoints']    =        (bool) ($riff_litewave_raw['compression_flags'] & 0x04);

							$thisfile_audio['lossless']        = (($riff_litewave_raw['m_wQuality'] == 100) ? true : false);
							$thisfile_audio['encoder_options'] = '-q'.$riff_litewave['quality_factor'];
							break;

						default:
							break;
					}
				}
				if ($info['avdataend'] > $info['filesize']) {
					switch (!empty($thisfile_audio_dataformat) ? $thisfile_audio_dataformat : '') {
						case 'wavpack': // WavPack
						case 'lpac':    // LPAC
						case 'ofr':     // OptimFROG
						case 'ofs':     // OptimFROG DualStream
							// lossless compressed audio formats that keep original RIFF headers - skip warning
							break;

						case 'litewave':
							if (($info['avdataend'] - $info['filesize']) == 1) {
								// LiteWave appears to incorrectly *not* pad actual output file
								// to nearest WORD boundary so may appear to be short by one
								// byte, in which case - skip warning
							} else {
								// Short by more than one byte, throw warning
								$this->warning('Probably truncated file - expecting '.$thisfile_riff[$RIFFsubtype]['data'][0]['size'].' bytes of data, only found '.($info['filesize'] - $info['avdataoffset']).' (short by '.($thisfile_riff[$RIFFsubtype]['data'][0]['size'] - ($info['filesize'] - $info['avdataoffset'])).' bytes)');
								$info['avdataend'] = $info['filesize'];
							}
							break;

						default:
							if ((($info['avdataend'] - $info['filesize']) == 1) && (($thisfile_riff[$RIFFsubtype]['data'][0]['size'] % 2) == 0) && ((($info['filesize'] - $info['avdataoffset']) % 2) == 1)) {
								// output file appears to be incorrectly *not* padded to nearest WORD boundary
								// Output less severe warning
								$this->warning('File should probably be padded to nearest WORD boundary, but it is not (expecting '.$thisfile_riff[$RIFFsubtype]['data'][0]['size'].' bytes of data, only found '.($info['filesize'] - $info['avdataoffset']).' therefore short by '.($thisfile_riff[$RIFFsubtype]['data'][0]['size'] - ($info['filesize'] - $info['avdataoffset'])).' bytes)');
								$info['avdataend'] = $info['filesize'];
							} else {
								// Short by more than one byte, throw warning
								$this->warning('Probably truncated file - expecting '.$thisfile_riff[$RIFFsubtype]['data'][0]['size'].' bytes of data, only found '.($info['filesize'] - $info['avdataoffset']).' (short by '.($thisfile_riff[$RIFFsubtype]['data'][0]['size'] - ($info['filesize'] - $info['avdataoffset'])).' bytes)');
								$info['avdataend'] = $info['filesize'];
							}
							break;
					}
				}
				if (!empty($info['mpeg']['audio']['LAME']['audio_bytes'])) {
					if ((($info['avdataend'] - $info['avdataoffset']) - $info['mpeg']['audio']['LAME']['audio_bytes']) == 1) {
						$info['avdataend']--;
						$this->warning('Extra null byte at end of MP3 data assumed to be RIFF padding and therefore ignored');
					}
				}
				if (isset($thisfile_audio_dataformat) && ($thisfile_audio_dataformat == 'ac3')) {
					unset($thisfile_audio['bits_per_sample']);
					if (!empty($info['ac3']['bitrate']) && ($info['ac3']['bitrate'] != $thisfile_audio['bitrate'])) {
						$thisfile_audio['bitrate'] = $info['ac3']['bitrate'];
					}
				}
				break;

			// http://en.wikipedia.org/wiki/Audio_Video_Interleave
			case 'AVI ':
				$info['fileformat'] = 'avi';
				$info['mime_type']  = 'video/avi';

				$thisfile_video['bitrate_mode'] = 'vbr'; // maybe not, but probably
				$thisfile_video['dataformat']   = 'avi';

				$thisfile_riff_video_current = array();

				if (isset($thisfile_riff[$RIFFsubtype]['movi']['offset'])) {
					$info['avdataoffset'] = $thisfile_riff[$RIFFsubtype]['movi']['offset'] + 8;
					if (isset($thisfile_riff['AVIX'])) {
						$info['avdataend'] = $thisfile_riff['AVIX'][(count($thisfile_riff['AVIX']) - 1)]['chunks']['movi']['offset'] + $thisfile_riff['AVIX'][(count($thisfile_riff['AVIX']) - 1)]['chunks']['movi']['size'];
					} else {
						$info['avdataend'] = $thisfile_riff['AVI ']['movi']['offset'] + $thisfile_riff['AVI ']['movi']['size'];
					}
					if ($info['avdataend'] > $info['filesize']) {
						$this->warning('Probably truncated file - expecting '.($info['avdataend'] - $info['avdataoffset']).' bytes of data, only found '.($info['filesize'] - $info['avdataoffset']).' (short by '.($info['avdataend'] - $info['filesize']).' bytes)');
						$info['avdataend'] = $info['filesize'];
					}
				}

				if (isset($thisfile_riff['AVI ']['hdrl']['strl']['indx'])) {
					//$bIndexType = array(
					//	0x00 => 'AVI_INDEX_OF_INDEXES',
					//	0x01 => 'AVI_INDEX_OF_CHUNKS',
					//	0x80 => 'AVI_INDEX_IS_DATA',
					//);
					//$bIndexSubtype = array(
					//	0x01 => array(
					//		0x01 => 'AVI_INDEX_2FIELD',
					//	),
					//);
					foreach ($thisfile_riff['AVI ']['hdrl']['strl']['indx'] as $streamnumber => $steamdataarray) {
						$ahsisd = &$thisfile_riff['AVI ']['hdrl']['strl']['indx'][$streamnumber]['data'];

						$thisfile_riff_raw['indx'][$streamnumber]['wLongsPerEntry'] = $this->EitherEndian2Int(substr($ahsisd,  0, 2));
						$thisfile_riff_raw['indx'][$streamnumber]['bIndexSubType']  = $this->EitherEndian2Int(substr($ahsisd,  2, 1));
						$thisfile_riff_raw['indx'][$streamnumber]['bIndexType']     = $this->EitherEndian2Int(substr($ahsisd,  3, 1));
						$thisfile_riff_raw['indx'][$streamnumber]['nEntriesInUse']  = $this->EitherEndian2Int(substr($ahsisd,  4, 4));
						$thisfile_riff_raw['indx'][$streamnumber]['dwChunkId']      =                         substr($ahsisd,  8, 4);
						$thisfile_riff_raw['indx'][$streamnumber]['dwReserved']     = $this->EitherEndian2Int(substr($ahsisd, 12, 4));

						//$thisfile_riff_raw['indx'][$streamnumber]['bIndexType_name']    =    $bIndexType[$thisfile_riff_raw['indx'][$streamnumber]['bIndexType']];
						//$thisfile_riff_raw['indx'][$streamnumber]['bIndexSubType_name'] = $bIndexSubtype[$thisfile_riff_raw['indx'][$streamnumber]['bIndexType']][$thisfile_riff_raw['indx'][$streamnumber]['bIndexSubType']];

						unset($ahsisd);
					}
				}
				if (isset($thisfile_riff['AVI ']['hdrl']['avih'][$streamindex]['data'])) {
					$avihData = $thisfile_riff['AVI ']['hdrl']['avih'][$streamindex]['data'];

					// shortcut
					$thisfile_riff_raw['avih'] = array();
					$thisfile_riff_raw_avih = &$thisfile_riff_raw['avih'];

					$thisfile_riff_raw_avih['dwMicroSecPerFrame']    = $this->EitherEndian2Int(substr($avihData,  0, 4)); // frame display rate (or 0L)
					if ($thisfile_riff_raw_avih['dwMicroSecPerFrame'] == 0) {
						$this->error('Corrupt RIFF file: avih.dwMicroSecPerFrame == zero');
						return false;
					}

					$flags = array(
						'dwMaxBytesPerSec',       // max. transfer rate
						'dwPaddingGranularity',   // pad to multiples of this size; normally 2K.
						'dwFlags',                // the ever-present flags
						'dwTotalFrames',          // # frames in file
						'dwInitialFrames',        //
						'dwStreams',              //
						'dwSuggestedBufferSize',  //
						'dwWidth',                //
						'dwHeight',               //
						'dwScale',                //
						'dwRate',                 //
						'dwStart',                //
						'dwLength',               //
					);
					$avih_offset = 4;
					foreach ($flags as $flag) {
						$thisfile_riff_raw_avih[$flag] = $this->EitherEndian2Int(substr($avihData, $avih_offset, 4));
						$avih_offset += 4;
					}

					$flags = array(
						'hasindex'     => 0x00000010,
						'mustuseindex' => 0x00000020,
						'interleaved'  => 0x00000100,
						'trustcktype'  => 0x00000800,
						'capturedfile' => 0x00010000,
						'copyrighted'  => 0x00020010,
					);
					foreach ($flags as $flag => $value) {
						$thisfile_riff_raw_avih['flags'][$flag] = (bool) ($thisfile_riff_raw_avih['dwFlags'] & $value);
					}

					// shortcut
					$thisfile_riff_video[$streamindex] = array();
					/** @var array $thisfile_riff_video_current */
					$thisfile_riff_video_current = &$thisfile_riff_video[$streamindex];

					if ($thisfile_riff_raw_avih['dwWidth'] > 0) {
						$thisfile_riff_video_current['frame_width'] = $thisfile_riff_raw_avih['dwWidth'];
						$thisfile_video['resolution_x']             = $thisfile_riff_video_current['frame_width'];
					}
					if ($thisfile_riff_raw_avih['dwHeight'] > 0) {
						$thisfile_riff_video_current['frame_height'] = $thisfile_riff_raw_avih['dwHeight'];
						$thisfile_video['resolution_y']              = $thisfile_riff_video_current['frame_height'];
					}
					if ($thisfile_riff_raw_avih['dwTotalFrames'] > 0) {
						$thisfile_riff_video_current['total_frames'] = $thisfile_riff_raw_avih['dwTotalFrames'];
						$thisfile_video['total_frames']              = $thisfile_riff_video_current['total_frames'];
					}

					$thisfile_riff_video_current['frame_rate'] = round(1000000 / $thisfile_riff_raw_avih['dwMicroSecPerFrame'], 3);
					$thisfile_video['frame_rate'] = $thisfile_riff_video_current['frame_rate'];
				}
				if (isset($thisfile_riff['AVI ']['hdrl']['strl']['strh'][0]['data'])) {
					if (is_array($thisfile_riff['AVI ']['hdrl']['strl']['strh'])) {
						for ($i = 0; $i < count($thisfile_riff['AVI ']['hdrl']['strl']['strh']); $i++) {
							if (isset($thisfile_riff['AVI ']['hdrl']['strl']['strh'][$i]['data'])) {
								$strhData = $thisfile_riff['AVI ']['hdrl']['strl']['strh'][$i]['data'];
								$strhfccType = substr($strhData,  0, 4);

								if (isset($thisfile_riff['AVI ']['hdrl']['strl']['strf'][$i]['data'])) {
									$strfData = $thisfile_riff['AVI ']['hdrl']['strl']['strf'][$i]['data'];

									// shortcut
									$thisfile_riff_raw_strf_strhfccType_streamindex = &$thisfile_riff_raw['strf'][$strhfccType][$streamindex];

									switch ($strhfccType) {
										case 'auds':
											$thisfile_audio['bitrate_mode'] = 'cbr';
											$thisfile_audio_dataformat      = 'wav';
											if (isset($thisfile_riff_audio) && is_array($thisfile_riff_audio)) {
												$streamindex = count($thisfile_riff_audio);
											}

											$thisfile_riff_audio[$streamindex] = self::parseWAVEFORMATex($strfData);
											$thisfile_audio['wformattag'] = $thisfile_riff_audio[$streamindex]['raw']['wFormatTag'];

											// shortcut
											$thisfile_audio['streams'][$streamindex] = $thisfile_riff_audio[$streamindex];
											$thisfile_audio_streams_currentstream = &$thisfile_audio['streams'][$streamindex];

											if ($thisfile_audio_streams_currentstream['bits_per_sample'] == 0) {
												unset($thisfile_audio_streams_currentstream['bits_per_sample']);
											}
											$thisfile_audio_streams_currentstream['wformattag'] = $thisfile_audio_streams_currentstream['raw']['wFormatTag'];
											unset($thisfile_audio_streams_currentstream['raw']);

											// shortcut
											$thisfile_riff_raw['strf'][$strhfccType][$streamindex] = $thisfile_riff_audio[$streamindex]['raw'];

											unset($thisfile_riff_audio[$streamindex]['raw']);
											$thisfile_audio = getid3_lib::array_merge_noclobber($thisfile_audio, $thisfile_riff_audio[$streamindex]);

											$thisfile_audio['lossless'] = false;
											switch ($thisfile_riff_raw_strf_strhfccType_streamindex['wFormatTag']) {
												case 0x0001:  // PCM
													$thisfile_audio_dataformat  = 'wav';
													$thisfile_audio['lossless'] = true;
													break;

												case 0x0050: // MPEG Layer 2 or Layer 1
													$thisfile_audio_dataformat = 'mp2'; // Assume Layer-2
													break;

												case 0x0055: // MPEG Layer 3
													$thisfile_audio_dataformat = 'mp3';
													break;

												case 0x00FF: // AAC
													$thisfile_audio_dataformat = 'aac';
													break;

												case 0x0161: // Windows Media v7 / v8 / v9
												case 0x0162: // Windows Media Professional v9
												case 0x0163: // Windows Media Lossess v9
													$thisfile_audio_dataformat = 'wma';
													break;

												case 0x2000: // AC-3
													$thisfile_audio_dataformat = 'ac3';
													break;

												case 0x2001: // DTS
													$thisfile_audio_dataformat = 'dts';
													break;

												default:
													$thisfile_audio_dataformat = 'wav';
													break;
											}
											$thisfile_audio_streams_currentstream['dataformat']   = $thisfile_audio_dataformat;
											$thisfile_audio_streams_currentstream['lossless']     = $thisfile_audio['lossless'];
											$thisfile_audio_streams_currentstream['bitrate_mode'] = $thisfile_audio['bitrate_mode'];
											break;


										case 'iavs':
										case 'vids':
											// shortcut
											$thisfile_riff_raw['strh'][$i]                  = array();
											$thisfile_riff_raw_strh_current                 = &$thisfile_riff_raw['strh'][$i];

											$thisfile_riff_raw_strh_current['fccType']               =                         substr($strhData,  0, 4);  // same as $strhfccType;
											$thisfile_riff_raw_strh_current['fccHandler']            =                         substr($strhData,  4, 4);
											$thisfile_riff_raw_strh_current['dwFlags']               = $this->EitherEndian2Int(substr($strhData,  8, 4)); // Contains AVITF_* flags
											$thisfile_riff_raw_strh_current['wPriority']             = $this->EitherEndian2Int(substr($strhData, 12, 2));
											$thisfile_riff_raw_strh_current['wLanguage']             = $this->EitherEndian2Int(substr($strhData, 14, 2));
											$thisfile_riff_raw_strh_current['dwInitialFrames']       = $this->EitherEndian2Int(substr($strhData, 16, 4));
											$thisfile_riff_raw_strh_current['dwScale']               = $this->EitherEndian2Int(substr($strhData, 20, 4));
											$thisfile_riff_raw_strh_current['dwRate']                = $this->EitherEndian2Int(substr($strhData, 24, 4));
											$thisfile_riff_raw_strh_current['dwStart']               = $this->EitherEndian2Int(substr($strhData, 28, 4));
											$thisfile_riff_raw_strh_current['dwLength']              = $this->EitherEndian2Int(substr($strhData, 32, 4));
											$thisfile_riff_raw_strh_current['dwSuggestedBufferSize'] = $this->EitherEndian2Int(substr($strhData, 36, 4));
											$thisfile_riff_raw_strh_current['dwQuality']             = $this->EitherEndian2Int(substr($strhData, 40, 4));
											$thisfile_riff_raw_strh_current['dwSampleSize']          = $this->EitherEndian2Int(substr($strhData, 44, 4));
											$thisfile_riff_raw_strh_current['rcFrame']               = $this->EitherEndian2Int(substr($strhData, 48, 4));

											$thisfile_riff_video_current['codec'] = self::fourccLookup($thisfile_riff_raw_strh_current['fccHandler']);
											$thisfile_video['fourcc']             = $thisfile_riff_raw_strh_current['fccHandler'];
											if (!$thisfile_riff_video_current['codec'] && isset($thisfile_riff_raw_strf_strhfccType_streamindex['fourcc']) && self::fourccLookup($thisfile_riff_raw_strf_strhfccType_streamindex['fourcc'])) {
												$thisfile_riff_video_current['codec'] = self::fourccLookup($thisfile_riff_raw_strf_strhfccType_streamindex['fourcc']);
												$thisfile_video['fourcc']             = $thisfile_riff_raw_strf_strhfccType_streamindex['fourcc'];
											}
											$thisfile_video['codec']              = $thisfile_riff_video_current['codec'];
											$thisfile_video['pixel_aspect_ratio'] = (float) 1;
											switch ($thisfile_riff_raw_strh_current['fccHandler']) {
												case 'HFYU': // Huffman Lossless Codec
												case 'IRAW': // Intel YUV Uncompressed
												case 'YUY2': // Uncompressed YUV 4:2:2
													$thisfile_video['lossless'] = true;
													break;

												default:
													$thisfile_video['lossless'] = false;
													break;
											}

											switch ($strhfccType) {
												case 'vids':
													$thisfile_riff_raw_strf_strhfccType_streamindex = self::ParseBITMAPINFOHEADER(substr($strfData, 0, 40), ($this->container == 'riff'));
													$thisfile_video['bits_per_sample'] = $thisfile_riff_raw_strf_strhfccType_streamindex['biBitCount'];

													if ($thisfile_riff_video_current['codec'] == 'DV') {
														$thisfile_riff_video_current['dv_type'] = 2;
													}
													break;

												case 'iavs':
													$thisfile_riff_video_current['dv_type'] = 1;
													break;
											}
											break;

										default:
											$this->warning('Unhandled fccType for stream ('.$i.'): "'.$strhfccType.'"');
											break;

									}
								}
							}

							if (isset($thisfile_riff_raw_strf_strhfccType_streamindex) && isset($thisfile_riff_raw_strf_strhfccType_streamindex['fourcc'])) {

								$thisfile_video['fourcc'] = $thisfile_riff_raw_strf_strhfccType_streamindex['fourcc'];
								if (self::fourccLookup($thisfile_video['fourcc'])) {
									$thisfile_riff_video_current['codec'] = self::fourccLookup($thisfile_video['fourcc']);
									$thisfile_video['codec']              = $thisfile_riff_video_current['codec'];
								}

								switch ($thisfile_riff_raw_strf_strhfccType_streamindex['fourcc']) {
									case 'HFYU': // Huffman Lossless Codec
									case 'IRAW': // Intel YUV Uncompressed
									case 'YUY2': // Uncompressed YUV 4:2:2
										$thisfile_video['lossless']        = true;
										//$thisfile_video['bits_per_sample'] = 24;
										break;

									default:
										$thisfile_video['lossless']        = false;
										//$thisfile_video['bits_per_sample'] = 24;
										break;
								}

							}
						}
					}
				}
				break;


			case 'AMV ':
				$info['fileformat'] = 'amv';
				$info['mime_type']  = 'video/amv';

				$thisfile_video['bitrate_mode']    = 'vbr'; // it's MJPEG, presumably contant-quality encoding, thereby VBR
				$thisfile_video['dataformat']      = 'mjpeg';
				$thisfile_video['codec']           = 'mjpeg';
				$thisfile_video['lossless']        = false;
				$thisfile_video['bits_per_sample'] = 24;

				$thisfile_audio['dataformat']   = 'adpcm';
				$thisfile_audio['lossless']     = false;
				break;


			// http://en.wikipedia.org/wiki/CD-DA
			case 'CDDA':
				$info['fileformat'] = 'cda';
				unset($info['mime_type']);

				$thisfile_audio_dataformat      = 'cda';

				$info['avdataoffset'] = 44;

				if (isset($thisfile_riff['CDDA']['fmt '][0]['data'])) {
					// shortcut
					$thisfile_riff_CDDA_fmt_0 = &$thisfile_riff['CDDA']['fmt '][0];

					$thisfile_riff_CDDA_fmt_0['unknown1']           = $this->EitherEndian2Int(substr($thisfile_riff_CDDA_fmt_0['data'],  0, 2));
					$thisfile_riff_CDDA_fmt_0['track_num']          = $this->EitherEndian2Int(substr($thisfile_riff_CDDA_fmt_0['data'],  2, 2));
					$thisfile_riff_CDDA_fmt_0['disc_id']            = $this->EitherEndian2Int(substr($thisfile_riff_CDDA_fmt_0['data'],  4, 4));
					$thisfile_riff_CDDA_fmt_0['start_offset_frame'] = $this->EitherEndian2Int(substr($thisfile_riff_CDDA_fmt_0['data'],  8, 4));
					$thisfile_riff_CDDA_fmt_0['playtime_frames']    = $this->EitherEndian2Int(substr($thisfile_riff_CDDA_fmt_0['data'], 12, 4));
					$thisfile_riff_CDDA_fmt_0['unknown6']           = $this->EitherEndian2Int(substr($thisfile_riff_CDDA_fmt_0['data'], 16, 4));
					$thisfile_riff_CDDA_fmt_0['unknown7']           = $this->EitherEndian2Int(substr($thisfile_riff_CDDA_fmt_0['data'], 20, 4));

					$thisfile_riff_CDDA_fmt_0['start_offset_seconds'] = (float) $thisfile_riff_CDDA_fmt_0['start_offset_frame'] / 75;
					$thisfile_riff_CDDA_fmt_0['playtime_seconds']     = (float) $thisfile_riff_CDDA_fmt_0['playtime_frames'] / 75;
					$info['comments']['track_number']         = $thisfile_riff_CDDA_fmt_0['track_num'];
					$info['playtime_seconds']                 = $thisfile_riff_CDDA_fmt_0['playtime_seconds'];

					// hardcoded data for CD-audio
					$thisfile_audio['lossless']        = true;
					$thisfile_audio['sample_rate']     = 44100;
					$thisfile_audio['channels']        = 2;
					$thisfile_audio['bits_per_sample'] = 16;
					$thisfile_audio['bitrate']         = $thisfile_audio['sample_rate'] * $thisfile_audio['channels'] * $thisfile_audio['bits_per_sample'];
					$thisfile_audio['bitrate_mode']    = 'cbr';
				}
				break;

			// http://en.wikipedia.org/wiki/AIFF
			case 'AIFF':
			case 'AIFC':
				$info['fileformat'] = 'aiff';
				$info['mime_type']  = 'audio/x-aiff';

				$thisfile_audio['bitrate_mode'] = 'cbr';
				$thisfile_audio_dataformat      = 'aiff';
				$thisfile_audio['lossless']     = true;

				if (isset($thisfile_riff[$RIFFsubtype]['SSND'][0]['offset'])) {
					$info['avdataoffset'] = $thisfile_riff[$RIFFsubtype]['SSND'][0]['offset'] + 8;
					$info['avdataend']    = $info['avdataoffset'] + $thisfile_riff[$RIFFsubtype]['SSND'][0]['size'];
					if ($info['avdataend'] > $info['filesize']) {
						if (($info['avdataend'] == ($info['filesize'] + 1)) && (($info['filesize'] % 2) == 1)) {
							// structures rounded to 2-byte boundary, but dumb encoders
							// forget to pad end of file to make this actually work
						} else {
							$this->warning('Probable truncated AIFF file: expecting '.$thisfile_riff[$RIFFsubtype]['SSND'][0]['size'].' bytes of audio data, only '.($info['filesize'] - $info['avdataoffset']).' bytes found');
						}
						$info['avdataend'] = $info['filesize'];
					}
				}

				if (isset($thisfile_riff[$RIFFsubtype]['COMM'][0]['data'])) {

					// shortcut
					$thisfile_riff_RIFFsubtype_COMM_0_data = &$thisfile_riff[$RIFFsubtype]['COMM'][0]['data'];

					$thisfile_riff_audio['channels']         =         getid3_lib::BigEndian2Int(substr($thisfile_riff_RIFFsubtype_COMM_0_data,  0,  2), true);
					$thisfile_riff_audio['total_samples']    =         getid3_lib::BigEndian2Int(substr($thisfile_riff_RIFFsubtype_COMM_0_data,  2,  4), false);
					$thisfile_riff_audio['bits_per_sample']  =         getid3_lib::BigEndian2Int(substr($thisfile_riff_RIFFsubtype_COMM_0_data,  6,  2), true);
					$thisfile_riff_audio['sample_rate']      = (int) getid3_lib::BigEndian2Float(substr($thisfile_riff_RIFFsubtype_COMM_0_data,  8, 10));

					if ($thisfile_riff[$RIFFsubtype]['COMM'][0]['size'] > 18) {
						$thisfile_riff_audio['codec_fourcc'] =                                   substr($thisfile_riff_RIFFsubtype_COMM_0_data, 18,  4);
						$CodecNameSize                       =         getid3_lib::BigEndian2Int(substr($thisfile_riff_RIFFsubtype_COMM_0_data, 22,  1), false);
						$thisfile_riff_audio['codec_name']   =                                   substr($thisfile_riff_RIFFsubtype_COMM_0_data, 23,  $CodecNameSize);
						switch ($thisfile_riff_audio['codec_name']) {
							case 'NONE':
								$thisfile_audio['codec']    = 'Pulse Code Modulation (PCM)';
								$thisfile_audio['lossless'] = true;
								break;

							case '':
								switch ($thisfile_riff_audio['codec_fourcc']) {
									// http://developer.apple.com/qa/snd/snd07.html
									case 'sowt':
										$thisfile_riff_audio['codec_name'] = 'Two\'s Compliment Little-Endian PCM';
										$thisfile_audio['lossless'] = true;
										break;

									case 'twos':
										$thisfile_riff_audio['codec_name'] = 'Two\'s Compliment Big-Endian PCM';
										$thisfile_audio['lossless'] = true;
										break;

									default:
										break;
								}
								break;

							default:
								$thisfile_audio['codec']    = $thisfile_riff_audio['codec_name'];
								$thisfile_audio['lossless'] = false;
								break;
						}
					}

					$thisfile_audio['channels']        = $thisfile_riff_audio['channels'];
					if ($thisfile_riff_audio['bits_per_sample'] > 0) {
						$thisfile_audio['bits_per_sample'] = $thisfile_riff_audio['bits_per_sample'];
					}
					$thisfile_audio['sample_rate']     = $thisfile_riff_audio['sample_rate'];
					if ($thisfile_audio['sample_rate'] == 0) {
						$this->error('Corrupted AIFF file: sample_rate == zero');
						return false;
					}
					$info['playtime_seconds'] = $thisfile_riff_audio['total_samples'] / $thisfile_audio['sample_rate'];
				}

				if (isset($thisfile_riff[$RIFFsubtype]['COMT'])) {
					$offset = 0;
					$CommentCount                                   = getid3_lib::BigEndian2Int(substr($thisfile_riff[$RIFFsubtype]['COMT'][0]['data'], $offset, 2), false);
					$offset += 2;
					for ($i = 0; $i < $CommentCount; $i++) {
						$info['comments_raw'][$i]['timestamp']      = getid3_lib::BigEndian2Int(substr($thisfile_riff[$RIFFsubtype]['COMT'][0]['data'], $offset, 4), false);
						$offset += 4;
						$info['comments_raw'][$i]['marker_id']      = getid3_lib::BigEndian2Int(substr($thisfile_riff[$RIFFsubtype]['COMT'][0]['data'], $offset, 2), true);
						$offset += 2;
						$CommentLength                              = getid3_lib::BigEndian2Int(substr($thisfile_riff[$RIFFsubtype]['COMT'][0]['data'], $offset, 2), false);
						$offset += 2;
						$info['comments_raw'][$i]['comment']        =                           substr($thisfile_riff[$RIFFsubtype]['COMT'][0]['data'], $offset, $CommentLength);
						$offset += $CommentLength;

						$info['comments_raw'][$i]['timestamp_unix'] = getid3_lib::DateMac2Unix($info['comments_raw'][$i]['timestamp']);
						$thisfile_riff['comments']['comment'][] = $info['comments_raw'][$i]['comment'];
					}
				}

				$CommentsChunkNames = array('NAME'=>'title', 'author'=>'artist', '(c) '=>'copyright', 'ANNO'=>'comment');
				foreach ($CommentsChunkNames as $key => $value) {
					if (isset($thisfile_riff[$RIFFsubtype][$key][0]['data'])) {
						$thisfile_riff['comments'][$value][] = $thisfile_riff[$RIFFsubtype][$key][0]['data'];
					}
				}
/*
				if (isset($thisfile_riff[$RIFFsubtype]['ID3 '])) {
					getid3_lib::IncludeDependency(GETID3_INCLUDEPATH.'module.tag.id3v2.php', __FILE__, true);
					$getid3_temp = new getID3();
					$getid3_temp->openfile($this->getid3->filename, null, $this->getid3->fp);
					$getid3_id3v2 = new getid3_id3v2($getid3_temp);
					$getid3_id3v2->StartingOffset = $thisfile_riff[$RIFFsubtype]['ID3 '][0]['offset'] + 8;
					if ($thisfile_riff[$RIFFsubtype]['ID3 '][0]['valid'] = $getid3_id3v2->Analyze()) {
						$info['id3v2'] = $getid3_temp->info['id3v2'];
					}
					unset($getid3_temp, $getid3_id3v2);
				}
*/
				break;

			// http://en.wikipedia.org/wiki/8SVX
			case '8SVX':
				$info['fileformat'] = '8svx';
				$info['mime_type']  = 'audio/8svx';

				$thisfile_audio['bitrate_mode']    = 'cbr';
				$thisfile_audio_dataformat         = '8svx';
				$thisfile_audio['bits_per_sample'] = 8;
				$thisfile_audio['channels']        = 1; // overridden below, if need be
				$ActualBitsPerSample               = 0;

				if (isset($thisfile_riff[$RIFFsubtype]['BODY'][0]['offset'])) {
					$info['avdataoffset'] = $thisfile_riff[$RIFFsubtype]['BODY'][0]['offset'] + 8;
					$info['avdataend']    = $info['avdataoffset'] + $thisfile_riff[$RIFFsubtype]['BODY'][0]['size'];
					if ($info['avdataend'] > $info['filesize']) {
						$this->warning('Probable truncated AIFF file: expecting '.$thisfile_riff[$RIFFsubtype]['BODY'][0]['size'].' bytes of audio data, only '.($info['filesize'] - $info['avdataoffset']).' bytes found');
					}
				}

				if (isset($thisfile_riff[$RIFFsubtype]['VHDR'][0]['offset'])) {
					// shortcut
					$thisfile_riff_RIFFsubtype_VHDR_0 = &$thisfile_riff[$RIFFsubtype]['VHDR'][0];

					$thisfile_riff_RIFFsubtype_VHDR_0['oneShotHiSamples']  =   getid3_lib::BigEndian2Int(substr($thisfile_riff_RIFFsubtype_VHDR_0['data'],  0, 4));
					$thisfile_riff_RIFFsubtype_VHDR_0['repeatHiSamples']   =   getid3_lib::BigEndian2Int(substr($thisfile_riff_RIFFsubtype_VHDR_0['data'],  4, 4));
					$thisfile_riff_RIFFsubtype_VHDR_0['samplesPerHiCycle'] =   getid3_lib::BigEndian2Int(substr($thisfile_riff_RIFFsubtype_VHDR_0['data'],  8, 4));
					$thisfile_riff_RIFFsubtype_VHDR_0['samplesPerSec']     =   getid3_lib::BigEndian2Int(substr($thisfile_riff_RIFFsubtype_VHDR_0['data'], 12, 2));
					$thisfile_riff_RIFFsubtype_VHDR_0['ctOctave']          =   getid3_lib::BigEndian2Int(substr($thisfile_riff_RIFFsubtype_VHDR_0['data'], 14, 1));
					$thisfile_riff_RIFFsubtype_VHDR_0['sCompression']      =   getid3_lib::BigEndian2Int(substr($thisfile_riff_RIFFsubtype_VHDR_0['data'], 15, 1));
					$thisfile_riff_RIFFsubtype_VHDR_0['Volume']            = getid3_lib::FixedPoint16_16(substr($thisfile_riff_RIFFsubtype_VHDR_0['data'], 16, 4));

					$thisfile_audio['sample_rate'] = $thisfile_riff_RIFFsubtype_VHDR_0['samplesPerSec'];

					switch ($thisfile_riff_RIFFsubtype_VHDR_0['sCompression']) {
						case 0:
							$thisfile_audio['codec']    = 'Pulse Code Modulation (PCM)';
							$thisfile_audio['lossless'] = true;
							$ActualBitsPerSample        = 8;
							break;

						case 1:
							$thisfile_audio['codec']    = 'Fibonacci-delta encoding';
							$thisfile_audio['lossless'] = false;
							$ActualBitsPerSample        = 4;
							break;

						default:
							$this->warning('Unexpected sCompression value in 8SVX.VHDR chunk - expecting 0 or 1, found "'.$thisfile_riff_RIFFsubtype_VHDR_0['sCompression'].'"');
							break;
					}
				}

				if (isset($thisfile_riff[$RIFFsubtype]['CHAN'][0]['data'])) {
					$ChannelsIndex = getid3_lib::BigEndian2Int(substr($thisfile_riff[$RIFFsubtype]['CHAN'][0]['data'], 0, 4));
					switch ($ChannelsIndex) {
						case 6: // Stereo
							$thisfile_audio['channels'] = 2;
							break;

						case 2: // Left channel only
						case 4: // Right channel only
							$thisfile_audio['channels'] = 1;
							break;

						default:
							$this->warning('Unexpected value in 8SVX.CHAN chunk - expecting 2 or 4 or 6, found "'.$ChannelsIndex.'"');
							break;
					}

				}

				$CommentsChunkNames = array('NAME'=>'title', 'author'=>'artist', '(c) '=>'copyright', 'ANNO'=>'comment');
				foreach ($CommentsChunkNames as $key => $value) {
					if (isset($thisfile_riff[$RIFFsubtype][$key][0]['data'])) {
						$thisfile_riff['comments'][$value][] = $thisfile_riff[$RIFFsubtype][$key][0]['data'];
					}
				}

				$thisfile_audio['bitrate'] = $thisfile_audio['sample_rate'] * $ActualBitsPerSample * $thisfile_audio['channels'];
				if (!empty($thisfile_audio['bitrate'])) {
					$info['playtime_seconds'] = ($info['avdat