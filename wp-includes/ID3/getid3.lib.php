<?php

/////////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <info@getid3.org>               //
//  available at https://github.com/JamesHeinrich/getID3       //
//            or https://www.getid3.org                        //
//            or http://getid3.sourceforge.net                 //
//                                                             //
// getid3.lib.php - part of getID3()                           //
//  see readme.txt for more details                            //
//                                                            ///
/////////////////////////////////////////////////////////////////


class getid3_lib
{
	/**
	 * @param string $string
	 * @param bool   $hex
	 * @param bool   $spaces
	 * @param string $htmlencoding
	 *
	 * @return string
	 */
	public static function PrintHexBytes($string, $hex=true, $spaces=true, $htmlencoding='UTF-8') {
		$returnstring = '';
		for ($i = 0; $i < strlen($string); $i++) {
			if ($hex) {
				$returnstring .= str_pad(dechex(ord($string[$i])), 2, '0', STR_PAD_LEFT);
			} else {
				$returnstring .= ' '.(preg_match("#[\x20-\x7E]#", $string[$i]) ? $string[$i] : '¤');
			}
			if ($spaces) {
				$returnstring .= ' ';
			}
		}
		if (!empty($htmlencoding)) {
			if ($htmlencoding === true) {
				$htmlencoding = 'UTF-8'; // prior to getID3 v1.9.0 the function's 4th parameter was boolean
			}
			$returnstring = htmlentities($returnstring, ENT_QUOTES, $htmlencoding);
		}
		return $returnstring;
	}

	/**
	 * Truncates a floating-point number at the decimal point.
	 *
	 * @param float $floatnumber
	 *
	 * @return float|int returns int (if possible, otherwise float)
	 */
	public static function trunc($floatnumber) {
		if ($floatnumber >= 1) {
			$truncatednumber = floor($floatnumber);
		} elseif ($floatnumber <= -1) {
			$truncatednumber = ceil($floatnumber);
		} else {
			$truncatednumber = 0;
		}
		if (self::intValueSupported($truncatednumber)) {
			$truncatednumber = (int) $truncatednumber;
		}
		return $truncatednumber;
	}

	/**
	 * @param int|null $variable
	 * @param int      $increment
	 *
	 * @return bool
	 */
	public static function safe_inc(&$variable, $increment=1) {
		if (isset($variable)) {
			$variable += $increment;
		} else {
			$variable = $increment;
		}
		return true;
	}

	/**
	 * @param int|float $floatnum
	 *
	 * @return int|float
	 */
	public static function CastAsInt($floatnum) {
		// convert to float if not already
		$floatnum = (float) $floatnum;

		// convert a float to type int, only if possible
		if (self::trunc($floatnum) == $floatnum) {
			// it's not floating point
			if (self::intValueSupported($floatnum)) {
				// it's within int range
				$floatnum = (int) $floatnum;
			}
		}
		return $floatnum;
	}

	/**
	 * @param int $num
	 *
	 * @return bool
	 */
	public static function intValueSupported($num) {
		// check if integers are 64-bit
		static $hasINT64 = null;
		if ($hasINT64 === null) { // 10x faster than is_null()
			$hasINT64 = is_int(pow(2, 31)); // 32-bit int are limited to (2^31)-1
			if (!$hasINT64 && !defined('PHP_INT_MIN')) {
				define('PHP_INT_MIN', ~PHP_INT_MAX);
			}
		}
		// if integers are 64-bit - no other check required
		if ($hasINT64 || (($num <= PHP_INT_MAX) && ($num >= PHP_INT_MIN))) {
			return true;
		}
		return false;
	}

	/**
	 * @param string $fraction
	 *
	 * @return float
	 */
	public static function DecimalizeFraction($fraction) {
		list($numerator, $denominator) = explode('/', $fraction);
		return $numerator / ($denominator ? $denominator : 1);
	}

	/**
	 * @param string $binarynumerator
	 *
	 * @return float
	 */
	public static function DecimalBinary2Float($binarynumerator) {
		$numerator   = self::Bin2Dec($binarynumerator);
		$denominator = self::Bin2Dec('1'.str_repeat('0', strlen($binarynumerator)));
		return ($numerator / $denominator);
	}

	/**
	 * @link http://www.scri.fsu.edu/~jac/MAD3401/Backgrnd/binary.html
	 *
	 * @param string $binarypointnumber
	 * @param int    $maxbits
	 *
	 * @return array
	 */
	public static function NormalizeBinaryPoint($binarypointnumber, $maxbits=52) {
		if (strpos($binarypointnumber, '.') === false) {
			$binarypointnumber = '0.'.$binarypointnumber;
		} elseif ($binarypointnumber[0] == '.') {
			$binarypointnumber = '0'.$binarypointnumber;
		}
		$exponent = 0;
		while (($binarypointnumber[0] != '1') || (substr($binarypointnumber, 1, 1) != '.')) {
			if (substr($binarypointnumber, 1, 1) == '.') {
				$exponent--;
				$binarypointnumber = substr($binarypointnumber, 2, 1).'.'.substr($binarypointnumber, 3);
			} else {
				$pointpos = strpos($binarypointnumber, '.');
				$exponent += ($pointpos - 1);
				$binarypointnumber = str_replace('.', '', $binarypointnumber);
				$binarypointnumber = $binarypointnumber[0].'.'.substr($binarypointnumber, 1);
			}
		}
		$binarypointnumber = str_pad(substr($binarypointnumber, 0, $maxbits + 2), $maxbits + 2, '0', STR_PAD_RIGHT);
		return array('normalized'=>$binarypointnumber, 'exponent'=>(int) $exponent);
	}

	/**
	 * @link http://www.scri.fsu.edu/~jac/MAD3401/Backgrnd/binary.html
	 *
	 * @param float $floatvalue
	 *
	 * @return string
	 */
	public static function Float2BinaryDecimal($floatvalue) {
		$maxbits = 128; // to how many bits of precision should the calculations be taken?
		$intpart   = self::trunc($floatvalue);
		$floatpart = abs($floatvalue - $intpart);
		$pointbitstring = '';
		while (($floatpart != 0) && (strlen($pointbitstring) < $maxbits)) {
			$floatpart *= 2;
			$pointbitstring .= (string) self::trunc($floatpart);
			$floatpart -= self::trunc($floatpart);
		}
		$binarypointnumber = decbin($intpart).'.'.$pointbitstring;
		return $binarypointnumber;
	}

	/**
	 * @link http://www.scri.fsu.edu/~jac/MAD3401/Backgrnd/ieee-expl.html
	 *
	 * @param float $floatvalue
	 * @param int $bits
	 *
	 * @return string|false
	 */
	public static function Float2String($floatvalue, $bits) {
		$exponentbits = 0;
		$fractionbits = 0;
		switch ($bits) {
			case 32:
				$exponentbits = 8;
				$fractionbits = 23;
				break;

			case 64:
				$exponentbits = 11;
				$fractionbits = 52;
				break;

			default:
				return false;
				break;
		}
		if ($floatvalue >= 0) {
			$signbit = '0';
		} else {
			$signbit = '1';
		}
		$normalizedbinary  = self::NormalizeBinaryPoint(self::Float2BinaryDecimal($floatvalue), $fractionbits);
		$biasedexponent    = pow(2, $exponentbits - 1) - 1 + $normalizedbinary['exponent']; // (127 or 1023) +/- exponent
		$exponentbitstring = str_pad(decbin($biasedexponent), $exponentbits, '0', STR_PAD_LEFT);
		$fractionbitstring = str_pad(substr($normalizedbinary['normalized'], 2), $fractionbits, '0', STR_PAD_RIGHT);

		return self::BigEndian2String(self::Bin2Dec($signbit.$exponentbitstring.$fractionbitstring), $bits % 8, false);
	}

	/**
	 * @param string $byteword
	 *
	 * @return float|false
	 */
	public static function LittleEndian2Float($byteword) {
		return self::BigEndian2Float(strrev($byteword));
	}

	/**
	 * ANSI/IEEE Standard 754-1985, Standard for Binary Floating Point Arithmetic
	 *
	 * @link http://www.psc.edu/general/software/packages/ieee/ieee.html
	 * @link http://www.scri.fsu.edu/~jac/MAD3401/Backgrnd/ieee.html
	 *
	 * @param string $byteword
	 *
	 * @return float|false
	 */
	public static function BigEndian2Float($byteword) {
		$bitword = self::BigEndian2Bin($byteword);
		if (!$bitword) {
			return 0;
		}
		$signbit = $bitword[0];
		$floatvalue = 0;
		$exponentbits = 0;
		$fractionbits = 0;

		switch (strlen($byteword) * 8) {
			case 32:
				$exponentbits = 8;
				$fractionbits = 23;
				break;

			case 64:
				$exponentbits = 11;
				$fractionbits = 52;
				break;

			case 80:
				// 80-bit Apple SANE format
				// http://www.mactech.com/articles/mactech/Vol.06/06.01/SANENormalized/
				$exponentstring = substr($bitword, 1, 15);
				$isnormalized = intval($bitword[16]);
				$fractionstring = substr($bitword, 17, 63);
				$exponent = pow(2, self::Bin2Dec($exponentstring) - 16383);
				$fraction = $isnormalized + self::DecimalBinary2Float($fractionstring);
				$floatvalue = $exponent * $fraction;
				if ($signbit == '1') {
					$floatvalue *= -1;
				}
				return $floatvalue;
				break;

			default:
				return false;
				break;
		}
		$exponentstring = substr($bitword, 1, $exponentbits);
		$fractionstring = substr($bitword, $exponentbits + 1, $fractionbits);
		$exponent = self::Bin2Dec($exponentstring);
		$fraction = self::Bin2Dec($fractionstring);

		if (($exponent == (pow(2, $exponentbits) - 1)) && ($fraction != 0)) {
			// Not a Number
			$floatvalue = false;
		} elseif (($exponent == (pow(2, $exponentbits) - 1)) && ($fraction == 0)) {
			if ($signbit == '1') {
				$floatvalue = '-infinity';
			} else {
				$floatvalue = '+infinity';
			}
		} elseif (($exponent == 0) && ($fraction == 0)) {
			if ($signbit == '1') {
				$floatvalue = -0;
			} else {
				$floatvalue = 0;
			}
			$floatvalue = ($signbit ? 0 : -0);
		} elseif (($exponent == 0) && ($fraction != 0)) {
			// These are 'unnormalized' values
			$floatvalue = pow(2, (-1 * (pow(2, $exponentbits - 1) - 2))) * self::DecimalBinary2Float($fractionstring);
			if ($signbit == '1') {
				$floatvalue *= -1;
			}
		} elseif ($exponent != 0) {
			$floatvalue = pow(2, ($exponent - (pow(2, $exponentbits - 1) - 1))) * (1 + self::DecimalBinary2Float($fractionstring));
			if ($signbit == '1') {
				$floatvalue *= -1;
			}
		}
		return (float) $floatvalue;
	}

	/**
	 * @param string $byteword
	 * @param bool   $synchsafe
	 * @param bool   $signed
	 *
	 * @return int|float|false
	 * @throws Exception
	 */
	public static function BigEndian2Int($byteword, $synchsafe=false, $signed=false) {
		$intvalue = 0;
		$bytewordlen = strlen($byteword);
		if ($bytewordlen == 0) {
			return false;
		}
		for ($i = 0; $i < $bytewordlen; $i++) {
			if ($synchsafe) { // disregard MSB, effectively 7-bit bytes
				//$intvalue = $intvalue | (ord($byteword{$i}) & 0x7F) << (($bytewordlen - 1 - $i) * 7); // faster, but runs into problems past 2^31 on 32-bit systems
				$intvalue += (ord($byteword[$i]) & 0x7F) * pow(2, ($bytewordlen - 1 - $i) * 7);
			} else {
				$intvalue += ord($byteword[$i]) * pow(256, ($bytewordlen - 1 - $i));
			}
		}
		if ($signed && !$synchsafe) {
			// synchsafe ints are not allowed to be signed
			if ($bytewordlen <= PHP_INT_SIZE) {
				$signMaskBit = 0x80 << (8 * ($bytewordlen - 1));
				if ($intvalue & $signMaskBit) {
					$intvalue = 0 - ($intvalue & ($signMaskBit - 1));
				}
			} else {
				throw new Exception('ERROR: Cannot have signed integers larger than '.(8 * PHP_INT_SIZE).'-bits ('.strlen($byteword).') in self::BigEndian2Int()');
			}
		}
		return self::CastAsInt($intvalue);
	}

	/**
	 * @param string $byteword
	 * @param bool   $signed
	 *
	 * @return int|float|false
	 */
	public static function LittleEndian2Int($byteword, $signed=false) {
		return self::BigEndian2Int(strrev($byteword), false, $signed);
	}

	/**
	 * @param string $byteword
	 *
	 * @return string
	 */
	public static function LittleEndian2Bin($byteword) {
		return self::BigEndian2Bin(strrev($byteword));
	}

	/**
	 * @param string $byteword
	 *
	 * @return string
	 */
	public static function BigEndian2Bin($byteword) {
		$binvalue = '';
		$bytewordlen = strlen($byteword);
		for ($i = 0; $i < $bytewordlen; $i++) {
			$binvalue .= str_pad(decbin(ord($byteword[$i])), 8, '0', STR_PAD_LEFT);
		}
		return $binvalue;
	}

	/**
	 * @param int  $number
	 * @param int  $minbytes
	 * @param bool $synchsafe
	 * @param bool $signed
	 *
	 * @return string
	 * @throws Exception
	 */
	public static function BigEndian2String($number, $minbytes=1, $synchsafe=false, $signed=false) {
		if ($number < 0) {
			throw new Exception('ERROR: self::BigEndian2String() does not support negative numbers');
		}
		$maskbyte = (($synchsafe || $signed) ? 0x7F : 0xFF);
		$intstring = '';
		if ($signed) {
			if ($minbytes > PHP_INT_SIZE) {
				throw new Exception('ERROR: Cannot have signed integers larger than '.(8 * PHP_INT_SIZE).'-bits in self::BigEndian2String()');
			}
			$number = $number & (0x80 << (8 * ($minbytes - 1)));
		}
		while ($number != 0) {
			$quotient = ($number / ($maskbyte + 1));
			$intstring = chr(ceil(($quotient - floor($quotient)) * $maskbyte)).$intstring;
			$number = floor($quotient);
		}
		return str_pad($intstring, $minbytes, "\x00", STR_PAD_LEFT);
	}

	/**
	 * @param int $number
	 *
	 * @return string
	 */
	public static function Dec2Bin($number) {
		while ($number >= 256) {
			$bytes[] = (($number / 256) - (floor($number / 256))) * 256;
			$number = floor($number / 256);
		}
		$bytes[] = $number;
		$binstring = '';
		for ($i = 0; $i < count($bytes); $i++) {
			$binstring = (($i == count($bytes) - 1) ? decbin($bytes[$i]) : str_pad(decbin($bytes[$i]), 8, '0', STR_PAD_LEFT)).$binstring;
		}
		return $binstring;
	}

	/**
	 * @param string $binstring
	 * @param bool   $signed
	 *
	 * @return int|float
	 */
	public static function Bin2Dec($binstring, $signed=false) {
		$signmult = 1;
		if ($signed) {
			if ($binstring[0] == '1') {
				$signmult = -1;
			}
			$binstring = substr($binstring, 1);
		}
		$decvalue = 0;
		for ($i = 0; $i < strlen($binstring); $i++) {
			$decvalue += ((int) substr($binstring, strlen($binstring) - $i - 1, 1)) * pow(2, $i);
		}
		return self::CastAsInt($decvalue * $signmult);
	}

	/**
	 * @param string $binstring
	 *
	 * @return string
	 */
	public static function Bin2String($binstring) {
		// return 'hi' for input of '0110100001101001'
		$string = '';
		$binstringreversed = strrev($binstring);
		for ($i = 0; $i < strlen($binstringreversed); $i += 8) {
			$string = chr(self::Bin2Dec(strrev(substr($binstringreversed, $i, 8)))).$string;
		}
		return $string;
	}

	/**
	 * @param int  $number
	 * @param int  $minbytes
	 * @param bool $synchsafe
	 *
	 * @return string
	 */
	public static function LittleEndian2String($number, $minbytes=1, $synchsafe=false) {
		$intstring = '';
		while ($number > 0) {
			if ($synchsafe) {
				$intstring = $intstring.chr($number & 127);
				$number >>= 7;
			} else {
				$intstring = $intstring.chr($number & 255);
				$number >>= 8;
			}
		}
		return str_pad($intstring, $minbytes, "\x00", STR_PAD_RIGHT);
	}

	/**
	 * @param array $array1
	 * @param array $array2
	 *
	 * @return array|false
	 */
	public static function array_merge_clobber($array1, $array2) {
		// written by kcØhireability*com
		// taken from http://www.php.net/manual/en/function.array-merge-recursive.php
		if (!is_array($array1) || !is_array($array2)) {
			return false;
		}
		$newarray = $array1;
		foreach ($array2 as $key => $val) {
			if (is_array($val) && isset($newarray[$key]) && is_array($newarray[$key])) {
				$newarray[$key] = self::array_merge_clobber($newarray[$key], $val);
			} else {
				$newarray[$key] = $val;
			}
		}
		return $newarray;
	}

	/**
	 * @param array $array1
	 * @param array $array2
	 *
	 * @return array|false
	 */
	public static function array_merge_noclobber($array1, $array2) {
		if (!is_array($array1) || !is_array($array2)) {
			return false;
		}
		$newarray = $array1;
		foreach ($array2 as $key => $val) {
			if (is_array($val) && isset($newarray[$key]) && is_array($newarray[$key])) {
				$newarray[$key] = self::array_merge_noclobber($newarray[$key], $val);
			} elseif (!isset($newarray[$key])) {
				$newarray[$key] = $val;
			}
		}
		return $newarray;
	}

	/**
	 * @param array $array1
	 * @param array $array2
	 *
	 * @return array|false|null
	 */
	public static function flipped_array_merge_noclobber($array1, $array2) {
		if (!is_array($array1) || !is_array($array2)) {
			return false;
		}
		# naturally, this only works non-recursively
		$newarray = array_flip($array1);
		foreach (array_flip($array2) as $key => $val) {
			if (!isset($newarray[$key])) {
				$newarray[$key] = count($newarray);
			}
		}
		return array_flip($newarray);
	}

	/**
	 * @param array $theArray
	 *
	 * @return bool
	 */
	public static function ksort_recursive(&$theArray) {
		ksort($theArray);
		foreach ($theArray as $key => $value) {
			if (is_array($value)) {
				self::ksort_recursive($theArray[$key]);
			}
		}
		return true;
	}

	/**
	 * @param string $filename
	 * @param int    $numextensions
	 *
	 * @return string
	 */
	public static function fileextension($filename, $numextensions=1) {
		if (strstr($filename, '.')) {
			$reversedfilename = strrev($filename);
			$offset = 0;
			for ($i = 0; $i < $numextensions; $i++) {
				$offset = strpos($reversedfilename, '.', $offset + 1);
				if ($offset === false) {
					return '';
				}
			}
			return strrev(substr($reversedfilename, 0, $offset));
		}
		return '';
	}

	/**
	 * @param int $seconds
	 *
	 * @return string
	 */
	public static function PlaytimeString($seconds) {
		$sign = (($seconds < 0) ? '-' : '');
		$seconds = round(abs($seconds));
		$H = (int) floor( $seconds                            / 3600);
		$M = (int) floor(($seconds - (3600 * $H)            ) /   60);
		$S = (int) round( $seconds - (3600 * $H) - (60 * $M)        );
		return $sign.($H ? $H.':' : '').($H ? str_pad($M, 2, '0', STR_PAD_LEFT) : intval($M)).':'.str_pad($S, 2, 0, STR_PAD_LEFT);
	}

	/**
	 * @param int $macdate
	 *
	 * @return int|float
	 */
	public static function DateMac2Unix($macdate) {
		// Macintosh timestamp: seconds since 00:00h January 1, 1904
		// UNIX timestamp:      seconds since 00:00h January 1, 1970
		return self::CastAsInt($macdate - 2082844800);
	}

	/**
	 * @param string $rawdata
	 *
	 * @return float
	 */
	public static function FixedPoint8_8($rawdata) {
		return self::BigEndian2Int(substr($rawdata, 0, 1)) + (float) (self::BigEndian2Int(substr($rawdata, 1, 1)) / pow(2, 8));
	}

	/**
	 * @param string $rawdata
	 *
	 * @return float
	 */
	public static function FixedPoint16_16($rawdata) {
		return self::BigEndian2Int(substr($rawdata, 0, 2)) + (float) (self::BigEndian2Int(substr($rawdata, 2, 2)) / pow(2, 16));
	}

	/**
	 * @param string $rawdata
	 *
	 * @return float
	 */
	public static function FixedPoint2_30($rawdata) {
		$binarystring = self::BigEndian2Bin($rawdata);
		return self::Bin2Dec(substr($binarystring, 0, 2)) + (float) (self::Bin2Dec(substr($binarystring, 2, 30)) / pow(2, 30));
	}


	/**
	 * @param string $ArrayPath
	 * @param string $Separator
	 * @param mixed $Value
	 *
	 * @return array
	 */
	public static function CreateDeepArray($ArrayPath, $Separator, $Value) {
		// assigns $Value to a nested array path:
		//   $foo = self::CreateDeepArray('/path/to/my', '/', 'file.txt')
		// is the same as:
		//   $foo = array('path'=>array('to'=>'array('my'=>array('file.txt'))));
		// or
		//   $foo['path']['to']['my'] = 'file.txt';
		$ArrayPath = ltrim($ArrayPath, $Separator);
		if (($pos = strpos($ArrayPath, $Separator)) !== false) {
			$ReturnedArray[substr($ArrayPath, 0, $pos)] = self::CreateDeepArray(substr($ArrayPath, $pos + 1), $Separator, $Value);
		} else {
			$ReturnedArray[$ArrayPath] = $Value;
		}
		return $ReturnedArray;
	}

	/**
	 * @param array $arraydata
	 * @param bool  $returnkey
	 *
	 * @return int|false
	 */
	public static function array_max($arraydata, $returnkey=false) {
		$maxvalue = false;
		$maxkey = false;
		foreach ($arraydata as $key => $value) {
			if (!is_array($value)) {
				if ($value > $maxvalue) {
					$maxvalue = $value;
					$maxkey = $key;
				}
			}
		}
		return ($returnkey ? $maxkey : $maxvalue);
	}

	/**
	 * @param array $arraydata
	 * @param bool  $returnkey
	 *
	 * @return int|false
	 */
	public static function array_min($arraydata, $returnkey=false) {
		$minvalue = false;
		$minkey = false;
		foreach ($arraydata as $key => $value) {
			if (!is_array($value)) {
				if ($value > $minvalue) {
					$minvalue = $value;
					$minkey = $key;
				}
			}
		}
		return ($returnkey ? $minkey : $minvalue);
	}

	/**
	 * @param string $XMLstring
	 *
	 * @return array|false
	 */
	public static function XML2array($XMLstring) {
		if (function_exists('simplexml_load_string') && function_exists('libxml_disable_entity_loader')) {
			// http://websec.io/2012/08/27/Preventing-XEE-in-PHP.html
			// https://core.trac.wordpress.org/changeset/29378
			$loader = libxml_disable_entity_loader(true);
			$XMLobject = simplexml_load_string($XMLstring, 'SimpleXMLElement', LIBXML_NOENT);
			$return = self::SimpleXMLelement2array($XMLobject);
			libxml_disable_entity_loader($loader);
			return $return;
		}
		return false;
	}

	/**
	* @param SimpleXMLElement|array $XMLobject
	*
	* @return array
	*/
	public static function SimpleXMLelement2array($XMLobject) {
		if (!is_object($XMLobject) && !is_array($XMLobject)) {
			return $XMLobject;
		}
		$XMLarray = $XMLobject instanceof SimpleXMLElement ? get_object_vars($XMLobject) : $XMLobject;
		foreach ($XMLarray as $key => $value) {
			$XMLarray[$key] = self::SimpleXMLelement2array($value);
		}
		return $XMLarray;
	}

	/**
	 * Returns checksum for a file from starting position to absolute end position.
	 *
	 * @param string $file
	 * @param int    $offset
	 * @param int    $end
	 * @param string $algorithm
	 *
	 * @return string|false
	 * @throws getid3_exception
	 */
	public static function hash_data($file, $offset, $end, $algorithm) {
		if (!self::intValueSupported($end)) {
			return false;
		}
		if (!in_array($algorithm, array('md5', 'sha1'))) {
			throw new getid3_exception('Invalid algorithm ('.$algorithm.') in self::hash_data()');
		}

		$size = $end - $offset;

		$fp = fopen($file, 'rb');
		fseek($fp, $offset);
		$ctx = hash_init($algorithm);
		while ($size > 0) {
			$buffer = fread($fp, min($size, getID3::FREAD_BUFFER_SIZE));
			hash_update($ctx, $buffer);
			$size -= getID3::FREAD_BUFFER_SIZE;
		}
		$hash = hash_final($ctx);
		fclose($fp);

		return $hash;
	}

	/**
	 * @param string $filename_source
	 * @param string $filename_dest
	 * @param int    $offset
	 * @param int    $length
	 *
	 * @return bool
	 * @throws Exception
	 *
	 * @deprecated Unused, may be removed in future versions of getID3
	 */
	public static function CopyFileParts($filename_source, $filename_dest, $offset, $length) {
		if (!self::intValueSupported($offset + $length)) {
			throw new Exception('cannot copy file portion, it extends beyond the '.round(PHP_INT_MAX / 1073741824).'GB limit');
		}
		if (is_readable($filename_source) && is_file($filename_source) && ($fp_src = fopen($filename_source, 'rb'))) {
			if (($fp_dest = fopen($filename_dest, 'wb'))) {
				if (fseek($fp_src, $offset) == 0) {
					$byteslefttowrite = $length;
					while (($byteslefttowrite > 0) && ($buffer = fread($fp_src, min($byteslefttowrite, getID3::FREAD_BUFFER_SIZE)))) {
						$byteswritten = fwrite($fp_dest, $buffer, $byteslefttowrite);
						$byteslefttowrite -= $byteswritten;
					}
					fclose($fp_dest);
					return true;
				} else {
					fclose($fp_src);
					throw new Exception('failed to seek to offset '.$offset.' in '.$filename_source);
				}
			} else {
				throw new Exception('failed to create file for writing '.$filename_dest);
			}
		} else {
			throw new Exception('failed to open file for reading '.$filename_source);
		}
	}

	/**
	 * @param int $charval
	 *
	 * @return string
	 */
	public static function iconv_fallback_int_utf8($charval) {
		if ($charval < 128) {
			// 0bbbbbbb
			$newcharstring = chr($charval);
		} elseif ($charval < 2048) {
			// 110bbbbb 10bbbbbb
			$newcharstring  = chr(($charval >>   6) | 0xC0);
			$newcharstring .= chr(($charval & 0x3F) | 0x80);
		} elseif ($charval < 65536) {
			// 1110bbbb 10bbbbbb 10bbbbbb
			$newcharstring  = chr(($charval >>  12) | 0xE0);
			$newcharstring .= chr(($charval >>   6) | 0xC0);
			$newcharstring .= chr(($charval & 0x3F) | 0x80);
		} else {
			// 11110bbb 10bbbbbb 10bbbbbb 10bbbbbb
			$newcharstring  = chr(($charval >>  18) | 0xF0);
			$newcharstring .= chr(($charval >>  12) | 0xC0);
			$newcharstring .= chr(($charval >>   6) | 0xC0);
			$newcharstring .= chr(($charval & 0x3F) | 0x80);
		}
		return $newcharstring;
	}

	/**
	 * ISO-8859-1 => UTF-8
	 *
	 * @param string $string
	 * @param bool   $bom
	 *
	 * @return string
	 */
	public static function iconv_fallback_iso88591_utf8($string, $bom=false) {
		if (function_exists('utf8_encode')) {
			return utf8_encode($string);
		}
		// utf8_encode() unavailable, use getID3()'s iconv_fallback() conversions (possibly PHP is compiled without XML support)
		$newcharstring = '';
		if ($bom) {
			$newcharstring .= "\xEF\xBB\xBF";
		}
		for ($i = 0; $i < strlen($string); $i++) {
			$charval = ord($string[$i]);
			$newcharstring .= self::iconv_fallback_int_utf8($charval);
		}
		return $newcharstring;
	}

	/**
	 * ISO-8859-1 => UTF-16BE
	 *
	 * @param string $string
	 * @param bool   $bom
	 *
	 * @return string
	 */
	public static function iconv_fallback_iso88591_utf16be($string, $bom=false) {
		$newcharstring = '';
		if ($bom) {
			$newcharstring .= "\xFE\xFF";
		}
		for ($i = 0; $i < strlen($string); $i++) {
