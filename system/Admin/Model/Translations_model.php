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
	
	public function get_translations()
	{
        $this->query->set_orderby(array('code'));
        $result = $this->query->select();

        $grouped_result = $this->group_array_by_field($result);
        return $grouped_result;
	}
	

	public function updateTrans($id, $column, $text)
	{
		$this->query->set_where('id', '=', $id);
		return $this->query->update(array($column => $text));
	}
        
    public function group_array_by_field($old_arr)
    {
        $arr = array();
        foreach ($old_arr as $key => $item) {
            if (array_key_exists('category', $item))
                $arr[$item['category']][$key] = $item;
        }
        return $arr;
    }        

}
?>