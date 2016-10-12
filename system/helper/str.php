<?php
namespace System\Helper; 
/**
* String helper
*/
class Str
{
	
    /**
     * 	Ékezetes karaktereket és a szóközt cseréli le ékezet nélkülire és alulvonásra
     * 	minden karaktert kisbetűre cserél
     */
    public function stringToSlug($string)
    {
        $accent = array("&", " ", "-", "á", "é", "í", "ó", "ö", "ő", "ú", "ü", "ű", "Á", "É", "Í", "Ó", "Ö", "Ő", "Ú", "Ü", "Ű");
        $no_accent = array('and', '_', '_', 'a', 'e', 'i', 'o', 'o', 'o', 'u', 'u', 'u', 'A', 'E', 'I', 'O', 'O', 'O', 'U', 'U', 'U');
        $string = str_replace($accent, $no_accent, $string);
        $string = strtolower($string);
        return $string;
    }
}
?>