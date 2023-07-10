<?php
if (isset($_POST['submit-form'])) {
    $user_id = $_POST['user_id'];
    $auth_token = $_POST['auth_token'];
    $friend_username = $_POST['friend_username'];

    // Recherche de l'utilisateur par son pseudo
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://139.162.199.69/api/playerName/' . urlencode($friend_username) . '/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Token ' . $auth_token
        ),
        CURLOPT_FOLLOWLOCATION => true,
    ));

    $response = curl_exec($curl);
    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($http_status == 200) {
        $user_info = json_decode($response);
        $friend_id = $user_info->id;

        // Ajout de l'ami
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://139.162.199.69/api/addfriend/' . $user_id . '/' . $friend_id . '/',
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Token ' . $auth_token
            ),
            CURLOPT_FOLLOWLOCATION => true,
        ));

        $response = curl_exec($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($http_status == 201) {
            header('Location: ../ami.php');
        } else {
            header('Location: ../ami.php?message=error&status=' . $http_status);
        }
    } else {
        header('Location: ../ami.php?message=error&status=' . $http_status);
    }
}
?>
