<?php
namespace System\Admin\Model;
use System\Core\AdminModel;

class Blog_model extends AdminModel {

	protected $table = 'blog';	

	function __construct()
	{
		parent::__construct();
	}
   
	/**
	 *	Visszaadja a blog tábla egy kategóriájának elemeit
	 *	Ha kap egy id paramétert (integer), akkor csak egy sort ad vissza a táblából
	 *
	 *	@param $id Integer 
	 *	@param $langcode string 
	 */
	public function findBlog($id = null, $langcode = null)
	{
		$this->query->set_table('blog');
		$this->query->set_columns(
			"blog.*,
			 blog_translation.title,
			 blog_translation.body,
			 blog_translation.language_code,
			 blog_category_translation.category_name"
			);

		$this->query->set_join('inner', 'blog_translation', 'blog.id', '=', 'blog_translation.blog_id'); 
		$this->query->set_join('left', 'blog_category_translation', '(blog.category_id = blog_category_translation.category_id AND blog_category_translation.language_code = blog_translation.language_code)'); 
		$this->query->set_orderby('blog.id');
		
		if (!is_null($langcode)) {
			$this->query->set_where('blog_translation.language_code', '=', $langcode);
		}
		
		if(!is_null($id)){
			$this->query->set_where('blog.id', '=', $id); 
		}
		
//$this->query->debug();
		return $this->query->select();


	}

	/**
	 *	Visszaadja a blog táblából a blog_category oszlopot
	 */
	public function categoryCounter_OLD()
	{
		$this->query->set_columns('category_id');
		return $this->query->select(); 
	}
   

	/**
	 *	Visszaadja a blog táblából hogy egy blog kategóriához hány elem tartozik
	 */
	public function categoryCounter()
	{
		$this->query->set_columns('category_id, COUNT(category_id) AS item_numbers');
		$this->query->set_groupby('category_id');
		$result = $this->query->select();

		$temp = array();
		foreach ($result as $key => $value) {
			$temp[$value['category_id']] = $value['item_numbers']; 
		}

		return $temp;
	}


    /**
     * Rekord INSERT
     */
   	public function insert($data)
	{
		return $this->query->insert($data);
	}

	/**
	 * Rekord UPDATE
	 */
	public function update($id, $data)
	{
		$this->query->set_where('id','=', $id);
		return $this->query->update($data);
	}
	
	/**
	 * Rekord törlése id alapján
	 */
	public function delete($value)
	{
		return $this->query->delete('id', '=', $value);
	}

 	/**
 	 * Blog törlése blog_category oszlop alapján
 	 */
	public function deleteWhereCategory($id)
 	{
		return $this->query->delete('category_id', '=', $id);
 	}

	/**
	 * Egy bloghoz tartozó kép nevét adja vissza
	 */
	public function findPicture($id)
	{
		$this->query->set_columns(array('picture'));
		$this->query->set_where('id', '=', $id);
		$result = $this->query->select();
		return $result[0]['picture'];
	}

	/**
	 * Visszaadja a képek nevét, ahol a kategória = a $category paraméterrel
	 */
	public function findPictureWhereCategory($category)
	{
		$this->query->set_columns(array('picture'));
		$this->query->set_where('category_id', '=', $category);
		return $this->query->select();
	}

    /**
     * 	Status mező értékét módosítja
     * 	
     * 	@param	integer	$id	
     * 	@param	integer	$data (0 vagy 1)	
     * 	@return integer
     */
    public function changeStatus($id, $data)
    {
        $this->query->set_where('id', '=', $id);
        return $this->query->update(array('status' => $data));
    }

}
?>