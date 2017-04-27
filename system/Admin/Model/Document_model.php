<?php
namespace System\Admin\Model;
use System\Core\AdminModel;

class Document_model extends AdminModel {

    protected $table = 'documents';

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 	Visszaadja a documents tábla tartalmát
     * 	Ha kap egy id paramétert (integer), akkor csak egy sort ad vissza a táblából
     *
     * 	@param $id Integer 
     */
    public function getDocument($id = null) {
        if (!is_null($id)) {
            $id = (int) $id;
            $this->query->set_where('id', '=', $id);
        }
        return $this->query->select();
    }

    /**
     * 	Visszaadja a documents tábla egy kategóriájának elemeit
     * 	Ha kap egy id paramétert (integer), akkor csak egy sort ad vissza a táblából
     *
     * 	@param $id Integer 
     */
    public function findDocument($id = null)
    {
        $this->query->set_columns(array('documents.id', 'documents.title', 'documents.description', 'documents.file', 'documents.created', 'document_category.name'));
        $this->query->set_join('left', 'document_category', 'documents.category_id', '=', 'document_category.id');
        if (!is_null($id)) {
            $id = (int) $id;
            $this->query->set_where('documents.id', '=', $id);
        }
        return $this->query->select();
    }

    /**
     * 	Visszaadja a documents táblából a category_id oszlopot
     */
    public function categoryCounter()
    {
        $this->query->set_columns('category_id');
        return $this->query->select();
    }

    /**
     * Visszaadja egy dokumentumhoz tartozó file-ok neveit
     */
    public function getDocumentFiles($id)
    {
        $this->query->set_columns(array('file'));
        $this->query->set_where('id', '=', $id);
        $files = $this->query->select();
        if (is_null($files[0]['file']) || $files[0]['file'] === '') {
            return array();
        } else {
            return json_decode($files[0]['file']);
        }
    }

    /**
     * DELETE
     *
     * @param integer
     */
    public function delete($id)
    {
        return $this->query->delete('id', '=', $id);
    }

    /**
     * UPDATE
     */
    public function update($id, $data)
    {
        $this->query->set_where('id', '=', $id);
        return $this->query->update($data);
    }

    /**
     * INSERT
     */
    public function insert($data)
    {
        return $this->query->insert($data);
    }

}
?>