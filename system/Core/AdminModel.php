<?php
namespace System\Core;
use System\Libs\Config;
 
class AdminModel extends Model {

	protected $lang;
	protected $langs;

	function __construct()
	{
		parent::__construct();

		$this->lang = LANG;
		$this->langs = Config::get('allowed_languages');
	}

	/**
     * Megadott nyelvi kódú elem létezését vizsgálja egy táblában
     *
     * @param string $table - tábla neve
     * @param string $column - oszlop neve (blog_id, category_id stb.)
     * @param integer $id
     * @param string $langcode
     * @return bool
     */
    public function _checkLangVersion($table, $column, $id, $langcode)
    {
        $this->query->set_table($table);
    	$this->query->set_columns('COUNT(id) AS counter');
    	$this->query->set_where($column, '=', $id);
    	$this->query->set_where('language_code', '=', $langcode);
    	$result = $this->query->select();
    	return ($result[0]['counter'] == 1) ? true : false;
    }

}
?>