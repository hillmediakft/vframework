<?php
namespace System\Helper; 
/**
* String helper
*/
class Str
{
	
    /**
     * 	Ékezetes karaktereket és a szóközt cseréli le ékezet nélkülire és kötőjelre
     * 	minden karaktert kisbetűre cserél
     */
    public function stringToSlug($string)
    {
        $accent = array(")", "(", "#", "@", ",", "?", "!", ".", ":", "&", " ", "_", "á", "é", "í", "ó", "ö", "ő", "ú", "ü", "ű", "Á", "É", "Í", "Ó", "Ö", "Ő", "Ú", "Ü", "Ű");
        $no_accent = array('-', '-', '-', '', '', '', '', '', '', '-', '-', '-', 'a', 'e', 'i', 'o', 'o', 'o', 'u', 'u', 'u', 'A', 'E', 'I', 'O', 'O', 'O', 'U', 'U', 'U');
        $string = str_replace($accent, $no_accent, $string);
        $string = strtolower($string);
        return $string;
    }

    /**
     *  Ékezetes karaktereket és a szóközt cseréli le ékezet nélkülire és kötőjelre
     *  minden karaktert kisbetűre cserél
     */
    public function stringToSlugAdvanced($name)
    {
        $_abc = array(
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
            '/ƒ/' => 'f',
            '/\<|\>|\#|\@|\{|\}|\(|\)|\[|\]|\$|\_|\?|\.|\,|\:|\;|\%|\!|\~|\"|\'|\^|\ˇ|\=|\/|\+|\-|\*|\`|\§|\|/' => ''
        );

        $_defaultReplacement = '-';

        $replacement = preg_quote($_defaultReplacement, '/');

        $merge = array(
            '/[^\s\w\d]/mu' => ' ',
            '/\\s+/' => $_defaultReplacement,
            sprintf('/^[%s]+|[%s]+$/', $replacement, $replacement) => '',
        );

        $map = $_abc + $merge;
        
        $newname = preg_replace(array_keys($map), array_values($map), $name);
        $newname = mb_strtolower($newname);
        return $newname;
    }


    /**
     * Egy szövegből az elejétől kezdődően adott karakterszámú rész ad vissza szóra kerekítve
     *  
     * @param   string  $string  szöveg
     * @param   int  $char  karakterek száma
     * @return  string  a levágott szöveg
     */
    public function substrWord($string, $char)
    {
        $s = mb_substr($string, 0, $char, 'UTF-8');
        return substr($s, 0, strrpos($s, ' '));
    }

    /**
     * Egy szövegből az elejétől adott számú mondatot ad vissza
     *  
     * @param   string  $body  szöveg
     * @param   int  $sentencesToDisplay  a mondatk száma
     * @return  string  a levágott szöveg
     */
    public function sentenceTrim($body, $sentencesToDisplay = 1)
    {
        $nakedBody = preg_replace('/\s+/', ' ', strip_tags($body));
        $sentences = preg_split('/(\.|\?|\!)(\s)/', $nakedBody);

        if (count($sentences) <= $sentencesToDisplay)
            return $nakedBody;

        $stopAt = 0;
        foreach ($sentences as $i => $sentence) {
            $stopAt += strlen($sentence);

            if ($i >= $sentencesToDisplay - 1)
                break;
        }

        $stopAt += ($sentencesToDisplay * 2);
        return trim(substr($nakedBody, 0, $stopAt));
    }

    /**
     * utf-8 karaktereket latin2-re cserél
     * @param string $str
     */
    public function utf8ToLatin2Hun($str)
    {
        return str_replace( array("\xc3\xb6", "\xc3\xbc", "\xc3\xb3", "\xc5\x91", "\xc3\xba", "\xc3\xa9", "\xc3\xa1", "\xc5\xb1", "\xc3\xad", "\xc3\x96", "\xc3\x9c", "\xc3\x93", "\xc5\x90", "\xc3\x9a", "\xc3\x89", "\xc3\x81", "\xc5\xb0", "\xc3\x8d"), array("\xf6", "\xfc", "\xf3", "\xf5", "\xfa", "\xe9", "\xe1", "\xfb", "\xed", "\xd6", "\xdc", "\xd3", "\xd5", "\xda", "\xc9", "\xc1", "\xdb", "\xcd"), $str );
    }

}
?>