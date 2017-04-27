<?php
namespace System\Admin\Model;
use System\Core\AdminModel;

class DocumentCategory_model extends AdminModel {

    protected $table = 'document_category';

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 	Visszaadja a document_category tábla tartalmát
     * 	Ha kap egy id paramétert (integer), akkor csak egy sort ad vissza a táblából
     *
     * 	@param $id Integer 
     */
    public function selectCategories($id = null)
    {
        if (!is_null($id)) {
            $id = (int) $id;
            $this->query->set_where('id', '=', $id);
        }
        return $this->query->select();
    }

    /**
     * Kategória törlése
     */
    public function deleteCategory($id)
    {
        return $this->query->delete('id', '=', $id);
    }

    /**
     * Kategória név módosítás
     */
    public function updateCategory($id, $name)
    {
        $this->query->set_where('id', '=', $id);
        return $this->query->update(array('name' => $name));
    }

    /**
     * Kategória hozzáadása
     */
    public function insertCategory($new_name)
    {
        return $this->query->insert(array('name' => $new_name));       
    }   

    /**
     * Ellenőrzi, hogy a kategória törlöhető-e
     * 
     * @param   string  $id
     * @return  bool
     */
    public function is_deletable($id)
    {
        $this->query->set_table(array('documents'));
        $this->query->set_columns('id');
        $this->query->set_where('category_id', '=', $id);
        $result = $this->query->select();
        
        if (empty($result)) {
            return true;
        } else {
            return false;
        }
    }
}
?>