<?php
namespace System\Admin\Model;
use System\Core\AdminModel;

class Terms_translation_model extends AdminModel {

    protected $table = 'terms_translation';

    /**
     * Constructor, létrehozza az adatbáziskapcsolatot
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Új adatok a terms_translation táblába
     */
    public function insertTranslation($last_insert_id, $langcode, $name)
    {
        return $this->query->insert(array(
            'term_id' => $last_insert_id,
            'language_code' => $langcode,
            'text' => $name
            ));
    }

    /**
     * Címke nevének módosítása a terms_translation táblában
     *
     * @param integer   $term_id
     * @param array     $text
     * @return integer || false
     */
    public function updateTranslation($term_id, $langcode, $text)
    {
        $this->query->set_where('term_id','=', $term_id);
        $this->query->set_where('language_code','=', $langcode);
        return $this->query->update(array('text' => $text));
    }

    /**
     * Megadott nyelvi kódú elem létezését vizsgálja
     *
     * @param integer $term_id
     * @param string $langcode
     * @return bool
     */
    public function checkLangVersion($term_id, $langcode)
    {
        $this->query->set_columns('COUNT(id) AS counter');
        $this->query->set_where('term_id', '=', $term_id);
        $this->query->set_where('language_code', '=', $langcode);
        $result = $this->query->select();
        return ($result[0]['counter'] == 1) ? true : false;
    }

}
?>