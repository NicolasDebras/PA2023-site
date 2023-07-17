<?php
require_once(__DIR__ . '/../vendor/autoload.php');

use Cloudinary\Cloudinary;

$cloudinary = new Cloudinary(
    [
        'cloud' => [
            'cloud_name' => 'hchb6esty',
            'api_key'    => '452249598719761',
            'api_secret' => 'GbD7mxu-jsCFFmVIahdOlq87ckg',
        ],
    ]
);

// Utilise le token du cookie
if (isset($_COOKIE['auth_token']) && !empty($_COOKIE['auth_token'])) {
    $auth_token = $_COOKIE['auth_token'];
	$user_id = $_COOKIE['user_id'];

    // Initialise cURL pour la requête avec le token d'authentification
    $curl = curl_init();

    // Récupérer les données du formulaire
    $title = $_POST['title'];
    $url_image = "";

	if (isset($_FILES['url_image']) && !empty($_FILES['url_image']['tmp_name'])) {
			// Télécharger l'image sur Cloudinary et obtenir le lien
			try {
				$uploaded_image = $cloudinary->uploadApi()->upload($_FILES['url_image']['tmp_name'], []);
				$url_image = $uploaded_image['secure_url'];
			} catch (Exception $e) {
				echo 'Cloudinary upload failed: ' . $e->getMessage();
			}
	}

    $data = array(
        'title' => $title,
        'url_image' => $url_image,
        'founder_id' => $user_id
        'Founder' => 'ceci est un bug que j'ai la flemme de corriger'
    );

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://nicolasdebras.fr/api/party/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            'Authorization: Token ' . $auth_token,
            'Content-Type: application/json'
        ),
        CURLOPT_FOLLOWLOCATION => true,
    ));

    $response = curl_exec($curl);
    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($http_status == 201) {
        header('Location: ../partie.php');
    } else {
        echo 'Unexpected HTTP status: ' . $http_status . '<br>';
		echo 'Response: ' . $response;
    }

    // Ferme cURL
    curl_close($curl);
}
?>