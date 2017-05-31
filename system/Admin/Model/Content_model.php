<?php
namespace System\Admin\Model;
use System\Core\AdminModel;

class Content_model extends AdminModel {

	protected $table = 'content';

	/**
     * Constructor, létrehozza az adatbáziskapcsolatot
     */
	function __construct()
	{
		parent::__construct();
	}
	
    /**
     * 	Tartalmi elem adatainak lekérdezése
     *
     *  @param  integer    $id
     * 	@param	string     $langcode
     * 	@return	array
     */
    public function findContent($id = null, $langcode = null)
    {
        $this->query->set_columns(
            "content.*,
            content_translation.language_code,
            content_translation.body"
            );    

        $this->query->set_join('inner', 'content_translation', 'content.id', '=', 'content_translation.content_id');

        if(!is_null($id)){
            $this->query->set_where('content.id', '=', $id);
        }

        if (!is_null($langcode)) {
            $this->query->set_where('content_translation.language_code', '=', $langcode);
        }
        
        return $this->query->select();
    }


    /**
     * INSERT
     */
    public function insert($data)
    {
    	return $this->query->insert($data);
    }

	/**
	 * INSERT - content_translation táblába
	 */
	public function insertContentTranslation($translation_data)
	{
		$this->query->set_table('content_translation');
		return $this->query->insert($translation_data);
	}
	
	/**
	 * UPDATE content
	 */
	public function update($id, $data)
	{
		$this->query->set_where('id', '=', $id);
		return $this->query->update($data);
	}

	/**
	 * UPDATE - content_translation táblában
	 */
	public function updateContentTranslation($content_id, $language_code, $translation_data)
	{
		$this->query->set_table('content_translation');
		$this->query->set_where('content_id', '=', $content_id);
		$this->query->set_where('language_code', '=', $language_code);
		return $this->query->update($translation_data);
	}

    /**
     * Megadott nyelvi kódú elem létezését vizsgálja
     *
     * @param integer $content_id
     * @param string $language_code
     * @return bool
     */
    public function checkLangVersion($content_id, $language_code)
    {
    	$this->query->set_table('content_translation');	
    	$this->query->set_columns('COUNT(id) AS counter');
    	$this->query->set_where('content_id', '=', $content_id);
    	$this->query->set_where('language_code', '=', $language_code);
    	$result = $this->query->select();
    	return ($result[0]['counter'] == 1) ? true : false;
    }
	
}
?>