<?php 
namespace System\Helper;
/**
* Url és link helper
*/
class Url
{
	
    /**
     * Egy kép elérési útvonalából generál egy elérési útvonalat a bélyegképéhez
     * Minta: path/to/file/filename.jpg -> path/to/file/filename_thumb.jpg
     * 
     * @param   string  $path (a file elérési útja)
     * @param   bool    $dir (hozzárak az elérési út végéhez egy mappát, amit a $suffix paraméterben kap meg a metódus)
     * @param   string  $suffix (fájl nevének utótagja)
     * @return  string  a bélyegkép elérési útvonala
     */
    public function thumbPath($path, $dir = false, $suffix = 'thumb')
    {
        $path_parts = pathinfo($path);
        $dirname = (isset($path_parts['dirname'])) ? $path_parts['dirname'] : '';
        $filename = (isset($path_parts['filename'])) ? $path_parts['filename'] : '';
        $extension = (isset($path_parts['extension'])) ? $path_parts['extension'] : '';

        if (!$dir) {
            if (($dirname == '.') || ($dirname == '\\')) {
                $new_path = $filename . '_' . $suffix . '.' . $extension;
            } else {
                $new_path = $dirname . '/' . $filename . '_' . $suffix . '.' . $extension;
            }
        } else {
            if (($dirname == '.') || ($dirname == '\\')) {
                $new_path = $suffix . '/' . $filename . '_' . $suffix . '.' . $extension;
            } else {
                $new_path = $dirname . '/' . $suffix . '/' . $filename . '_' . $suffix . '.' . $extension;
            }
        }
        return $new_path;
    }

    /**
     * A fájl nevéhez hozzáilleszt egy query stringet (pl: style.css?v=2314564321
     * A szám a fájl utolsó módosításának timestamp-je
     *  
     * @param   string  $uri  a file elérési útvonala pl.: valami.hu/public/site_assets/css/style.css
     * @return  string  a fájl verzióval ellátott elérési útvonala
     */
    public function autoVersion($uri)
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