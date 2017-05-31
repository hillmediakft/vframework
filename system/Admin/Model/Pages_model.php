<?php 
namespace System\Admin\Model;
use System\Core\AdminModel;

class Pages_model extends AdminModel {

	protected $table = 'pages';

	/**
     * Constructor, létrehozza az adatbáziskapcsolatot
     */
	function __construct()
	{
		parent::__construct();
	}
	
	public function allPages()
	{
		return $this->query->select(); 
	}

	/**
	 *	Egy oldal adatait kérdezi le az adatbázisból (pages tábla)
	 *
	 *	@param	integer $id
	 *	@return	array
	 */
	public function findPage($id = null, $language_code = null)
	{
		$this->query->set_columns(
			"pages.*,
			pages_translation.language_code,
			pages_translation.body,
			pages_translation.metatitle,
			pages_translation.metadescription,
			pages_translation.metakeywords"
			);

		$this->query->set_join('inner', 'pages_translation', 'pages.id', '=', 'pages_translation.page_id');

		if (!is_null($id)) {
			$this->query->set_where('pages.id', '=', $id);
		}
		if (!is_null($language_code)) {
			$this->query->set_where('pages_translation.language_code', '=', $language_code);
		}

		return $this->query->select();
	}


	/**
	 * INSERT - pages_translation táblába
	 */
	public function insert($data)
	{
		return $this->query->insert($data);
	}

	/**
	 * INSERT - pages_translation táblába
	 */
	public function insertContent($translation_data)
	{
		$this->query->set_table('pages_translation');
		return $this->query->insert($translation_data);
	}

	/**
	 * UPDATE - pages táblában
	 */
	public function update($id, $data)
	{
		$this->query->set_where('id', '=', $id);
		return $this->query->update($data);		
	}

	/**
	 * UPDATE - pages_translation táblában
	 */
	public function updateContent($page_id, $language_code, $translation_data)
	{
		$this->query->set_table('pages_translation');
		$this->query->set_where('page_id', '=', $page_id);
		$this->query->set_where('language_code', '=', $language_code);
		return $this->query->update($translation_data);
	}

    /**
     * Megadott nyelvi kódú elem létezését vizsgálja
     *
     * @param integer $page_id
     * @param string $language_code
     * @return bool
     */
    public function checkLangVersion($page_id, $language_code)
    {
    	$this->query->set_columns('COUNT(id) AS counter');
    	$this->query->set_where('page_id', '=', $page_id);
    	$this->query->set_where('language_code', '=', $language_code);
    	$result = $this->query->select();
    	return ($result[0]['counter'] == 1) ? true : false;
    }
		
}
?>