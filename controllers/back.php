<?php
// Ne s'exécute que si notre formulaire est envoyé
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // On récupère nos valeurs
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Initialise cURL, bibliothèque pour communiquer avec notre API
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://nicolasdebras.fr/api/auth/',
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
        $user_id = $body->user_id; // Récupère l'ID utilisateur

        // Sauvegarde le token en tant que cookie pendant 30 jours
        setcookie("auth_token", $token, time() + (86400 * 30), "/");

        // Sauvegarde l'ID utilisateur en tant que cookie pendant 30 jours
        setcookie("user_id", $user_id, time() + (86400 * 30), "/");

        // Redirige vers index.php
        header("Location: ../index.php");

        exit;
    } else {
        // Redirige vers la page de connexion avec une variable GET pour indiquer l'erreur
        header("Location: ../compte.php?error=1");
        exit;
    }

    // Ferme cURL
    curl_close($curl);
}


// Utilise le token du cookie
if (isset($_COOKIE['auth_token']) && !empty($_COOKIE['auth_token']) && isset($_COOKIE['user_id']) && !empty($_COOKIE['user_id'])) {
    $auth_token = $_COOKIE['auth_token'];
    $user_id = $_COOKIE['user_id'];
    //echo $auth_token;

    // Initialise cURL pour la requête avec le token d'authentification
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://nicolasdebras.fr/api/player/' . $user_id . '/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Token ' . $auth_token
        ),
        CURLOPT_FOLLOWLOCATION => true,
    ));

    $response = curl_exec($curl);
    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($http_status == 200) {
        //echo $response;
    } else {
        echo 'Unexpected HTTP status: ' . $http_status;
    }

    // Ferme cURL
    curl_close($curl);
}

?>
