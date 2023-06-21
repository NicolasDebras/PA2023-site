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

if (isset($_POST['submit-form'])) {
    $url_image = "";
	echo $_FILES['url_image'];
    if (isset($_FILES['url_image']) && !empty($_FILES['url_image']['tmp_name'])) {
        // Télécharger l'image sur Cloudinary et obtenir le lien
        try {
            $uploaded_image = $cloudinary->uploadApi()->upload($_FILES['url_image']['tmp_name'], []);
            $url_image = $uploaded_image['secure_url'];
			echo "ok";
        } catch (Exception $e) {
            echo 'Cloudinary upload failed: ' . $e->getMessage();
        }
    }

    $data = array(
        'username' => $_POST['username'],
        'email' => $_POST['email'],
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
		'url_image' => $url_image,
        'password' => $_POST['password'],
    );
	echo json_encode($data);

	
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api-pa2023.herokuapp.com/api/player/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        CURLOPT_FOLLOWLOCATION => true,
    ));

    $response = curl_exec($curl);
    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($http_status == 201) {
        header('Location: ../compte.php');
    } else {
        echo 'Unexpected HTTP status: ' . $http_status;
    }

    // Ferme cURL
    curl_close($curl);
} else {
    header('Location: ../inscription.php');
}
?>
