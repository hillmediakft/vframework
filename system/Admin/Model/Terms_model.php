<?php
namespace System\Admin\Model;
use System\Core\AdminModel;

class Terms_model extends AdminModel {

    protected $table = 'terms';

    /**
     * Constructor, létrehozza az adatbáziskapcsolatot
     */
    function __construct() {
        parent::__construct();
    }

    /**
     *  A terms és terms_translation táblából kérdezi le az adatokat
     *
     *  @param integer  $id  
     *  @param string   $lang  - csak a megadott nyelvű elemet adja vissza
     */
    public function findTerms($id = null, $lang = null)
    {
        $this->query->set_columns(
            "terms_translation.term_id AS id,
            terms_translation.language_code,
            terms_translation.text"
            );

        $this->query->set_join('inner', 'terms_translation', 'terms.id', '=', 'terms_translation.term_id'); 

        if (!is_null($lang)) {
            $this->query->set_where('terms_translation.language_code', '=', $lang); 
        }
        
        if(!is_null($id)){
            $this->query->set_where('terms.id', '=', $id); 
        }
        
        return $this->query->select(); 
    }

    /**
     * 	Címke hozzáadása (új rekord a terms táblába)
     */
    public function insert()
    {
        return $this->query->insert(array('id' => null));
    }

    /**
     * Címke (vagy több címke) törlése
     *
     * @param mixed $id
     */
    public function delete($id)
    {
        /*
        if (is_array($id)) {
            $this->query->set_where('id', 'in', $id);
            return $this->query->delete();
        }
        */
        return $this->query->delete('id', '=', $id);
    }

}
?>