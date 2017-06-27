<?php
namespace System\Admin\Model;
use System\Core\AdminModel;
use System\Libs\DI;

class Taxonomy_model extends AdminModel {

    protected $table = "taxonomy";

    function __construct() {
        parent::__construct();
    }

    /**
     * INSERT a taxonomy táblába
     */
    public function insert($content_id, $terms, $content_type_id)
    {
        $data = array(
            'content_id' => $content_id,
            'content_type_id' => $content_type_id
        );
        if (is_array($terms)) {
            foreach ($terms as $key => $term) {
                $data['term_id'] = $term;
                $this->query->insert($data);
            }
        } else {
            $data['term_id'] = $terms;
            $this->query->insert($data);
        }
    }

    /**
     * Lekérdezi a content id-hez tartozó term id-ket
     * 	
     * @return Array (az összes slide minden adata a "slider_order" szerint rendezve)
     */
    public function getTermsByContentId($content_id)
    {
        $this->query->set_columns('term_id');
        $this->query->set_where('content_id', '=', $content_id);
        return $this->query->select();
    }

    /**
     * Lekérdezi azokaz id-ket, amelyek adott content_id és content_type_id tartozik - pl. egy szolgáltatás 
     * 	
     * @param int vagy array $content_id a tartalom id-je (integer vagy tömb)
     * @param int $content_type_id a tartalom típus ide-je pl.: 1 (blog)   
     * @return Array törlendő címkék tömbje
     */
    public function getTermsToDelete($content_id, $content_type_id)
    {
        $this->query->set_columns('id');
        if (!is_array($content_id)) {
            $this->query->set_where('content_id', '=', $content_id);
        } else {
            $this->query->set_where('content_id', 'in', $content_id);
        }
        $this->query->set_where('content_type_id', '=', $content_type_id);
        return $this->query->select();
    }

    /**
     * Lekérdezi azokaz id-ket, amelyek adott content_id és content_type_id tartozik - pl. egy szolgáltatás 
     * 	
     * @return Array (az összes slide minden adata a "slider_order" szerint rendezve)
     */
    public function getTermsByContentIdAndContentTypeId($content_id, $content_type_id)
    {
        $this->query->set_columns('term_id');
        $this->query->set_where('content_id', '=', $content_id);
        $this->query->set_where('content_type_id', '=', $content_type_id);
        return $this->query->select();
    }

    /**
     * Lekérdezi a content id-hez tartozó term id-ket
     * 	
     * @return Array (az összes slide minden adata a "slider_order" szerint rendezve)
     */
    public function deleteByContentTypeAndId($content_id, $content_type_id)
    {
        $ids = $this->getTermsToDelete($content_id, $content_type_id);
        $ids = DI::get('arr_helper')->convertArrayToOneDimensional($ids);
        
        $this->query->set_where('id', 'in', $ids);
        $this->query->delete();

/*
        foreach ($ids as $id) {
            $this->query->delete('id', '=', $id);
        }
*/
    }

}
?>