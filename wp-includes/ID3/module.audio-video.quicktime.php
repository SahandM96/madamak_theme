<?php

/////////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <info@getid3.org>               //
//  available at https://github.com/JamesHeinrich/getID3       //
//            or https://www.getid3.org                        //
//            or http://getid3.sourceforge.net                 //
//  see readme.txt for more details                            //
/////////////////////////////////////////////////////////////////
//                                                             //
// module.audio-video.quicktime.php                            //
// module for analyzing Quicktime and MP3-in-MP4 files         //
// dependencies: module.audio.mp3.php                          //
// dependencies: module.tag.id3v2.php                          //
//                                                            ///
/////////////////////////////////////////////////////////////////

getid3_lib::IncludeDependency(GETID3_INCLUDEPATH.'module.audio.mp3.php', __FILE__, true);
getid3_lib::IncludeDependency(GETID3_INCLUDEPATH.'module.tag.id3v2.php', __FILE__, true); // needed for ISO 639-2 language code lookup

class getid3_quicktime extends getid3_handler
{

	public $ReturnAtomData        = true;
	public $ParseAllPossibleAtoms = false;

	/**
	 * @return bool
	 */
	public function Analyze() {
		$info = &$this->getid3->info;

		$info['fileformat'] = 'quicktime';
		$info['quicktime']['hinting']    = false;
		$info['quicktime']['controller'] = 'standard'; // may be overridden if 'ctyp' atom is present

		$this->fseek($info['avdataoffset']);

		$offset      = 0;
		$atomcounter = 0;
		$atom_data_read_buffer_size = $info['php_memory_limit'] ? round($info['php_memory_limit'] / 4) : $this->getid3->option_fread_buffer_size * 1024; // set read buffer to 25% of PHP memory limit (if one is specified), otherwise use option_fread_buffer_size [default: 32MB]
		while ($offset < $info['avdataend']) {
			if (!getid3_lib::intValueSupported($offset)) {
				$this->error('Unable to parse atom at offset '.$offset.' because beyond '.round(PHP_INT_MAX / 1073741824).'GB limit of PHP filesystem functions');
				break;
			}
			$this->fseek($offset);
			$AtomHeader = $this->fread(8);

			$atomsize = getid3_lib::BigEndian2Int(substr($AtomHeader, 0, 4));
			$atomname = substr($AtomHeader, 4, 4);

			// 64-bit MOV patch by jlegateØktnc*com
			if ($atomsize == 1) {
				$atomsize = getid3_lib::BigEndian2Int($this->fread(8));
			}

			$info['quicktime'][$atomname]['name']   = $atomname;
			$info['quicktime'][$atomname]['size']   = $atomsize;
			$info['quicktime'][$atomname]['offset'] = $offset;

			if (($offset + $atomsize) > $info['avdataend']) {
				$this->error('Atom at offset '.$offset.' claims to go beyond end-of-file (length: '.$atomsize.' bytes)');
				return false;
			}

			if ($atomsize == 0) {
				// Furthermore, for historical reasons the list of atoms is optionally
				// terminated by a 32-bit integer set to 0. If you are writing a program
				// to read user data atoms, you should allow for the terminating 0.
				break;
			}
			$atomHierarchy = array();
			$info['quicktime'][$atomname] = $this->QuicktimeParseAtom($atomname, $atomsize, $this->fread(min($atomsize, $atom_data_read_buffer_size)), $offset, $atomHierarchy, $this->ParseAllPossibleAtoms);

			$offset += $atomsize;
			$atomcounter++;
		}

		if (!empty($info['avdataend_tmp'])) {
			// this value is assigned to a temp value and then erased because
			// otherwise any atoms beyond the 'mdat' atom would not get parsed
			$info['avdataend'] = $info['avdataend_tmp'];
			unset($info['avdataend_tmp']);
		}

		if (!empty($info['quicktime']['comments']['chapters']) && is_array($info['quicktime']['comments']['chapters']) && (count($info['quicktime']['comments']['chapters']) > 0)) {
			$durations = $this->quicktime_time_to_sample_table($info);
			for ($i = 0; $i < count($info['quicktime']['comments']['chapters']); $i++) {
				$bookmark = array();
				$bookmark['title'] = $info['quicktime']['comments']['chapters'][$i];
				if (isset($durations[$i])) {
					$bookmark['duration_sample'] = $durations[$i]['sample_duration'];
					if ($i > 0) {
						$bookmark['start_sample'] = $info['quicktime']['bookmarks'][($i - 1)]['start_sample'] + $info['quicktime']['bookmarks'][($i - 1)]['duration_sample'];
					} else {
						$bookmark['start_sample'] = 0;
					}
					if ($time_scale = $this->quicktime_bookmark_time_scale($info)) {
						$bookmark['duration_seconds'] = $bookmark['duration_sample'] / $time_scale;
						$bookmark['start_seconds']    = $bookmark['start_sample']    / $time_scale;
					}
				}
				$info['quicktime']['bookmarks'][] = $bookmark;
			}
		}

		if (isset($info['quicktime']['temp_meta_key_names'])) {
			unset($info['quicktime']['temp_meta_key_names']);
		}

		if (!empty($info['quicktime']['comments']['location.ISO6709'])) {
			// https://en.wikipedia.org/wiki/ISO_6709
			foreach ($info['quicktime']['comments']['location.ISO6709'] as $ISO6709string) {
				$latitude  = false;
				$longitude = false;
				$altitude  = false;
				if (preg_match('#^([\\+\\-])([0-9]{2}|[0-9]{4}|[0-9]{6})(\\.[0-9]+)?([\\+\\-])([0-9]{3}|[0-9]{5}|[0-9]{7})(\\.[0-9]+)?(([\\+\\-])([0-9]{3}|[0-9]{5}|[0-9]{7})(\\.[0-9]+)?)?/$#', $ISO6709string, $matches)) {
					@list($dummy, $lat_sign, $lat_deg, $lat_deg_dec, $lon_sign, $lon_deg, $lon_deg_dec, $dummy, $alt_sign, $alt_deg, $alt_deg_dec) = $matches;

					if (strlen($lat_deg) == 2) {        // [+-]DD.D
						$latitude = floatval(ltrim($lat_deg, '0').$lat_deg_dec);
					} elseif (strlen($lat_deg) == 4) {  // [+-]DDMM.M
						$latitude = floatval(ltrim(substr($lat_deg, 0, 2), '0')) + floatval(ltrim(substr($lat_deg, 2, 2), '0').$lat_deg_dec / 60);
					} elseif (strlen($lat_deg) == 6) {  // [+-]DDMMSS.S
						$latitude = floatval(ltrim(substr($lat_deg, 0, 2), '0')) + floatval(ltrim(substr($lat_deg, 2, 2), '0') / 60) + floatval(ltrim(substr($lat_deg, 4, 2), '0').$lat_deg_dec / 3600);
					}

					if (strlen($lon_deg) == 3) {        // [+-]DDD.D
						$longitude = floatval(ltrim($lon_deg, '0').$lon_deg_dec);
					} elseif (strlen($lon_deg) == 5) {  // [+-]DDDMM.M
						$longitude = floatval(ltrim(substr($lon_deg, 0, 2), '0')) + floatval(ltrim(substr($lon_deg, 2, 2), '0').$lon_deg_dec / 60);
					} elseif (strlen($lon_deg) == 7) {  // [+-]DDDMMSS.S
						$longitude = floatval(ltrim(substr($lon_deg, 0, 2), '0')) + floatval(ltrim(substr($lon_deg, 2, 2), '0') / 60) + floatval(ltrim(substr($lon_deg, 4, 2), '0').$lon_deg_dec / 3600);
					}

					if (strlen($alt_deg) == 3) {        // [+-]DDD.D
						$altitude = floatval(ltrim($alt_deg, '0').$alt_deg_dec);
					} elseif (strlen($alt_deg) == 5) {  // [+-]DDDMM.M
						$altitude = floatval(ltrim(substr($alt_deg, 0, 2), '0')) + floatval(ltrim(substr($alt_deg, 2, 2), '0').$alt_deg_dec / 60);
					} elseif (strlen($alt_deg) == 7) {  // [+-]DDDMMSS.S
						$altitude = floatval(ltrim(substr($alt_deg, 0, 2), '0')) + floatval(ltrim(substr($alt_deg, 2, 2), '0') / 60) + floatval(ltrim(substr($alt_deg, 4, 2), '0').$alt_deg_dec / 3600);
					}

					if ($latitude !== false) {
						$info['quicktime']['comments']['gps_latitude'][]  = (($lat_sign == '-') ? -1 : 1) * floatval($latitude);
					}
					if ($longitude !== false) {
						$info['quicktime']['comments']['gps_longitude'][] = (($lon_sign == '-') ? -1 : 1) * floatval($longitude);
					}
					if ($altitude !== false) {
						$info['quicktime']['comments']['gps_altitude'][]  = (($alt_sign == '-') ? -1 : 1) * floatval($altitude);
					}
				}
				if ($latitude === false) {
					$this->warning('location.ISO6709 string not parsed correctly: "'.$ISO6709string.'", please submit as a bug');
				}
				break;
			}
		}

		if (!isset($info['bitrate']) && isset($info['playtime_seconds'])) {
			$info['bitrate'] = (($info['avdataend'] - $info['avdataoffset']) * 8) / $info['playtime_seconds'];
		}
		if (isset($info['bitrate']) && !isset($info['audio']['bitrate']) && !isset($info['quicktime']['video'])) {
			$info['audio']['bitrate'] = $info['bitrate'];
		}
		if (!empty($info['bitrate']) && !empty($info['audio']['bitrate']) && empty($info['video']['bitrate']) && !empty($info['video']['frame_rate']) && !empty($info['video']['resolution_x']) && ($info['bitrate'] > $info['audio']['bitrate'])) {
			$info['video']['bitrate'] = $info['bitrate'] - $info['audio']['bitrate'];
		}
		if (!empty($info['playtime_seconds']) && !isset($info['video']['frame_rate']) && !empty($info['quicktime']['stts_framecount'])) {
			foreach ($info['quicktime']['stts_framecount'] as $key => $samples_count) {
				$samples_per_second = $samples_count / $info['playtime_seconds'];
				if ($samples_per_second > 240) {
					// has to be audio samples
				} else {
					$info['video']['frame_rate'] = $samples_per_second;
					break;
				}
			}
		}
		if ($info['audio']['dataformat'] == 'mp4') {
			$info['fileformat'] = 'mp4';
			if (empty($info['video']['resolution_x'])) {
				$info['mime_type']  = 'audio/mp4';
				unset($info['video']['dataformat']);
			} else {
				$info['mime_type']  = 'video/mp4';
			}
		}

		if (!$this->ReturnAtomData) {
			unset($info['quicktime']['moov']);
		}

		if (empty($info['audio']['dataformat']) && !empty($info['quicktime']['audio'])) {
			$info['audio']['dataformat'] = 'quicktime';
		}
		if (empty($info['video']['dataformat']) && !empty($info['quicktime']['video'])) {
			$info['video']['dataformat'] = 'quicktime';
		}
		if (isset($info['video']) && ($info['mime_type'] == 'audio/mp4') && empty($info['video']['resolution_x']) && empty($info['video']['resolution_y']))  {
			unset($info['video']);
		}

		return true;
	}

	/**
	 * @param string $atomname
	 * @param int    $atomsize
	 * @param string $atom_data
	 * @param int    $baseoffset
	 * @param array  $atomHierarchy
	 * @param bool   $ParseAllPossibleAtoms
	 *
	 * @return array|false
	 */
	public function QuicktimeParseAtom($atomname, $atomsize, $atom_data, $baseoffset, &$atomHierarchy, $ParseAllPossibleAtoms) {
		// http://developer.apple.com/techpubs/quicktime/qtdevdocs/APIREF/INDEX/atomalphaindex.htm
		// https://code.google.com/p/mp4v2/wiki/iTunesMetadata

		$info = &$this->getid3->info;

		$atom_parent = end($atomHierarchy); // not array_pop($atomHierarchy); see https://www.getid3.org/phpBB3/viewtopic.php?t=1717
		array_push($atomHierarchy, $atomname);
		$atom_structure['hierarchy'] = implode(' ', $atomHierarchy);
		$atom_structure['name']      = $atomname;
		$atom_structure['size']      = $atomsize;
		$atom_structure['offset']    = $baseoffset;
		if (substr($atomname, 0, 3) == "\x00\x00\x00") {
			// https://github.com/JamesHeinrich/getID3/issues/139
			$atomname = getid3_lib::BigEndian2Int($atomname);
			$atom_structure['name'] = $atomname;
			$atom_structure['subatoms'] = $this->QuicktimeParseContainerAtom($atom_data, $baseoffset + 8, $atomHierarchy, $ParseAllPossibleAtoms);
		} else {
			switch ($atomname) {
				case 'moov': // MOVie container atom
				case 'trak': // TRAcK container atom
				case 'clip': // CLIPping container atom
				case 'matt': // track MATTe container atom
				case 'edts': // EDiTS container atom
				case 'tref': // Track REFerence container atom
				case 'mdia': // MeDIA container atom
				case 'minf': // Media INFormation container atom
				case 'dinf': // Data INFormation container atom
				case 'udta': // User DaTA container atom
				case 'cmov': // Compressed MOVie container atom
				case 'rmra': // Reference Movie Record Atom
				case 'rmda': // Reference Movie Descriptor Atom
				case 'gmhd': // Generic Media info HeaDer atom (seen on QTVR)
					$atom_structure['subatoms'] = $this->QuicktimeParseContainerAtom($atom_data, $baseoffset + 8, $atomHierarchy, $ParseAllPossibleAtoms);
					break;

				case 'ilst': // Item LiST container atom
					if ($atom_structure['subatoms'] = $this->QuicktimeParseContainerAtom($atom_data, $baseoffset + 8, $atomHierarchy, $ParseAllPossibleAtoms)) {
						// some "ilst" atoms contain data atoms that have a numeric name, and the data is far more accessible if the returned array is compacted
						$allnumericnames = true;
						foreach ($atom_structure['subatoms'] as $subatomarray) {
							if (!is_integer($subatomarray['name']) || (count($subatomarray['subatoms']) != 1)) {
								$allnumericnames = false;
								break;
							}
						}
						if ($allnumericnames) {
							$newData = array();
							foreach ($atom_structure['subatoms'] as $subatomarray) {
								foreach ($subatomarray['subatoms'] as $newData_subatomarray) {
									unset($newData_subatomarray['hierarchy'], $newData_subatomarray['name']);
									$newData[$subatomarray['name']] = $newData_subatomarray;
									break;
								}
							}
							$atom_structure['data'] = $newData;
							unset($atom_structure['subatoms']);
						}
					}
					break;

				case 'stbl': // Sample TaBLe container atom
					$atom_structure['subatoms'] = $this->QuicktimeParseContainerAtom($atom_data, $baseoffset + 8, $atomHierarchy, $ParseAllPossibleAtoms);
					$isVideo = false;
					$framerate  = 0;
					$framecount = 0;
					foreach ($atom_structure['subatoms'] as $key => $value_array) {
						if (isset($value_array['sample_description_table'])) {
							foreach ($value_array['sample_description_table'] as $key2 => $value_array2) {
								if (isset($value_array2['data_format'])) {
									switch ($value_array2['data_format']) {
										case 'avc1':
										case 'mp4v':
											// video data
											$isVideo = true;
											break;
										case 'mp4a':
											// audio data
											break;
									}
								}
							}
						} elseif (isset($value_array['time_to_sample_table'])) {
							foreach ($value_array['time_to_sample_table'] as $key2 => $value_array2) {
								if (isset($value_array2['sample_count']) && isset($value_array2['sample_duration']) && ($value_array2['sample_duration'] > 0)) {
									$framerate  = round($info['quicktime']['time_scale'] / $value_array2['sample_duration'], 3);
									$framecount = $value_array2['sample_count'];
								}
							}
						}
					}
					if ($isVideo && $framerate) {
						$info['quicktime']['video']['frame_rate'] = $framerate;
						$info['video']['frame_rate'] = $info['quicktime']['video']['frame_rate'];
					}
					if ($isVideo && $framecount) {
						$info['quicktime']['video']['frame_count'] = $framecount;
					}
					break;


				case "\xA9".'alb': // ALBum
				case "\xA9".'ART': //
				case "\xA9".'art': // ARTist
				case "\xA9".'aut': //
				case "\xA9".'cmt': // CoMmenT
				case "\xA9".'com': // COMposer
				case "\xA9".'cpy': //
				case "\xA9".'day': // content created year
				case "\xA9".'dir': //
				case "\xA9".'ed1': //
				case "\xA9".'ed2': //
				case "\xA9".'ed3': //
				case "\xA9".'ed4': //
				case "\xA9".'ed5': //
				case "\xA9".'ed6': //
				case "\xA9".'ed7': //
				case "\xA9".'ed8': //
				case "\xA9".'ed9': //
				case "\xA9".'enc': //
				case "\xA9".'fmt': //
				case "\xA9".'gen': // GENre
				case "\xA9".'grp': // GRouPing
				case "\xA9".'hst': //
				case "\xA9".'inf': //
				case "\xA9".'lyr': // LYRics
				case "\xA9".'mak': //
				case "\xA9".'mod': //
				case "\xA9".'nam': // full NAMe
				case "\xA9".'ope': //
				case "\xA9".'PRD': //
				case "\xA9".'prf': //
				case "\xA9".'req': //
				case "\xA9".'src': //
				case "\xA9".'swr': //
				case "\xA9".'too': // encoder
				case "\xA9".'trk': // TRacK
				case "\xA9".'url': //
				case "\xA9".'wrn': //
				case "\xA9".'wrt': // WRiTer
				case '----': // itunes specific
				case 'aART': // Album ARTist
				case 'akID': // iTunes store account type
				case 'apID': // Purchase Account
				case 'atID': //
				case 'catg': // CaTeGory
				case 'cmID': //
				case 'cnID': //
				case 'covr': // COVeR artwork
				case 'cpil': // ComPILation
				case 'cprt': // CoPyRighT
				case 'desc': // DESCription
				case 'disk': // DISK number
				case 'egid': // Episode Global ID
				case 'geID': //
				case 'gnre': // GeNRE
				case 'hdvd': // HD ViDeo
				case 'keyw': // KEYWord
				case 'ldes': // Long DEScription
				case 'pcst': // PodCaST
				case 'pgap': // GAPless Playback
				case 'plID': //
				case 'purd': // PURchase Date
				case 'purl': // Podcast URL
				case 'rati': //
				case 'rndu': //
				case 'rpdu': //
				case 'rtng': // RaTiNG
				case 'sfID': // iTunes store country
				case 'soaa': // SOrt Album Artist
				case 'soal': // SOrt ALbum
				case 'soar': // SOrt ARtist
				case 'soco': // SOrt COmposer
				case 'sonm': // SOrt NaMe
				case 'sosn': // SOrt Show Name
				case 'stik': //
				case 'tmpo': // TeMPO (BPM)
				case 'trkn': // TRacK Number
				case 'tven': // tvEpisodeID
				case 'tves': // TV EpiSode
				case 'tvnn': // TV Network Name
				case 'tvsh': // TV SHow Name
				case 'tvsn': // TV SeasoN
					if ($atom_parent == 'udta') {
						// User data atom handler
						$atom_structure['data_length'] = getid3_lib::BigEndian2Int(substr($atom_data, 0, 2));
						$atom_structure['language_id'] = getid3_lib::BigEndian2Int(substr($atom_data, 2, 2));
						$atom_structure['data']        =                           substr($atom_data, 4);

						$atom_structure['language']    = $this->QuicktimeLanguageLookup($atom_structure['language_id']);
						if (empty($info['comments']['language']) || (!in_array($atom_structure['language'], $info['comments']['language']))) {
							$info['comments']['language'][] = $atom_structure['language'];
						}
					} else {
						// Apple item list box atom handler
						$atomoffset = 0;
						if (substr($atom_data, 2, 2) == "\x10\xB5") {
							// not sure what it means, but observed on iPhone4 data.
							// Each $atom_data has 2 bytes of datasize, plus 0x10B5, then data
							while ($atomoffset < strlen($atom_data)) {
								$boxsmallsize = getid3_lib::BigEndian2Int(substr($atom_data, $atomoffset,     2));
								$boxsmalltype =                           substr($atom_data, $atomoffset + 2, 2);
								$boxsmalldata =                           substr($atom_data, $atomoffset + 4, $boxsmallsize);
								if ($boxsmallsize <= 1) {
									$this->warning('Invalid QuickTime atom smallbox size "'.$boxsmallsize.'" in atom "'.preg_replace('#[^a-zA-Z0-9 _\\-]#', '?', $atomname).'" at offset: '.($atom_structure['offset'] + $atomoffset));
									$atom_structure['data'] = null;
									$atomoffset = strlen($atom_data);
									break;
								}
								switch ($boxsmalltype) {
									case "\x10\xB5":
										$atom_structure['data'] = $boxsmalldata;
										break;
									default:
										$this->warning('Unknown QuickTime smallbox type: "'.preg_replace('#[^a-zA-Z0-9 _\\-]#', '?', $boxsmalltype).'" ('.trim(getid3_lib::PrintHexBytes($boxsmalltype)).') at offset '.$baseoffset);
										$atom_structure['data'] = $atom_data;
										break;
								}
								$atomoffset += (4 + $boxsmallsize);
							}
						} else {
							while ($atomoffset < strlen($atom_data)) {
								$boxsize = getid3_lib::BigEndian2Int(substr($atom_data, $atomoffset, 4));
								$boxtype =                           substr($atom_data, $atomoffset + 4, 4);
								$boxdata =                           substr($atom_data, $atomoffset + 8, $boxsize - 8);
								if ($boxsize <= 1) {
									$this->warning('Invalid QuickTime atom box size "'.$boxsize.'" in atom "'.preg_replace('#[^a-zA-Z0-9 _\\-]#', '?', $atomname).'" at offset: '.($atom_structure['offset'] + $atomoffset));
									$atom_structure['data'] = null;
									$atomoffset = strlen($atom_data);
									break;
								}
								$atomoffset += $boxsize;

								switch ($boxtype) {
									case 'mean':
									case 'name':
										$atom_structure[$boxtype] = substr($boxdata, 4);
										break;

									case 'data':
										$atom_structure['version']   = getid3_lib::BigEndian2Int(substr($boxdata,  0, 1));
										$atom_structure['flags_raw'] = getid3_lib::BigEndian2Int(substr($boxdata,  1, 3));
										switch ($atom_structure['flags_raw']) {
											case  0: // data flag
											case 21: // tmpo/cpil flag
												switch ($atomname) {
													case 'cpil':
													case 'hdvd':
													case 'pcst':
													case 'pgap':
														// 8-bit integer (boolean)
														$atom_structure['data'] = getid3_lib::BigEndian2Int(substr($boxdata, 8, 1));
														break;

													case 'tmpo':
														// 16-bit integer
														$atom_structure['data'] = getid3_lib::BigEndian2Int(substr($boxdata, 8, 2));
														break;

													case 'disk':
													case 'trkn':
														// binary
														$num       = getid3_lib::BigEndian2Int(substr($boxdata, 10, 2));
														$num_total = getid3_lib::BigEndian2Int(substr($boxdata, 12, 2));
														$atom_structure['data']  = empty($num) ? '' : $num;
														$atom_structure['data'] .= empty($num_total) ? '' : '/'.$num_total;
														break;

													case 'gnre':
														// enum
														$GenreID = getid3_lib::BigEndian2Int(substr($boxdata, 8, 4));
														$atom_structure['data']    = getid3_id3v1::LookupGenreName($GenreID - 1);
														break;

													case 'rtng':
														// 8-bit integer
														$atom_structure[$atomname] = getid3_lib::BigEndian2Int(substr($boxdata, 8, 1));
														$atom_structure['data']    = $this->QuicktimeContentRatingLookup($atom_structure[$atomname]);
														break;

													case 'stik':
														// 8-bit integer (enum)
														$atom_structure[$atomname] = getid3_lib::BigEndian2Int(substr($boxdata, 8, 1));
														$atom_structure['data']    = $this->QuicktimeSTIKLookup($atom_structure[$atomname]);
														break;

													case 'sfID':
														// 32-bit integer
														$atom_structure[$atomname] = getid3_lib::BigEndian2Int(substr($boxdata, 8, 4));
														$atom_structure['data']    = $this->QuicktimeStoreFrontCodeLookup($atom_structure[$atomname]);
														break;

													case 'egid':
													case 'purl':
														$atom_structure['data'] = substr($boxdata, 8);
														break;

													case 'plID':
														// 64-bit integer
														$atom_structure['data'] = getid3_lib::BigEndian2Int(substr($boxdata, 8, 8));
														break;

													case 'covr':
														$atom_structure['data'] = substr($boxdata, 8);
														// not a foolproof check, but better than nothing
														if (preg_match('#^\\xFF\\xD8\\xFF#', $atom_structure['data'])) {
															$atom_structure['image_mime'] = 'image/jpeg';
														} elseif (preg_match('#^\\x89\\x50\\x4E\\x47\\x0D\\x0A\\x1A\\x0A#', $atom_structure['data'])) {
															$atom_structure['image_mime'] = 'image/png';
														} elseif (preg_match('#^GIF#', $atom_structure['data'])) {
															$atom_structure['image_mime'] = 'image/gif';
														}
														break;

													case 'atID':
													case 'cnID':
													case 'geID':
													case 'tves':
													case 'tvsn':
													default:
														// 32-bit integer
														$atom_structure['data'] = getid3_lib::BigEndian2Int(substr($boxdata, 8, 4));
												}
												break;

											case  1: // text flag
											case 13: // image flag
											default:
												$atom_structure['data'] = substr($boxdata, 8);
												if ($atomname == 'covr') {
													// not a foolproof check, but better than nothing
													if (preg_match('#^\\xFF\\xD8\\xFF#', $atom_structure['data'])) {
														$atom_structure['image_mime'] = 'image/jpeg';
													} elseif (preg_match('#^\\x89\\x50\\x4E\\x47\\x0D\\x0A\\x1A\\x0A#', $atom_structure['data'])) {
														$atom_structure['image_mime'] = 'image/png';
													} elseif (preg_match('#^GIF#', $atom_structure['data'])) {
														$atom_structure['image_mime'] = 'image/gif';
													}
												}
												break;

										}
										break;

									default:
										$this->warning('Unknown QuickTime box type: "'.preg_replace('#[^a-zA-Z0-9 _\\-]#', '?', $boxtype).'" ('.trim(getid3_lib::PrintHexBytes($boxtype)).') at offset '.$baseoffset);
										$atom_structure['data'] = $atom_data;

								}
							}
						}
					}
					$this->CopyToAppropriateCommentsSection($atomname, $atom_structure['data'], $atom_structure['name']);
					break;


				case 'play': // auto-PLAY atom
					$atom_structure['autoplay'] = (bool) getid3_lib::BigEndian2Int(substr($atom_data,  0, 1));

					$info['quicktime']['autoplay'] = $atom_structure['autoplay'];
					break;


				case 'WLOC': // Window LOCation atom
					$atom_structure['location_x']  = getid3_lib::BigEndian2Int(substr($atom_data,  0, 2));
					$atom_structure['location_y']  = getid3_lib::BigEndian2Int(substr($atom_data,  2, 2));
					break;


				case 'LOOP': // LOOPing atom
				case 'SelO': // play SELection Only atom
				case 'AllF': // play ALL Frames atom
					$atom_structure['data'] = getid3_lib::BigEndian2Int($atom_data);
					break;


				case 'name': //
				case 'MCPS': // Media Cleaner PRo
				case '@PRM': // adobe PReMiere version
				case '@PRQ': // adobe PRemiere Quicktime version
					$atom_structure['data'] = $atom_data;
					break;


				case 'cmvd': // Compressed MooV Data atom
					// Code by ubergeekØubergeek*tv based on information from
					// http://developer.apple.com/quicktime/icefloe/dispatch012.html
					$atom_structure['unCompressedSize'] = getid3_lib::BigEndian2Int(substr($atom_data, 0, 4));

					$CompressedFileData = substr($atom_data, 4);
					if ($UncompressedHeader = @gzuncompress($CompressedFileData)) {
						$atom_structure['subatoms'] = $this->QuicktimeParseContainerAtom($UncompressedHeader, 0, $atomHierarchy, $ParseAllPossibleAtoms);
					} else {
						$this->warning('Error decompressing compressed MOV atom at offset '.$atom_structure['offset']);
					}
					break;


				case 'dcom': // Data COMpression atom
					$atom_structure['compression_id']   = $atom_data;
					$atom_structure['compression_text'] = $this->QuicktimeDCOMLookup($atom_data);
					break;


				case 'rdrf': // Reference movie Data ReFerence atom
					$atom_structure['version']                = getid3_lib::BigEndian2Int(substr($atom_data,  0, 1));
					$atom_structure['flags_raw']              = getid3_lib::BigEndian2Int(substr($atom_data,  1, 3));
					$atom_structure['flags']['internal_data'] = (bool) ($atom_structure['flags_raw'] & 0x000001);

					$atom_structure['reference_type_name']    =                           substr($atom_data,  4, 4);
					$atom_structure['reference_length']       = getid3_lib::BigEndian2Int(substr($atom_data,  8, 4));
					switch ($atom_structure['reference_type_name']) {
						case 'url ':
							$atom_structure['url']            =       $this->NoNullString(substr($atom_data, 12));
							break;

						case 'alis':
							$atom_structure['file_alias']     =                           substr($atom_data, 12);
							break;

						case 'rsrc':
							$atom_structure['resource_alias'] =                           substr($atom_data, 12);
							break;

						default:
							$atom_structure['data']           =                           substr($atom_data, 12);
							break;
					}
					break;


				case 'rmqu': // Reference Movie QUality atom
					$atom_structure['movie_quality'] = getid3_lib::BigEndian2Int($atom_data);
					break;


				case 'rmcs': // Reference Movie Cpu Speed atom
					$atom_structure['version']          = getid3_lib::BigEndian2Int(substr($atom_data,  0, 1));
					$atom_structure['flags_raw']        = getid3_lib::BigEndian2Int(substr($atom_data,  1, 3)); // hardcoded: 0x0000
					$atom_structure['cpu_speed_rating'] = getid3_lib::BigEndian2Int(substr($atom_data,  4, 2));
					break;


				case 'rmvc': // Reference Movie Version Check atom
					$atom_structure['version']            = getid3_lib::BigEndian2Int(substr($atom_data,  0, 1));
					$atom_structure['flags_raw']          = getid3_lib::BigEndian2Int(substr($atom_data,  1, 3)); // hardcoded: 0x0000
					$atom_structure['gestalt_selector']   =                           substr($atom_data,  4, 4);
					$atom_structure['gestalt_value_mask'] = getid3_lib::BigEndian2Int(substr($atom_data,  8, 4));
					$atom_structure['gestalt_value']      = getid3_lib::BigEndian2Int(substr($atom_data, 12, 4));
					$atom_structure['gestalt_check_type'] = getid3_lib::BigEndian2Int(substr($atom_data, 14, 2));
					break;


				case 'rmcd': // Reference Movie Component check atom
					$atom_structure['version']                = getid3_lib::BigEndian2Int(substr($atom_data,  0, 1));
					$atom_structure['flags_raw']              = getid3_lib::BigEndian2Int(substr($atom_data,  1, 3)); // hardcoded: 0x0000
					$atom_structure['component_type']         =                           substr($atom_data,  4, 4);
					$atom_structure['component_subtype']      =                           substr($atom_data,  8, 4);
					$atom_structure['component_manufacturer'] =                           substr($atom_data, 12, 4);
					$atom_structure['component_flags_raw']    = getid3_lib::BigEndian2Int(substr($atom_data, 16, 4));
					$atom_structure['component_flags_mask']   = getid3_lib::BigEndian2Int(substr($atom_data, 20, 4));
					$atom_structure['component_min_version']  = getid3_lib::BigEndian2Int(substr($atom_data, 24, 4));
					break;


				case 'rmdr': // Reference Movie Data Rate atom
					$atom_structure['version']       = getid3_lib::BigEndian2Int(substr($atom_data,  0, 1));
					$atom_structure['flags_raw']     = getid3_lib::BigEndian2Int(substr($atom_data,  1, 3)); // hardcoded: 0x0000
					$atom_structure['data_rate']     = getid3_lib::BigEndian2Int(substr($atom_data,  4, 4));

					$atom_structure['data_rate_bps'] = $atom_structure['data_rate'] * 10;
					break;


				case 'rmla': // Reference Movie Language Atom
					$atom_structure['version']     = getid3_lib::BigEndian2Int(substr($atom_data,  0, 1));
					$atom_structure['flags_raw']   = getid3_lib::BigEndian2Int(substr($atom_data,  1, 3)); // hardcoded: 0x0000
					$atom_structure['language_id'] = getid3_lib::BigEndian2Int(substr($atom_data,  4, 2));

					$atom_structure['language']    = $this->QuicktimeLanguageLookup($atom_structure['language_id']);
					if (empty($info['comments']['language']) || (!in_array($atom_structure['language'], $info['comments']['language']))) {
						$info['comments']['language'][] = $atom_structure['language'];
					}
					break;


				case 'ptv ': // Print To Video - defines a movie's full screen mode
					// http://developer.apple.com/documentation/QuickTime/APIREF/SOURCESIV/at_ptv-_pg.htm
					$atom_structure['display_size_raw']  = getid3_lib::BigEndian2Int(substr($atom_data, 0, 2));
					$atom_structure['reserved_1']        = getid3_lib::BigEndian2Int(substr($atom_data, 2, 2)); // hardcoded: 0x0000
					$atom_structure['reserved_2']        = getid3_lib::BigEndian2Int(substr($atom_data, 4, 2)); // hardcoded: 0x0000
					$atom_structure['slide_show_flag']   = getid3_lib::BigEndian2Int(substr($atom_data, 6, 1));
					$atom_structure['play_on_open_flag'] = getid3_lib::BigEndian2Int(substr($atom_data, 7, 1));

					$atom_structure['flags']['play_on_open'] = (bool) $atom_structure['play_on_open_flag'];
					$atom_structure['flags']['slide_show']   = (bool) $atom_structure['slide_show_flag'];

					$ptv_lookup[0] = 'normal';
					$ptv_lookup[1] = 'double';
					$ptv_lookup[2] = 'half';
					$ptv_lookup[3] = 'full';
					$ptv_lookup[4] = 'current';
					if (isset($ptv_lookup[$atom_structure['display_size_raw']])) {
						$atom_structure['display_size'] = $ptv_lookup[$atom_structure['display_size_raw']];
					} else {
						$this->warning('unknown "ptv " display constant ('.$atom_structure['display_size_raw'].')');
					}
					break;


				case 'stsd': // Sample Table Sample Description atom
					$atom_structure['version']        = getid3_lib::BigEndian2Int(substr($atom_data,  0, 1));
					$atom_structure['flags_raw']      = getid3_lib::BigEndian2Int(substr($atom_data,  1, 3)); // hardcoded: 0x0000
					$atom_structure['number_entries'] = getid3_lib::BigEndian2Int(substr($atom_data,  4, 4));

					// see: https://github.com/JamesHeinrich/getID3/issues/111
					// Some corrupt files have been known to have high bits set in the number_entries field
					// This field shouldn't really need to be 32-bits, values stores are likely in the range 1-100000
					// Workaround: mask off the upper byte and throw a warning if it's nonzero
					if ($atom_structure['number_entries'] > 0x000FFFFF) {
						if ($atom_structure['number_entries'] > 0x00FFFFFF) {
							$this->warning('"stsd" atom contains improbably large number_entries (0x'.getid3_lib::PrintHexBytes(substr($atom_data, 4, 4), true, false).' = '.$atom_structure['number_entries'].'), probably in error. Ignoring upper byte and interpreting this as 0x'.getid3_lib::PrintHexBytes(substr($atom_data, 5, 3), true, false).' = '.($atom_structure['number_entries'] & 0x00FFFFFF));
							$atom_structure['number_entries'] = ($atom_structure['number_entries'] & 0x00FFFFFF);
						} else {
							$this->warning('"stsd" atom contains improbably large number_entries (0x'.getid3_lib::PrintHexBytes(substr($atom_data, 4, 4), true, false).' = '.$atom_structure['number_entries'].'), probably in error. Please report this to info@getid3.org referencing bug report #111');
						}
					}

					$stsdEntriesDataOffset = 8;
					for ($i = 0; $i < $atom_structure['number_entries']; $i++) {
						$atom_structure['sample_description_table'][$i]['size']             = getid3_lib::BigEndian2Int(substr($atom_data, $stsdEntriesDataOffset, 4));
						$stsdEntriesDataOffset += 4;
						$atom_structure['sample_description_table'][$i]['data_format']      =                           substr($atom_data, $stsdEntriesDataOffset, 4);
						$stsdEntriesDataOffset += 4;
						$atom_structure['sample_description_table'][$i]['reserved']         = getid3_lib::BigEndian2Int(substr($atom_data, $stsdEntriesDataOffset, 6));
						$stsdEntriesDataOffset += 6;
						$atom_structure['sample_description_table'][$i]['reference_index']  = getid3_lib::BigEndian2Int(substr($atom_data, $stsdEntriesDataOffset, 2));
						$stsdEntriesDataOffset += 2;
						$atom_structure['sample_description_table'][$i]['data']             =                           substr($atom_data, $stsdEntriesDataOffset, ($atom_structure['sample_description_table'][$i]['size'] - 4 - 4 - 6 - 2));
						$stsdEntriesDataOffset += ($atom_structure['sample_description_table'][$i]['size'] - 4 - 4 - 6 - 2);

						$atom_structure['sample_description_table'][$i]['encoder_version']  = getid3_lib::BigEndian2Int(substr($atom_structure['sample_description_table'][$i]['data'],  0, 2));
						$atom_structure['sample_description_table'][$i]['encoder_revision'] = getid3_lib::BigEndian2Int(substr($atom_structure['sample_description_table'][$i]['data'],  2, 2));
						$atom_structure['sample_description_table'][$i]['encoder_vendor']   =                           substr($atom_structure['sample_description_table'][$i]['data'],  4, 4);

						switch ($atom_structure['sample_description_table'][$i]['encoder_vendor']) {

							case "\x00\x00\x00\x00":
								// audio tracks
								$atom_structure['sample_description_table'][$i]['audio_channels']       =   getid3_lib::BigEndian2Int(substr($atom_structure['sample_description_table'][$i]['data'],  8,  2));
								$atom_structure['sample_description_table'][$i]['audio_bit_depth']      =   getid3_lib::BigEndian2Int(substr($atom_structure['sample_description_table'][$i]['data'], 10,  2));
								$atom_structure['sample_description_table'][$i]['audio_compression_id'] =   getid3_lib::BigEndian2Int(substr($atom_structure['sample_description_table'][$i]['data'], 12,  2));
								$atom_structure['sample_description_table'][$i]['audio_packet_size']    =   getid3_lib::BigEndian2Int(substr($atom_structure['sample_description_table'][$i]['data'], 14,  2));
								$atom_structure['sample_description_table'][$i]['audio_sample_rate']    = getid3_lib::FixedPoint16_16(substr($atom_structure['sample_description_table'][$i]['data'], 16,  4));

								// video tracks
								// http://developer.apple.com/library/mac/#documentation/QuickTime/QTFF/QTFFChap3/qtff3.html
								$atom_structure['sample_description_table'][$i]['temporal_quality'] =   getid3_lib::BigEndian2Int(substr($atom_structure['sample_description_table'][$i]['data'],  8,  4));
								$atom_structure['sample_description_table'][$i]['spatial_quality']  =   getid3_lib::BigEndian2Int(substr($atom_structure['sample_description_table'][$i]['data'], 12,  4));
								$atom_structure['sample_description_table'][$i]['width']            =   getid3_lib::BigEndian2Int(substr($atom_structure['sample_description_table'][$i]['data'], 16,  2));
								$atom_structure['sample_description_table'][$i]['height']           =   getid3_lib::BigEndian2Int(substr($atom_structure['sample_description_table'][$i]['data'], 18,  2));
								$atom_structure['sample_description_table'][$i]['resolution_x']     = getid3_lib::FixedPoint16_16(substr($atom_structure['sample_description_table'][$i]['data'], 24,  4));
								$atom_structure['sample_description_table'][$i]['resolution_y']     = getid3_lib::FixedPoint16_16(substr($atom_structure['sample_description_table'][$i]['data'], 28,  4));
								$atom_structure['sample_description_table'][$i]['data_size']        =   getid3_lib::BigEndian2Int(substr($atom_structure['sample_description_table'][$i]['data'], 32,  4));
								$atom_structure['sample_description_table'][$i]['frame_count']      =   getid3_lib::BigEndian2Int(substr($atom_structure['sample_description_table'][$i]['data'], 36,  2));
								$atom_structure['sample_description_table'][$i]['compressor_name']  =                             substr($atom_structure['sample_description_table'][$i]['data'], 38,  4);
								$atom_structure['sample_description_table'][$i]['pixel_depth']      =   getid3_lib::BigEndian2Int(substr($atom_structure['sample_description_table'][$i]['data'], 42,  2));
								$atom_structure['sample_description_table'][$i]['color_table_id']   =   getid3_lib::BigEndian2Int(substr($atom_structure['sample_description_table'][$i]['data'], 44,  2));

								switch ($atom_structure['sample_description_table'][$i]['data_format']) {
									case '2vuY':
									case 'avc1':
									case 'cvid':
									case 'dvc ':
									case 'dvcp':
									case 'gif ':
									case 'h263':
									case 'jpeg':
									case 'kpcd':
									case 'mjpa':
									case 'mjpb':
									case 'mp4v':
									case 'png ':
									case 'raw ':
									case 'rle ':
									case 'rpza':
									case 'smc ':
									case 'SVQ1':
									case 'SVQ3':
									case 'tiff':
									case 'v210':
									case 'v216':
									case 'v308':
									case 'v408':
									case 'v410':
									case 'yuv2':
										$info['fileformat'] = 'mp4';
										$info['video']['fourcc'] = $atom_structure['sample_description_table'][$i]['data_format'];
										if ($this->QuicktimeVideoCodecLookup($info['video']['fourcc'])) {
											$info['video']['fourcc_lookup'] = $this->QuicktimeVideoCodecLookup($info['video']['fourcc']);
										}

										// https://www.getid3.org/phpBB3/viewtopic.php?t=1550
										//if ((!empty($atom_structure['sample_description_table'][$i]['width']) && !empty($atom_structure['sample_description_table'][$i]['width'])) && (empty($info['video']['resolution_x']) || empty($info['video']['resolution_y']) || (number_format($info['video']['resolution_x'], 6) != number_format(round($info['video']['resolution_x']), 6)) || (number_format($info['video']['resolution_y'], 6) != number_format(round($info['video']['resolution_y']), 6)))) { // ugly check for floating point numbers
										if (!empty($atom_structure['sample_description_table'][$i]['width']) && !empty($atom_structure['sample_description_table'][$i]['height'])) {
											// assume that values stored here are more important than values stored in [tkhd] atom
											$info['video']['resolution_x'] = $atom_structure['sample_description_table'][$i]['width'];
											$info['video']['resolution_y'] = $atom_structure['sample_description_table'][$i]['height'];
											$info['quicktime']['video']['resolution_x'] = $info['video']['resolution_x'];
											$info['quicktime']['video']['resolution_y'] = $info['video']['resolution_y'];
										}
										break;

									case 'qtvr':
										$info['video']['dataformat'] = 'quicktimevr';
										break;

									case 'mp4a':
									default:
										$info['quicktime']['audio']['codec']       = $this->QuicktimeAudioCodecLookup($atom_structure['sample_description_table'][$i]['data_format']);
										$info['quicktime']['audio']['sample_rate'] = $atom_structure['sample_description_table'][$i]['audio_sample_rate'];
										$info['quicktime']['audio']['channels']    = $atom_structure['sample_description_table'][$i]['audio_channels'];
										$info['quicktime']['audio']['bit_depth']   = $atom_structure['sample_description_table'][$i]['audio_bit_depth'];
										$info['audio']['codec']                    = $info['quicktime']['audio']['codec'];
										$info['audio']['sample_rate']              = $info['quicktime']['audio']['sample_rate'];
										$info['audio']['channels']                 = $info['quicktime']['audio']['channels'];
										$info['audio']['bits_per_sample']          = $info['quicktime']['audio']['bit_depth'];
										switch ($atom_structure['sample_description_table'][$i]['data_format']) {
											case 'raw ': // PCM
											case 'alac': // Apple Lossless Audio Codec
											case 'sowt': // signed/two's complement (Little Endian)
											case 'twos': // signed/two's complement (Big Endian)
											case 'in24': // 24-bit Integer
											case 'in32': // 32-bit Integer
											case 'fl32': // 32-bit Floating Point
											case 'fl64': // 64-bit Floating Point
												$info['audio']['lossless'] = $info['quicktime']['audio']['lossless'] = true;
												$info['audio']['bitrate']  = $info['quicktime']['audio']['bitrate']  = $info['audio']['channels'] * $info['audio']['bits_per_sample'] * $info['audio']['sample_rate'];
												break;
											default:
												$info['audio']['lossless'] = false;
												break;
										}
										break;
								}
								break;

							default:
								switch ($atom_structure['sample_description_table'][$i]['data_format']) {
									case 'mp4s':
										$info['fileformat'] = 'mp4';
										break;

									default:
										// video atom
										$atom_structure['sample_description_table'][$i]['video_temporal_quality']  =   getid3_lib::BigEndian2Int(substr($atom_structure['sample_description_table'][$i]['data'],  8,  4));
										$atom_structure['sample_description_table'][$i]['video_spatial_quality']   =   getid3_lib::BigEndian2Int(substr($atom_structure['sample_description_table'][$i]['data'], 12,  4));
										$atom_structure['sample_description_table'][$i]['video_frame_width']       =   getid3_lib::BigEndian2Int(substr($atom_structure['sample_description_table'][$i]['data'], 16,  2));
										$atom_structure['sample_description_table'][$i]['video_frame_height']      =   getid3_lib::BigEndian2Int(substr($atom_structure['sample_description_table'][$i]['data'], 18,  2));
										$atom_structure['sample_description_table'][$i]['video_resolution_x']      = getid3_lib::FixedPoint16_16(substr($atom_structure['sample_description_table'][$i]['data'], 20,  4));
										$atom_structure['sample_description_table'][$i]['video_resolution_y']      = getid3_lib::FixedPoint16_16(substr($atom_structure['sample_description_table'][$i]['data'], 24,  4));
										$atom_structure['sample_description_table'][$i]['video_data_size']         =   getid3_lib::BigEndian2Int(substr($atom_structure['sample_description_table'][$i]['data'], 28,  4));
										$atom_structure['sample_description_table'][$i]['video_frame_count']       =   getid3_lib::BigEndian2Int(substr($atom_structure['sample_description_table'][$i]['data'], 32,  2));
										$atom_structure['sample_description_table'][$i]['video_encoder_name_len']  =   getid3_lib::BigEndian2Int(substr($atom_structure['sample_description_table'][$i]['data'], 34,  1));
										$atom_structure['sample_description_table'][$i]['video_encoder_name']      =                             substr($atom_structure['sample_description_table'][$i]['data'], 35, $atom_structure['sample_description_table'][$i]['video_encoder_name_len']);
										$atom_structure['sample_description_table'][$i]['video_pixel_color_depth'] =   getid3_lib::BigEndian2Int(substr($atom_structure['sample_description_table'][$i]['data'], 66,  2));
										$atom_structure['sample_description_table'][$i]['video_color_table_id']    =   getid3_lib::BigEndian2Int(substr($atom_structure['sample_description_table'][$i]['data'], 68,  2));

										$atom_structure['sample_description_table'][$i]['video_pixel_color_type']  = (($atom_structure['sample_description_table'][$i]['video_pixel_color_depth'] > 32) ? 'grayscale' : 'color');
										$atom_structure['sample_description_table'][$i]['video_pixel_color_name']  = $this->QuicktimeColorNameLookup($atom_structure['sample_description_table'][$i]['video_pixel_color_depth']);

										if ($atom_structure['sample_description_table'