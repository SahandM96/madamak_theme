<?php

/////////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <info@getid3.org>               //
//  available at https://github.com/JamesHeinrich/getID3       //
//            or https://www.getid3.org                        //
//            or http://getid3.sourceforge.net                 //
//  see readme.txt for more details                            //
/////////////////////////////////////////////////////////////////
//                                                             //
// module.audio-video.matriska.php                             //
// module for analyzing Matroska containers                    //
// dependencies: NONE                                          //
//                                                            ///
/////////////////////////////////////////////////////////////////


define('EBML_ID_CHAPTERS',                  0x0043A770); // [10][43][A7][70] -- A system to define basic menus and partition data. For more detailed information, look at the Chapters Explanation.
define('EBML_ID_SEEKHEAD',                  0x014D9B74); // [11][4D][9B][74] -- Contains the position of other level 1 elements.
define('EBML_ID_TAGS',                      0x0254C367); // [12][54][C3][67] -- Element containing elements specific to Tracks/Chapters. A list of valid tags can be found <http://www.matroska.org/technical/specs/tagging/index.html>.
define('EBML_ID_INFO',                      0x0549A966); // [15][49][A9][66] -- Contains miscellaneous general information and statistics on the file.
define('EBML_ID_TRACKS',                    0x0654AE6B); // [16][54][AE][6B] -- A top-level block of information with many tracks described.
define('EBML_ID_SEGMENT',                   0x08538067); // [18][53][80][67] -- This element contains all other top-level (level 1) elements. Typically a Matroska file is composed of 1 segment.
define('EBML_ID_ATTACHMENTS',               0x0941A469); // [19][41][A4][69] -- Contain attached files.
define('EBML_ID_EBML',                      0x0A45DFA3); // [1A][45][DF][A3] -- Set the EBML characteristics of the data to follow. Each EBML document has to start with this.
define('EBML_ID_CUES',                      0x0C53BB6B); // [1C][53][BB][6B] -- A top-level element to speed seeking access. All entries are local to the segment.
define('EBML_ID_CLUSTER',                   0x0F43B675); // [1F][43][B6][75] -- The lower level element containing the (monolithic) Block structure.
define('EBML_ID_LANGUAGE',                    0x02B59C); //     [22][B5][9C] -- Specifies the language of the track in the Matroska languages form.
define('EBML_ID_TRACKTIMECODESCALE',          0x03314F); //     [23][31][4F] -- The scale to apply on this track to work at normal speed in relation with other tracks (mostly used to adjust video speed when the audio length differs).
define('EBML_ID_DEFAULTDURATION',             0x03E383); //     [23][E3][83] -- Number of nanoseconds (i.e. not scaled) per frame.
define('EBML_ID_CODECNAME',                   0x058688); //     [25][86][88] -- A human-readable string specifying the codec.
define('EBML_ID_CODECDOWNLOADURL',            0x06B240); //     [26][B2][40] -- A URL to download about the codec used.
define('EBML_ID_TIMECODESCALE',               0x0AD7B1); //     [2A][D7][B1] -- Timecode scale in nanoseconds (1.000.000 means all timecodes in the segment are expressed in milliseconds).
define('EBML_ID_COLOURSPACE',                 0x0EB524); //     [2E][B5][24] -- Same value as in AVI (32 bits).
define('EBML_ID_GAMMAVALUE',                  0x0FB523); //     [2F][B5][23] -- Gamma Value.
define('EBML_ID_CODECSETTINGS',               0x1A9697); //     [3A][96][97] -- A string describing the encoding setting used.
define('EBML_ID_CODECINFOURL',                0x1B4040); //     [3B][40][40] -- A URL to find information about the codec used.
define('EBML_ID_PREVFILENAME',                0x1C83AB); //     [3C][83][AB] -- An escaped filename corresponding to the previous segment.
define('EBML_ID_PREVUID',                     0x1CB923); //     [3C][B9][23] -- A unique ID to identify the previous chained segment (128 bits).
define('EBML_ID_NEXTFILENAME',                0x1E83BB); //     [3E][83][BB] -- An escaped filename corresponding to the next segment.
define('EBML_ID_NEXTUID',                     0x1EB923); //     [3E][B9][23] -- A unique ID to identify the next chained segment (128 bits).
define('EBML_ID_CONTENTCOMPALGO',               0x0254); //         [42][54] -- The compression algorithm used. Algorithms that have been specified so far are:
define('EBML_ID_CONTENTCOMPSETTINGS',           0x0255); //         [42][55] -- Settings that might be needed by the decompressor. For Header Stripping (ContentCompAlgo=3), the bytes that were removed from the beggining of each frames of the track.
define('EBML_ID_DOCTYPE',                       0x0282); //         [42][82] -- A string that describes the type of document that follows this EBML header ('matroska' in our case).
define('EBML_ID_DOCTYPEREADVERSION',            0x0285); //         [42][85] -- The minimum DocType version an interpreter has to support to read this file.
define('EBML_ID_EBMLVERSION',                   0x0286); //         [42][86] -- The version of EBML parser used to create the file.
define('EBML_ID_DOCTYPEVERSION',                0x0287); //         [42][87] -- The version of DocType interpreter used to create the file.
define('EBML_ID_EBMLMAXIDLENGTH',               0x02F2); //         [42][F2] -- The maximum length of the IDs you'll find in this file (4 or less in Matroska).
define('EBML_ID_EBMLMAXSIZELENGTH',             0x02F3); //         [42][F3] -- The maximum length of the sizes you'll find in this file (8 or less in Matroska). This does not override the element size indicated at the beginning of an element. Elements that have an indicated size which is larger than what is allowed by EBMLMaxSizeLength shall be considered invalid.
define('EBML_ID_EBMLREADVERSION',               0x02F7); //         [42][F7] -- The minimum EBML version a parser has to support to read this file.
define('EBML_ID_CHAPLANGUAGE',                  0x037C); //         [43][7C] -- The languages corresponding to the string, in the bibliographic ISO-639-2 form.
define('EBML_ID_CHAPCOUNTRY',                   0x037E); //         [43][7E] -- The countries corresponding to the string, same 2 octets as in Internet domains.
define('EBML_ID_SEGMENTFAMILY',                 0x0444); //         [44][44] -- A randomly generated unique ID that all segments related to each other must use (128 bits).
define('EBML_ID_DATEUTC',                       0x0461); //         [44][61] -- Date of the origin of timecode (value 0), i.e. production date.
define('EBML_ID_TAGLANGUAGE',                   0x047A); //         [44][7A] -- Specifies