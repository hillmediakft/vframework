<?php 
class Users extends Admin_controller {

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
		if($this->request->has_post('submit_new_user')) {
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
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/jquery-validation/jquery.validate.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/jquery-validation/additional-methods.min.js');
		//$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'form-validation.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/new_user.js');
		
		// template betöltése
		$this->view->render('users/tpl_new_user');
	}
	
	
	/**
	 * Felhasználó adatainak megjelenítése és módosítása
     *
	 * A metódusnak szüksége van egy user id-jére amit módosítani akarunk ($this->request->get_params('id'))
	 */
	public function profile()
	{
		if($this->request->has_post('submit_edit_user')) {
            
			$result = $this->users_model->edit_user($this->request->get_params('id'));
			
			if($result) {
				Util::redirect('users');
			}
			else {
				Util::redirect('users/profile/' . $this->request->get_params('id'));
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
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/jquery-validation/jquery.validate.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_ASSETS, 'plugins/jquery-validation/additional-methods.min.js');
		$this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/profile.js');
        
		// visszadja a bejelentkezett user adatait egy tömbbe (id, név, telefon, password... stb.)
		$this->view->data_arr = $this->users_model->user_data_query($this->request->get_params('id'));
		
	// $this->view->debug(true);
		
		// template betöltése
		$this->view->render('users/tpl_profile');
	}
	
	
    public function user_roles() {
   //     $this->check_access('menu_users_user_roles');
        // adatok bevitele a view objektumba
        $this->view->title = 'Felhasználói csoportok oldal';
        $this->view->description = 'Felhasználói csoportok description';
        $this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/common.js');

        $this->view->roles = $this->users_model->getRoles();
        $this->view->roles_counter = $this->users_model->roles_counter_query();

        // template betöltése
        $this->view->render('users/tpl_user_roles');
    }
	
	
 public function edit_roles() {
        
   //     $this->check_access('action_users_edit_roles');
        
        $role_id = $this->request->get_params('id');
        
        if ($this->request->has_post('submit_edit_roles')) {
            $result = $this->users_model->save_role_permissions($role_id);
                Util::redirect('users/edit_roles/' . $role_id);

        }

        // adatok bevitele a view objektumba
        $this->view->title = 'Felhasználói jogosultságok szerkesztése oldal';
        $this->view->description = 'Felhasználói jogosultságok szerkesztése description';
        $this->view->js_link[] = $this->make_link('js', ADMIN_JS, 'pages/common.js');
        // a szerkesztendő role neve
        $this->view->role = $this->users_model->getRoles($role_id);
        $this->view->role = $this->view->role[0];
        // a szerkesztendő role-hoz tartozó engedélyek
        $this->view->role_permissions = $this->users_model->getRolePerms($role_id);
 
        // template betöltése
        $this->view->render('users/tpl_edit_roles');
    }
	
	/**
	 *	User törlése AJAX-al
	 */
	public function delete_user_AJAX()
	{
        if($this->request->is_ajax()){
	        if(Session::get('user_role_id') == 1){
	        	// a POST-ban kapott user_id egy string ami egy szám vagy számok felsorolása pl.: "23" vagy "12,45,76" 
	        	$id = $this->request->get_post('user_id');
            	$respond = $this->users_model->delete_user_AJAX($id);
        		echo json_encode($respond);
	        } else {
	            echo json_encode(array(
	            	'status' => 'error',
	            	'message' => 'Nincs engedélye a művelet végrehajtásához!'
	            ));
	        }
        }
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
		if( $this->request->is_ajax() ){
			echo $this->users_model->user_img_upload();
		}
	}

    /**
     * (AJAX) Az users táblában módosítja a user_active mező értékét
     *
     * @return void
     */
    public function change_status() {
        if ( $this->request->is_ajax() ) {
            if ( $this->request->has_post('action') && $this->request->has_post('id') ) {
			
				$id = (int)$this->request->get_post('id');
				$action = $this->request->get_post('action');

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