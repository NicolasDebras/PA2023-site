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
    if (empty($_POST['party_id']) || empty($_POST['number']) || empty($_POST['langage']) || empty($_FILES['game'])) {
        echo 'Error: Missing required data';
        return;
    }

    $party_id = $_POST['party_id'];
    $url_game = "";
    $number = intval($_POST['number']);
    $language = $_POST['langage'];
    $game_file = $_FILES['game'];

    $participants = isset($_POST['participants']) ? $_POST['participants'] : null;
    if ($participants !== null) {
        $currentUserId = $_POST['creator_id'];
        
        $participant_data = [['id' => $currentUserId, 'tag_player' => 1]];
        for ($i = 0; $i < count($participants); $i++) {
            $participant_id = $participants[$i];
            $participant_data[] = ['id' => $participant_id, 'tag_player' => ($i + 2)];
        }
    } else {
        $participant_data = [];
    }

    $arguments = null;
    if (!empty($_POST['arguments'])) {
        $args_names = $_POST['arguments']['name'];
        $args_types = $_POST['arguments']['type'];
        $args_values = $_POST['arguments']['value'];
    
        for ($i = 0; $i < count($args_names); $i++) {
            if (!empty($args_names[$i]) && !empty($args_values[$i]) && !empty($args_types[$i])) {
                $arguments[] = ['name' => $args_names[$i], 'value' => $args_values[$i], 'type' => $args_types[$i]];
            }
        }
    }

    try {
        $uploaded_game = $cloudinary->uploadApi()->upload(
            $game_file['tmp_name'], 
            [
                'resource_type' => 'raw',
                'public_id' => pathinfo($game_file['name'], PATHINFO_FILENAME),
                'format' => pathinfo($game_file['name'], PATHINFO_EXTENSION)
            ]
        );
        $url_game = $uploaded_game['secure_url'];
    } catch (Exception $e) {
        echo 'Cloudinary upload failed: ' . $e->getMessage();
        return;
    }

    $data = [
        "language" => $language,
        "url_game" => $url_game,
        "started" => true,
        "max_player" => $number,
        "participants" => $participant_data,
    ];
    
    if (!empty($arguments)) {
        $data["argument_parties"] = $arguments;
    }

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://nicolasdebras.fr/api/update-party/' . $party_id . '/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'PATCH',
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json'
        ],
    ]);

    $response = curl_exec($curl);
    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($http_status == 200) {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        //header('Location: ../infos_partie.php?party_id=' . $party_id);
    } else {
        echo 'Error: Failed to edit the party. HTTP Status: ' . $http_status . ' Response: ' . $response;
        return;
    }

    curl_close($curl);
}
?>