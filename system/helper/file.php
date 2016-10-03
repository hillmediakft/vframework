<?php
namespace System\Helper;
/**
* File-műveleteket végző metódusok
*/
class File {
	
	public function __construct() { }

	/**
     * 	File törlése
     *
     * 	@param	$filename	a törlendő file elérési útja mappa/filename.ext
     * 	@return	true|false
     */
    public function delete($filename)
    {
        if (is_file($filename)) {
            //ha a file megnyitható és írható
            if (is_writable($filename)) {
                unlink($filename);
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * A fájl nevéhez hozzáilleszt egy query stringet (pl: style.css?v=2314564321
     * A szám a fájl utolsó módosításának timestamp-je
     *  
     * @param   string  $uri  a file elérési útvonala pl.: valami.hu/public/site_assets/css/style.css
     * @return  string  a fájl verzióval ellátott elérési útvonala
     */
    public function auto_version($uri)
    {
        if (substr($uri, 0, 1) == "/") {
            // relatív URI
            $fname = $_SERVER["DOCUMENT_ROOT"] . $uri;
        } else {
            // abszolút URI
            $fname = $uri;
        }
        $ftime = filemtime($fname);
        return $uri . '?v=' . $ftime;
    }    



}
?>