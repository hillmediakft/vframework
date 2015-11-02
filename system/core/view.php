<?php 
class View {
	
	private $registry;
	
	/**
	 *	Az aktuális terület neve (pl.: site vagy admin)
	 *	@var string
	 */
	private $area = '';
	
	/**
	 *	Betöltendő  template mappájának a neve
	 *	@var string
	 */
	private $dirname = '';

	/**
	 *	Betöltendő template file neve (kiterjesztés nélküll)
	 *	@var string	 
	 */
	private $filename = '';

	/**
	 *	Betöltendő template file eleresi utja: mappa/file
	 *	@var string	 
	 */
	private $view_path = '';

	/**
	 *	Csak egy template file betöltése (render() metódus paraméter)
	 *	@var bool
	 */
	private $one_file_render = false;
		
	/**
	 *	Ebbe a tömbbe kerülnek az adatok, amit berakunk a template-ekbe
	 *	a set() metódussal adjuk meg az adatokat
	 *	@var array	 
	 */
	public $vars = array();

	/**
	 *	Ebbe a tömbbe kerülnek a css linkkek
	 *	@css_link array	 
	 */
	public $css_link = array();

	/**
	 *	Ebbe a tömbbe kerülnek a javascript linkek
	 *	@js_link array	 
	 */
	public $js_link = array();
	
/*-----------------------------------------------------------------------------*/

	/**
	 *	Constructor
	 */
	public function __construct()
	{
		// registry objektum
		$this->registry = Registry::get_instance();
		// request objektum
		$this->request = $this->registry->request;
		$this->area = $this->request->get_uri('area');
	}
	
	/**
	 * Nem definiált adattagok automatikus létrehozása (pl.: $objektum->valtozo_neve = 'lorem ipsum')
	 * Az új adattaghoz rendelt értékek a $vars tömbbe kerülnek (ebből is ki lehet nyerni az adatokat)
	 */
	public function __set($name,$value)
	{
		$this->vars[$name] = $value;
	}

	/**
	 *	Visszaadja a __set metódussal létrehozott adattagok értékeit (automatikusan)
	 *	pl.: echo $objektum->valtozo_neve;
	 */
	public function __get($name)
	{
		return $this->vars[$name];
	}
			

		/**
		 * HIBAKERESÉSHEZ!
		 * Megjeleníti a $vars tömb elemeit, 
		 * utána megállítja a program futását!
		 *
		 * @param	bool	ha értéke true, akkor csak a tömbelemek nevét írja ki
		 */
		public function debug($par1 = false)
		{
			
			echo <<<HTML
				<!DOCTYPE html>
				<html lang="hu">
				<head>
					<meta http-equiv="content-type" content="text/html; charset=utf-8">
					<title>DEBUG VARS</title>
				</head>
				<body>
				<h2>A template-be helyezhető változók: </h2>
HTML;
					
			echo "<pre style='background:#DDDDDD; border:1px solid #AAAAAA; padding:10px'>";
				if($par1 == true) {
					print_r($this->vars);
				} else {
					print_r(array_keys($this->vars));
				}
			echo "</pre></body></html>";
			die();		
		}			
 
    /**
	 *	Template betöltése
	 *
	 *	@param	string	mappa/file neve kiterjesztés nélkül (pl.: home/tpl_home)	 
	 *	@param	bool	ha true, akkor csak egy file-t tölt be (nem lesz automatikusan header és footer)	 
	 */
    public function render($view_path, $one_file_render = false)
	{
		$this->view_path = $view_path;
		$this->one_file_render = $one_file_render;
	
		try{
			// megnézzük, hogy a $this->view_path stringben van e / jel (ha a strpos() függvény nem false-ot ad vissza, akkor van benne / jel)
			if (strpos($this->view_path,'/') !== false) {
				// felbontjuk a $this->view_path stringet a / jel mentén, és az első elemet (a mappa nevét) vátozóhoz rendeljük
				list($this->dirname, $this->filename) = explode('/', $this->view_path);
			} else {
				throw new Exception('Hibas parameteratadas a view objektum render() fuggvenyenek <br /> A ' . $this->view_path . '.php template file nem nyithato meg!');
			}		
		} catch (Exception $e)  {
			die($e->getMessage());
		}
		
		// A template-be kerülő (tömb) adatok változókká bontása
		// ha már van ilyen nevű változó, akkor a változó neve elé beteszi a prefix-et pl.: $wddx_valtozoneve
		if(count($this->vars) > 0) {
			extract($this->vars, EXTR_PREFIX_SAME, "wddx");
		}
		
		
        // egy file betöltése
        if ($one_file_render == true) {
            include ('system/' . $this->area . '/view/' . $this->dirname . '/' . $this->filename . '.php');
        } else {
		// header - content - footer betöltése
			// header betöltése 
			if (file_exists('system/' . $this->area . '/view/' . $this->dirname . '/tpl_head.php')) {
				// ha van helyi header
				include('system/' . $this->area . '/view/' . $this->dirname . '/tpl_head.php');
			} else {
				// template header
				include('system/' . $this->area . '/view/_template/tpl_head.php');
			}
	 
			// változó tartalmi elem betöltése 
			include ('system/' . $this->area . '/view/' . $this->view_path . '.php');      
				 
			// footer betöltése 
			if (file_exists('system/' . $this->area . '/view/' . $this->dirname . '/tpl_foot.php')) {
				// ha van helyi footer
				include('system/' . $this->area . '/view/' . $this->dirname . '/tpl_foot.php');
			} else {
				// template footer
				include('system/' . $this->area . '/view/_template/tpl_foot.php');
			}
        }		
    }
	

    /**
     * Üzeneteket tartalmazó template behívása
     */
    public function renderFeedbackMessages()
    {
		// echo out the feedback messages
		require 'system/' . $this->area . '/view/_template/feedback.php';
	}
} // end class
?>