<?php
//Ne s'exécute que si notre formulaire est envoyé
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// On récupère nos valeurs
	$username = $_POST['username'];
	$password = $_POST['password'];

	// Initialise cURL, bibliothèque pour communiquer avec notre API
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://api-pa2023.herokuapp.com/api/auth/',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_POST => true,
	  CURLOPT_POSTFIELDS => http_build_query(array(
		'username' => $username,
		'password' => $password
	  )),
	  CURLOPT_FOLLOWLOCATION => true,
	));

	$response = curl_exec($curl);
	$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	if ($http_status == 200) {
		$body = json_decode($response);
		$token = $body->token;

		// Sauvegarde le token en tant que cookie pdt 1J
		setcookie("auth_token", $token, time() + (86400 * 30), "/");

		// Redirige vers index.php
		header("Location: index.php");
		exit;
	} else {
		// Redirige vers la page de connexion avec une variable GET pour indiquer l'erreur
		header("Location: compte.php?error=1");
		exit;
	}
	
	// Ferme cURL
	curl_close($curl);
}

// Utilise le token du cookie
if (isset($_COOKIE['auth_token']) && !empty($_COOKIE['auth_token'])) {
	$auth_token = $_COOKIE['auth_token'];

	// Initialise cURL pour la requête avec le token d'authentification
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://api-pa2023.herokuapp.com/api/player/1/',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_HTTPHEADER => array(
		'Authorization: Token ' . $auth_token
	  ),
	  CURLOPT_FOLLOWLOCATION => true,
	));

	$response = curl_exec($curl);
	$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	if ($http_status == 200) {
		echo $response;
	} else {
	  echo 'Unexpected HTTP status: ' . $http_status;
	}

	// Ferme cURL
	curl_close($curl);
}
?>
