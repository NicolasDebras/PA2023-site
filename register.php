<?php
    if (isset($_POST['submit-form'])) {
        $data = array(
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'password' => $_POST['password'],
        );

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
            header('Location: login.php');
        } else {
            echo 'Unexpected HTTP status: ' . $http_status;
        }

        // Ferme cURL
        curl_close($curl);
    } else {
        header('Location: inscription.php');
    }
?>
