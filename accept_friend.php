<?php
if (isset($_POST['submit-form'])) {
    $user_id = $_POST['user_id'];
    $auth_token = $_POST['auth_token'];
    $request_id = $_POST['request_id'];

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api-pa2023.herokuapp.com/api/acceptfriend/' . $request_id . '/',
      CURLOPT_CUSTOMREQUEST => 'PUT',
      CURLOPT_HTTPHEADER => array(
        'Authorization: Token ' . $auth_token
      ),
      CURLOPT_FOLLOWLOCATION => true,
    ));

    $response = curl_exec($curl);
    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($http_status == 200) {
        header('Location: ami.php');
    } else {
        header('Location: ami.php' . $http_status);
    }

    curl_close($curl);
}
?>