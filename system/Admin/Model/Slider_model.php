<?php
namespace System\Admin\Model;

use System\Core\AdminModel;

class Slider_model extends AdminModel {

    protected $table = 'slider';

    /**
     * Constructor, létrehozza az adatbáziskapcsolatot
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * Slide-ok adatainak lekérdezése, a slider_order sorrend szerint
     * 	
     * @return Array (az összes slide minden adata a "slider_order" szerint rendezve)
     */
    public function allSlides()
    {
        $this->query->set_orderby(array('slider_order'));
        return $this->query->select();
    }

    /**
     * 	Egy slide adatait kérdezi le az adatbázisból
     *
     * 	@param	$id  Int (egy id szám)
     * 	@return	Array
     */
    public function oneSlide($id)
    {
        $this->query->set_where('id', '=', $id);
        $result = $this->query->select();
        return $result[0];
    }

    /**
     * INSERT
     */
    public function insert($data)
    {
        return $this->query->insert($data);        
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
     * DELETE
     */
    public function delete($id)
    {
        return $this->query->delete('id', '=', $id);        
    }

    /**
     * Sliderek sorrendjének módosítása
     *
     * @param integer $id
     * @param integer $new_order
     * @return array 
     */
    public function orderUpdate($id, $new_order)
    {
        $this->query->set_where('id', '=', $id);
        return $this->query->update(array('slider_order' => $new_order));
    }

    /**
     * Egy rekordhoz tartozó Kép nevének lekérdezése
     */
    public function selectPicture($id)
    {
        $this->query->set_columns(array('picture'));
        $this->query->set_where('id', '=', $id);
        $result = $this->query->select();         
        return $result[0]['picture'];
    }

    /**
     * Meghatározott slider_id-hez feltöltött képek közül kiválasztja azt a sort, amelyben a 
     * legmagasabb a sorrend értéke. 
     *
     * @return int az eddigi legnagyobb sorrend szám
     */
    public function slide_highest_order_number()
    {
        $this->query->set_columns('MAX(slider_order)');
        $result = $this->query->select();
        return $result[0]['MAX(slider_order)'];
    }

}
?>