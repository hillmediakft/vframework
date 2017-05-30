<?php
namespace System\Core;
use System\Libs\DI;

class SiteModel extends Model {

    /**
     * Minden site modelben elérhető, és a nyelvi kódot tartalmazza
     */
    protected $lang = LANG;

    function __construct() {
        parent::__construct();
    }

    /**
     * 	Oldal tartalmak lekérdezése (title, body, metatitle, metadescription, metakeywords)
     *
     * 	@param	string	$page_name (az oldal friendlyurl-je a pages táblában)
     * 	@return array
     */
    public function getPageData_OLD($page_name)
    {
        $this->query->set_table(array('pages'));
        $this->query->set_columns('*');
        $this->query->set_where('friendlyurl', '=', $page_name);
        $result = $this->query->select();
        return $result[0];
    }


    /**
     *  Egy oldal adatait kérdezi le az adatbázisból (pages tábla)
     *
     *  @param  integer $id
     *  @return array
     */
    public function getPageData($page_name)
    {
        $this->query->set_table(array('pages'));
        $this->query->set_columns(
            "pages.*,
            pages_translation.language_code,
            pages_translation.body,
            pages_translation.metatitle,
            pages_translation.metadescription,
            pages_translation.metakeywords"
            );

        $this->query->set_join('inner', 'pages_translation', 'pages.id', '=', 'pages_translation.page_id');
        $this->query->set_where('pages.friendlyurl', '=', $page_name);
        $this->query->set_where('language_code', '=', $this->lang);
        $result = $this->query->select();
            
        if (empty($result)) {
            throw new \Exception("Nincs az adatbazisban a " . $page_name . " nevu oldal");
            die;
        }    

        return $result[0];
    }


}
?>