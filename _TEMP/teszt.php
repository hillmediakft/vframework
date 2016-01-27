<!DOCTYPE html>
<html lang="hu">
<head>
	<meta charset="UTF-8">
	<title>Validator teszt</title>
</head>
<body>
	<h1>Adatok:</h1>
	
	<form name="some_name" action="" method="post">
		Username: <input type="text" name="username" />
		<br /><br />
		Password: <input type="password" name="password" />
		<br /><br />
		Password again: <input type="password" name="password_again" />
		<br /><br />
		Email: <input type="text" name="email" />
		<br /><br />
		
		<input type="submit" name="submit" value="submit"/>
    </form>
	<hr/>
</body>
</html>

<?php
include('validator_class.php');

if(!empty($_POST)){

	$validator = new Validate();
	//$validator->check_old($tomb, $items);

	// szabályok megadása
		$validator->add_rule('username', 'Felhasznalonev_label', array(
			'required' => true,
			'max' => 5
		));
		$validator->add_rule('password', 'jelszo_label', array(
			'required' => true,
			'min' => 5
		));
		$validator->add_rule('password_again', 'password_repeat_label', array(
			'matches' => 'password'
		));
		$validator->add_rule('email', 'e-mail_label', array(
			'required' => true,
			'email' => true
		));

	// egyedi üzenetek megadása	
		$validator->set_message('min', 'A :label mezo tul keves karaktert tartalmaz!');
		$validator->set_message('required', ':label mezo nem lehet ures!');

	// validálás lefuttatása	
		$validator->check($_POST);
		
	echo "<hr/>";	
		
	// validálás eredménye
		if($validator->passed()){
			echo "Sikeres validalas";
		} else {
			foreach ($validator->get_error() as $error) {
				echo $error . "<br />";
			}
		}

	
}




?>