<?php 
namespace System\Admin\Model;
use System\Core\AdminModel;

class Translations_model extends AdminModel {

	protected $table = 'translations';

	/**
     * Constructor, létrehozza az adatbáziskapcsolatot
     */
	function __construct()
	{
		parent::__construct();
	}

    /**
     * Translation adatok viszzaadása 
     */            
	public function findTranslations()
	{
        $this->query->set_columns(
            "translations.*,
            translations_content.language_code,
            translations_content.text"
            );

        $this->query->set_join('inner', 'translations_content', 'translations.id = translations_content.translation_id');

        $this->query->set_orderby(array('translations.code'));
        return $this->query->select();
	}
    
    /**
     * INSERT - translations táblában
     */
    public function insert($data)
    {
    	return $this->query->insert($data);
    }

    /**
     * INSERT - translations_content táblában
     */
    public function insertContent($data)
    {
        $this->query->set_table('translations_content');
        return $this->query->insert($data);
    }

    /**
     * UPDATE - translations_content táblában
     */
    public function updateContent($translation_id, $language_code, $text)
    {
        $this->query->set_table('translations_content');    
        $this->query->set_where('translation_id', '=', $translation_id);
        $this->query->set_where('language_code', '=', $language_code);
        return $this->query->update(array('text' => $text));
    }

    /**
     * Megadott nyelvi kódú elem létezését vizsgálja
     *
     * @param integer $translation_id
     * @param string $langcode
     * @return bool
     */
    public function checkLangVersion($translation_id, $langcode)
    {
    	$this->query->set_table('translations_content');
        $this->query->set_columns('COUNT(id) AS counter');
        $this->query->set_where('translation_id', '=', $translation_id);
        $this->query->set_where('language_code', '=', $langcode);
        $result = $this->query->select();
        return ($result[0]['counter'] == 1) ? true : false;
    }

}
?>