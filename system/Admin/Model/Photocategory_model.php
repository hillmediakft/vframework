<?php 
namespace System\Admin\Model;
use System\Core\AdminModel;
use System\Libs\Config;

class PhotoCategory_model extends AdminModel {

	protected $table = 'photo_category';
	protected $id = 'category_id';

	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *	Visszaadja a photo_category tábla tartalmát
	 *
	 *	@param $id Integer 
	 */
	public function selectOne($id)
	{
		$this->query->set_where('category_id', '=', $id); 
		return $this->query->select(); 
	}

	/**
	 *	Visszaadja a photo_category tábla tartalmát
	 */
	public function selectAll()
	{
		return $this->query->select(); 
	}

	/**
	 * INSERT kategória
	 */
	public function insertCategory($data)
	{
		return $this->query->insert($data);		
	}

	/**
	 * UPDATE kategória
	 */
	public function updateCategory($id, $new_name)
	{
		$this->query->set_where('category_id', '=', $id);
		return $this->query->update(array('category_name' => $new_name));		
	}

	/**
	 * DELETE kategória
	 */
	public function deleteCategory($id)
	{
		return $this->query->delete('category_id', '=', $id);		
	}

}
?>