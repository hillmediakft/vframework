<?php
namespace System\Site\Model;
use System\Core\SiteModel;
use System\Libs\DI;

class Taxonomy_model extends SiteModel {

    protected $table = 'taxonomy';

    /**
     * Constructor, létrehozza az adatbáziskapcsolatot
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Lekérdezi a content id-hez tartozó term id-ket - pl egy oldalhoz milyen szavak tartoznak
     * 
     * @param int $content_id a tartalom id-je (pl.: egy page id-je)	
     * @return Array a szavak id-inek tömbje
     */
    public function getTermsByContentId($content_id)
    {
        $this->query->set_columns('term_id');
        $this->query->set_where('content_id', '=', $content_id);
        return $this->query->select();
    }

    /**
     * Lekérdezi azokat id-ket, amelyek adott content_id és content_type_id tartozik - pl. egy szolgáltatás 
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
    public function getContentIdByTermIdAndContentType($term_ids, $content_type_id)
    {
        $arr_helper = DI::get('arr_helper');

        $term_ids = $arr_helper->convertArrayToOneDimensional($term_ids);
        
        $this->query->set_columns('content_id');
        $this->query->set_where('term_id', 'in', $term_ids);
        $this->query->set_where('content_type_id', '=', $content_type_id);
        $result = $this->query->select();

        if(count($result) == 1) {
            return array($result[0]['content_id']);
        } elseif(count($result) > 1) {
            return $arr_helper->convertArrayToOneDimensional($result);
        } else  {
            return array();
        }
    }

}
?>