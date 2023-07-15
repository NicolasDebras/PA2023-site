<?php

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;
$party_id = isset($_GET['party_id']) ? intval($_GET['party_id']) : null;

// Vérifie si les ID sont valides. On s'occupera de l'erreur plus tard dans le projet
if ($user_id !== null && $party_id !== null) {
    // Récupère le token depuis le cookie
    $token = $_COOKIE['auth_token'];

    // Prépare la requête cURL (autre format que les autrees mais même fonctionnement)
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://nicolasdebras.fr/api/addParticipant/' . $user_id . '/' . $party_id . '/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Token ' . $token
    ]);

    // Exécute la requête
    $response = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Ferme cURL
    curl_close($ch);

    // Vérifie le statut HTTP et redirige en conséquence
	
	//Fck j'avais encore oublié que c'était pas 200 mais 201. 20min perdu sur ça
    if ($http_status == 201) {
        header('Location: ../partie.php?joined=true');
    } else {
        header('Location: ../partie.php?joined=false');
    }
} else {
    header('Location: ../partie.php?joined=false');
}
?>
