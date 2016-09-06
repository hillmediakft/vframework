<?php

class Util {

    /**
     * Redirects to another page.
     *
     * @param string $location The path to redirect to
     * @param int $status Status code to use
     * @return bool False if $location is not set
     */
    public static function redirect($location, $status = 302) {
        $registry = Registry::get_instance();
        $request = $registry->request;

        if ($location == '') {
            header("Location: " . $request->get_uri('site_url'), true, $status);
            exit;
        }

        header("Location: " . $request->get_uri('site_url') . $location, true, $status);
        exit;
    }

    /**
     * 	File törlése
     *
     * 	@param	$filename	a törlendő file elérésiútja mappa/filename.ext
     * 	@return	true|false
     */
    public static function del_file($filename)
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
     * Egy kép elérési útvonalából generál egy elérési útvonalat a bélyegképéhez
     * Minta: path/to/file/filename.jpg -> path/to/file/filename_thumb.jpg
     * 
     * @param   string  $path (a file elérési útja)
     * @param   bool    $dir (hozzárak az elérési út végéhez egy mappát, amit a $suffix paraméterben kap meg a metódus)
     * @param   string  $suffix (fájl nevének utótagja)
     * @return  string  a bélyegkép elérési útvonala
     */
    public static function thumb_path($path, $dir = false, $suffix = 'thumb')
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
     * Spamektől védett e-mail linket generál Javascripttel
     *
     * @param	string	$email: e-mail cím
     * @param	string	$title: title
     * @param	mixed	$attributes: attribútumok
     * @return	string
     */
    public static function safe_mailto($email, $title = '', $attributes = '') {
        $title = (string) $title;

        if ($title == "") {
            $title = $email;
        }

        for ($i = 0; $i < 16; $i++) {
            $x[] = substr('<a href="mailto:', $i, 1);
        }

        for ($i = 0; $i < strlen($email); $i++) {
            $x[] = "|" . ord(substr($email, $i, 1));
        }

        $x[] = '"';

        if ($attributes != '') {
            if (is_array($attributes)) {
                foreach ($attributes as $key => $val) {
                    $x[] = ' ' . $key . '="';
                    for ($i = 0; $i < strlen($val); $i++) {
                        $x[] = "|" . ord(substr($val, $i, 1));
                    }
                    $x[] = '"';
                }
            } else {
                for ($i = 0; $i < strlen($attributes); $i++) {
                    $x[] = substr($attributes, $i, 1);
                }
            }
        }

        $x[] = '>';

        $temp = array();
        for ($i = 0; $i < strlen($title); $i++) {
            $ordinal = ord($title[$i]);

            if ($ordinal < 128) {
                $x[] = "|" . $ordinal;
            } else {
                if (count($temp) == 0) {
                    $count = ($ordinal < 224) ? 2 : 3;
                }

                $temp[] = $ordinal;
                if (count($temp) == $count) {
                    $number = ($count == 3) ? (($temp['0'] % 16) * 4096) + (($temp['1'] % 64) * 64) + ($temp['2'] % 64) : (($temp['0'] % 32) * 64) + ($temp['1'] % 64);
                    $x[] = "|" . $number;
                    $count = 1;
                    $temp = array();
                }
            }
        }

        $x[] = '<';
        $x[] = '/';
        $x[] = 'a';
        $x[] = '>';

        $x = array_reverse($x);
        ob_start();
        ?><script type="text/javascript">
            //<![CDATA[
            var l = new Array();
        <?php
        $i = 0;
        foreach ($x as $val) {
            ?>l[<?php echo $i++; ?>] = '<?php echo $val; ?>';<?php } ?>

                for (var i = l.length - 1; i >= 0; i = i - 1) {
                    if (l[i].substring(0, 1) == '|')
                        document.write("&#" + unescape(l[i].substring(1)) + ";");
                    else
                        document.write(unescape(l[i]));
                }
                //]]>
        </script><?php
        $buffer = ob_get_contents();
        ob_end_clean();
        return $buffer;
    }

    /**
     * 	Ékezetes karaktereket és a szóközt cseréli le ékezet nélkülire és alulvonásra
     * 	minden karaktert kisbetűre cserél
     */
    public static function string_to_slug($string) {
        $accent = array("&", " ", "-", "á", "é", "í", "ó", "ö", "ő", "ú", "ü", "ű", "Á", "É", "Í", "Ó", "Ö", "Ő", "Ú", "Ü", "Ű");
        $no_accent = array('and', '_', '_', 'a', 'e', 'i', 'o', 'o', 'o', 'u', 'u', 'u', 'A', 'E', 'I', 'O', 'O', 'O', 'U', 'U', 'U');
        $string = str_replace($accent, $no_accent, $string);
        $string = strtolower($string);
        return $string;
    }

    /**
     * A fájl nevéhez hozzáilleszt egy query stringet (pl: style.css?v=2314564321
     * A szám a fájl utolsó módosításának timestamp-je
     *  
     * @param   string  $uri  a file elérési útvonala pl.: valami,hu/public/site_assets/css/style.css
     * @return  string  a fájl verzióval ellátott elérési útvonala
     */
    public static function auto_version($uri) {
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