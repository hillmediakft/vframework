<?php
namespace System\Admin\Model;

use System\Core\AdminModel;

class Client_model extends AdminModel {

    protected $table = 'clients';

    /**
     * Constructor, létrehozza az adatbáziskapcsolatot
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * 	Egy kolléga minden "nyers" adatát lekérdezi
     * 	A kolléga módosításához kell (itt az id-kre van szükség, és nem a hozzájuk tartozó névre)	
     */
    public function oneClient($id) {
        $id = (int) $id;
        $this->query->set_where('id', '=', $id);
        $result = $this->query->select();
        return $result[0];
    }

    /**
     * 	Egy partner minden "nyers" adatát lekérdezi
     * 	A partner módosításához kell (itt az id-kre van szükség, és nem a hozzájuk tartozó névre)	
     */
    public function allClient()
    {
        $this->query->set_orderby(array('client_order'));
        return $this->query->select();
    }

    /**
     * INSERT partner
     */
    public function insert($data)
    {
        return $this->query->insert($data);
    }

    /**
     * UPDATE partner
     */
    public function update($id, $value)
    {
        $this->query->set_where('id', '=', $id);
        $result = $this->query->update($value);
    }

    /**
     * DELETE partner
     */
    public function delete($id)
    {
        return $this->query->delete('id', '=', $id);        
    }

    /**
     * Lekérdezzük a törlendő kép nevét, hogy törölhessük a szerverről
     */
    public function selectPicture($id)
    {
        $this->query->set_columns(array('photo'));
        $this->query->set_where('id', '=', $id);
        $result = $this->query->select();
        return $result[0]['photo'];
    }

    /**
     * Meghatározott slider_id-hez feltöltött képek közül kiválasztja azt a sort, amelyben a 
     * legmagasabb a sorrend értéke. 
     *
     * @return int az eddigi legnagyobb sorrend szám
     */
    public function highest_order_number()
    {
        $this->query->set_columns('MAX(client_order)');
        $result = $this->query->select();
        return $result[0]['MAX(client_order)'];
    }

    /**
     * Sorrend módosítása
     */
    public function order($id, $new_order)
    {
        $this->query->set_where('id', '=', $id);
        $this->query->update(array('client_order' => $new_order));        
    }

}
?>