<?php
namespace System\Core;
use System\Libs\Config;
 
class AdminModel extends Model {

	protected $lang;
	protected $langs;

	function __construct()
	{
		parent::__construct();

		$this->lang = LANG;
		$this->langs = Config::get('allowed_languages');
	}

}
?>