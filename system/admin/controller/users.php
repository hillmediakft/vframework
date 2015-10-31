<?php 

class Users extends Controller {

	function __construct()
	{
		parent::__construct();
        Auth::handleLogin();
		$this->loadModel('users_model');
	}

	public function index()
	{
	// adatok bevitele a view objektumba
        
		$this->view->title = 'Users oldal';
		$this->view->description = 'Users oldal description';
		
		// az oldalspecifikus css linkeket berakjuk a view objektum css_link tulajdonságába (ami egy tömb)
		// a make_link() metódus az anyakontroller metódusa (így egyszerűen meghívható bármelyik kontrollerben)
		$this->view->css_link[] = $this->make_link('css', ADMIN_ASSETS, 'plugins/select2/select2.css');
		$this->view->css_link[] = $this->make_link('css', ADMIN_ASSETS, 'plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css');
		
		// az oldalspecifikus javascript linkeket berakjuk a view objektum js_link tulajdonságába (ami egy tömb)
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/select2/select2.min.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/datatables/media/js/jquery.dataTables.min.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/bootbox/bootbox.min.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'datatable.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/users.js');
		
        // userek adatainak lekérdezése
        $this->view->all_user = $this->users_model->all_user();	
		
//$this->view->debug(true);	

		// template betöltése
		$this->view->render('users/tpl_users');
	}

	
	/**
	 *	Új felhasználó hozzáadása oldal
	 */
	public function new_user()
	{
		// új user hozzáadása
		if(isset($_POST['submit_new_user'])) {
			$result = $this->users_model->new_user();
			
			if($result) {
				Util::redirect('users');
			}
			else {
				Util::redirect('users/new_user');
			}
		}

	// HTML oldal megjelenítése
		// adatok bevitele a view objektumba
		$this->view->title = 'Új felhasználó oldal';
		$this->view->description = 'Új felhasználó description';
		// css linkek generálása
		$this->view->css_link[] = $this->make_link('css', ADMIN_ASSETS, 'plugins/bootstrap-fileupload/bootstrap-fileupload.css');
		$this->view->css_link[] = $this->make_link('css', ADMIN_ASSETS, 'plugins/croppic/croppic.css');
		// js linkek generálása
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/croppic/croppic.min.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/bootstrap-fileupload/bootstrap-fileupload.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/jquery-validation/dist/jquery.validate.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/jquery-validation/dist/additional-methods.min.js');
		//$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'form-validation.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/new_user.js');
		
		// template betöltése
		$this->view->render('users/tpl_new_user');
	}
	
	
	/**
	 * Felhasználó adatainak megjelenítése és módosítása
     *
	 * A metódusnak szüksége van egy user id-jére amit módosítani akarunk ($this->registry->params['id'])
	 */
	public function profile()
	{
		if(isset($_POST['submit_edit_user'])) {
            
			$result = $this->users_model->edit_user($this->registry->params['id']);
			
			if($result) {
				Util::redirect('users');
			}
			else {
				Util::redirect('users/profile/' . $this->registry->params['id']);
			}
		}
		
		// adatok bevitele a view objektumba
		$this->view->title = 'Profilom oldal';
		$this->view->description = 'Profilom description';
		// css linkek generálása
		$this->view->css_link[] = $this->make_link('css', ADMIN_ASSETS, 'plugins/bootstrap-fileupload/bootstrap-fileupload.css');
		$this->view->css_link[] = $this->make_link('css', ADMIN_ASSETS, 'plugins/croppic/croppic.css');
		// js linkek generálása
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/croppic/croppic.min.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/bootstrap-fileupload/bootstrap-fileupload.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/jquery-validation/dist/jquery.validate.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/jquery-validation/dist/additional-methods.min.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/profile.js');
        
		// visszadja a bejelentkezett user adatait egy tömbbe (id, név, telefon, password... stb.)
		$this->view->data_arr = $this->users_model->user_data_query($this->registry->params['id']);
		
	// $this->view->debug(true);
		
		// template betöltése
		$this->view->render('users/tpl_profile');
	}
	
	
	public function user_roles()
	{
		// adatok bevitele a view objektumba
		$this->view->title = 'Felhasználói csoportok oldal';
		$this->view->description = 'Felhasználói csoportok description';
		$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/common.js');
		// template betöltése
		$this->view->render('users/tpl_user_roles');
	}
	
	
	public function edit_roles()
	{
		// adatok bevitele a view objektumba
		$this->view->title = 'Felhasználói jogosultságok szerkesztése oldal';
		$this->view->description = 'Felhasználói jogosultságok szerkesztése description';
		$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/common.js');
		// template betöltése
		$this->view->render('users/tpl_edit_roles');
	}
	
	/**
	 *	User törlése
	 *
	 */
	public function delete_user()
	{
        if(Session::get('user_role_id') == 1){
            $this->users_model->delete_user();
        } else {
            Message::set('error', 'Nincs engedélye a művelet végrehajtásához!');
        }
        
        Util::redirect('users');
	}

	
    /**
	 *	A felhasználó képét tölti fel a szerverre, és készít egy kisebb méretű képet is.
	 *
	 *	Ez a metódus kettő XHR kérést dolgoz fel.
	 *	Meghívásakor kap egy id nevű paramétert melynek értékei upload vagy crop
	 *		upload paraméterrel meghívva: feltölti a kiválasztott képet
	 *		crop paraméterrel meghívva: megvágja az eredeti képet és feltölti	
	 *	(a paraméterek megadása a new_user.js fájlban található: admin/users/user_img_upload/upload vagy admin/user_img_upload/crop)
	 *
	 *	Az user_img_upload() model metódus JSON adatot ad vissza (ezt "echo-za" vissza ez a metódus a kérelmező javascriptnek). 
	 */
	public function user_img_upload()
	{
		if(Util::is_ajax()){
			echo $this->users_model->user_img_upload();
		}
	}

    /**
     * (AJAX) Az users táblában módosítja a user_active mező értékét
     *
     * @return void
     */
    public function change_status() {
        if (Util::is_ajax()) {
            if (isset($_POST['action']) && isset($_POST['id'])){
			
				$id = (int)$_POST['id'];
				$action = $_POST['action'];

				if($action == 'make_active') {
					$result = $this->users_model->change_status_query($id, 1);
					if($result){
						echo json_encode(array(
							"status" => 'success',
							"message" => 'A felhasználó aktiválása megtörtént!'
						)); 	
					} else {
						echo json_encode(array(
							"status" => 'error',
							"message" => 'Adatbázis hiba! A felhasználó státusza nem változott meg!'
						));
					}
				}
				if($action == 'make_inactive') {
					//ha a szuperadmint akarjuk blokkolni 
					if($this->users_model->is_user_superadmin($id)) {
						echo json_encode(array(
							"status" => 'error',
							"message" => 'Szuperadminisztrátor nem blokkolható!'
						));
						return;					
					}
				
					$result = $this->users_model->change_status_query($id, 0);
					if($result){
						echo json_encode(array(
							"status" => 'success',
							"message" => 'A felhasználó blokkolása megtörtént!'
						)); 	
					} else {
						echo json_encode(array(
							"status" => 'error',
							"message" => 'Adatbázis hiba! A felhasználó státusza nem változott meg!'
						));
					}
					
				}
			} else {
				throw new Exception('Nincs $_POST["action"] es $_POST["id"]!!!');
			}
		} else {
			Util::redirect('error');
		}
    }	

}
?>