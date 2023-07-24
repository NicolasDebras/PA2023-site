<?php
function addPoint($playerId) {
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://nicolasdebras.fr/api/addpoint/55/' . $playerId . '/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'PATCH',
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    if ($err) {
        return "cURL Error #:" . $err;
    } else {
        return $response;
    }
    curl_close($curl);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['playerId'])) {
        echo addPoint($_POST['playerId']);
    } else {
        echo "Error: No playerId provided";
    }
}
?>
