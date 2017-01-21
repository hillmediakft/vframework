<?php
namespace System\Core;

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
    public function getPageData($page_name)
    {
        $this->query->set_table(array('pages'));
        $this->query->set_columns('*');
        $this->query->set_where('friendlyurl', '=', $page_name);
        $result = $this->query->select();
        return $result[0];
    }

}
?>