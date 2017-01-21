<?php
namespace System\Libs;
use \PDO;

class Language {
    
    private static $translations = array(); //a fordítások tömbje

    /**
     * Language fájl betöltése az adatbázisból
     *
     * @param   string  $lang_code az aktuális nyelvi kód
     * @param   object  db kapcsolat objektum
     * @return  void
     */
    public static function init($lang_code, $connect)
    {
        //$connect = DI::get('connect');
        $sth = $connect->query("SELECT `code`,`" . $lang_code . "` FROM `translations`");
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            self::$translations[$row['code']] = $row[$lang_code];
        }
/*
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            self::$translations[$row['code']] = $row[$lang_code];
        }
*/
    }

    /**
     * fordítás kód alapján
     *
     * @param   string  $code a szöveg elem kódja
     * @return  string   fordítás
     */
    public static function get($code)
    {
        return self::$translations[$code];
    }

}
?>