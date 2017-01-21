<?php
namespace System\Libs;

class Replacer {
	/**
	 * Lecserélendő karakterek tömbje.
	 * index:pattern,
	 * érték:mire
	 *
	 * @var array
	 */
	private static $_abc = array(
		'/À|Á|Â|Ã|Ä|Å|Ǻ|Ā|Ă|Ą|Ǎ/' => 'A',
		'/à|á|â|ã|å|ǻ|ā|ă|ą|ǎ|ª|ä|æ|ǽ/' => 'a',
		'/Ç|Ć|Ĉ|Ċ|Č/' => 'C',
		'/ç|ć|ĉ|ċ|č/' => 'c',
		'/Ð|Ď|Đ/' => 'D',
		'/ð|ď|đ/' => 'd',
		'/È|É|Ê|Ë|Ē|Ĕ|Ė|Ę|Ě/' => 'E',
		'/è|é|ê|ë|ē|ĕ|ė|ę|ě/' => 'e',
		'/Ĝ|Ğ|Ġ|Ģ/' => 'G',
		'/ĝ|ğ|ġ|ģ/' => 'g',
		'/Ĥ|Ħ/' => 'H',
		'/ĥ|ħ/' => 'h',
		'/Ì|Í|Î|Ï|Ĩ|Ī|Ĭ|Ǐ|Į|İ/' => 'I',
		'/ì|í|î|ï|ĩ|ī|ĭ|ǐ|į|ı/' => 'i',
		'/Ĵ/' => 'J',
		'/ĵ/' => 'j',
		'/Ķ/' => 'K',
		'/ķ/' => 'k',
		'/Ĺ|Ļ|Ľ|Ŀ|Ł/' => 'L',
		'/ĺ|ļ|ľ|ŀ|ł/' => 'l',
		'/Ñ|Ń|Ņ|Ň/' => 'N',
		'/ñ|ń|ņ|ň|ŉ/' => 'n',
		'/Ò|Ó|Ô|Õ|Ō|Ŏ|Ǒ|Ő|Ơ|Ø|Ǿ|Ö/' => 'O',
		'/ò|ó|ô|õ|ō|ŏ|ǒ|ő|ơ|ø|ǿ|º|ö|œ/' => 'o',
		'/Ŕ|Ŗ|Ř/' => 'R',
		'/ŕ|ŗ|ř/' => 'r',
		'/Ś|Ŝ|Ş|Š/' => 'S',
		'/ś|ŝ|ş|š|ſ/' => 's',
		'/Ţ|Ť|Ŧ/' => 'T',
		'/ţ|ť|ŧ/' => 't',
		'/Ù|Ú|Û|Ũ|Ū|Ŭ|Ů|Ű|Ų|Ư|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ|Ü/' => 'U',
		'/ù|ú|û|ũ|ū|ŭ|ů|ű|ų|ư|ǔ|ǖ|ǘ|ǚ|ǜ|ü/' => 'u',
		'/Ý|Ÿ|Ŷ/' => 'Y',
		'/ý|ÿ|ŷ/' => 'y',
		'/Ŵ/' => 'W',
		'/ŵ/' => 'w',
		'/Ź|Ż|Ž/' => 'Z',
		'/ź|ż|ž/' => 'z',
		'/Æ|Ǽ/' => 'AE',
		'/ß/'=> 'ss',
		'/Ĳ/' => 'IJ',
		'/ĳ/' => 'ij',
		'/Œ/' => 'OE',
		'/ƒ/' => 'f'
	);
	
				/**
				 * Lecserélendő karakterek tömbje.
				 * index:pattern,
				 * érték:mire
				 *
				 * @var array
				 */
					private static $_spec_char = array(
						'/\'|\"/' => ''
					);	
	

	/**
	 * Mire legyenek helyettesítve a whitespace karakterek és
	 * az egyik szabálynak sem megfelelő karakterek.
	 *
	 * @var string
	 */
	private static $_defaultReplacement = '-';
	
	/**
	 * Fájlnevek karaktereinek szűrése és lecserélése
	 *
	 * @param string $name Fájlnév
	 * @return string A módosított fájlnév
	 */
	public static function filterName($name)
	{
		$replacement = preg_quote(self::$_defaultReplacement, '/');

		$merge = array(
			'/[^\s\w\d]/mu' => ' ',
			'/\\s+/' => self::$_defaultReplacement,
			sprintf('/^[%s]+|[%s]+$/', $replacement, $replacement) => '',
		);

		$map = self::$_abc + $merge;
		
		$newname = preg_replace(array_keys($map), array_values($map), $name);
		$newname = mb_strtolower($newname);
		return $newname;
	}
	
	/**
	 * Mire legyenek helyettesítve az ismeretlen, vagy a whitespace karakterek.
	 *
	 * @param string $defaultReplacement Helyettesítő string
	 * @return string Aktuális helyettesítő értéke.
	 */
	public static function defaultReplacement($defaultReplacement=null)
	{
		if (!is_null($defaultReplacement))
		{
			self::$_defaultReplacement = (string)$defaultReplacement;
		}
		return self::$_defaultReplacement;
	}	

} //osztály vége
?>