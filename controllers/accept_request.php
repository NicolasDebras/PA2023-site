<?php

if (isset($_POST['submit-form'])) {
    $user_id = $_POST['user_id'];
    $auth_token = $_POST['auth_token'];
    $request_id = $_POST['request_id'];
    $party_id = $_POST['party_id'];

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api-pa2023.herokuapp.com/api/accept/' . $request_id . '/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Token ' . $auth_token,
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);
    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

	if ($http_status == 200) {
		header('Location: ../infos_partie.php?party_id=' . $party_id . '&joined=true');
	} else {
		header('Location: ../infos_partie.php?party_id=' . $party_id . '&joined=false');
	}
} else {
    header('Location: ../index.php');
}
