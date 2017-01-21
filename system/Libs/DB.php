<?php
namespace System\Libs;

use \PDO, \PDOException;

class DB {

	private $host;
	private $db_name;
	private $user;
	private $password;

	/**
	 * Kapcsolat adatainak megadása
	 */
	public function __construct($db_name, $host, $user, $password)
	{
		$this->db_name = $db_name;
		$this->host = $host;
		$this->user = $user;
		$this->password = $password;
	}

	/**
	 * Visszaad egy PDO adatbázis kapcsolat objektumot (hozzárendeli a $connect statikus tulajdonsághoz),
	 * vagy létrehoz egy új adatbázis kapcsolatot (ha nincs), és azt adja vissza
	 *
	 * @return object (PDO)
	 * @access public
	 */
	public function create()
	{
		try {
			$connect = new PDO('mysql:dbname=' . $this->db_name . ';host=' . $this->host . ';charset=utf8', $this->user, $this->password);
			// hiba visszaadás beállítása a PDO objektumban a fejlesztői környezet alapján
			if(ENV == 'development'){
				$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
		}
		catch(PDOException $e) {
			die('<strong>Database error:</strong> ' . $e->getMessage());
		}

		return $connect;
	}
}
?>