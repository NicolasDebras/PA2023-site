<?php
if (isset($_POST['submit-form'])) {
    $user_id = $_POST['user_id'];
    $auth_token = $_POST['auth_token'];
    $friend_id = $_POST['friend_id'];

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api-pa2023.herokuapp.com/api/addfirend/' . $user_id . '/' . $friend_id . '/',
      CURLOPT_POST => true,
      CURLOPT_HTTPHEADER => array(
        'Authorization: Token ' . $auth_token
      ),
      CURLOPT_FOLLOWLOCATION => true,
    ));

    $response = curl_exec($curl);
    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($http_status == 200) {
        header('Location: friend_page.php?message=success');
    } else {
        header('Location: friend_page.php?message=error&status=' . $http_status);
    }

    curl_close($curl);
}
?>
