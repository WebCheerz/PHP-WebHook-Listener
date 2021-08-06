<?php

header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('X-Content-Type-Options: nosniff');
header('Strict-Transport-Security: max-age=63072000');
header('Content-type:application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
header('X-Robots-Tag: noindex, nofollow', true);

//Define API Key Here
define("API_KEY", "webcheerz");

//Get Data from headers
$headers = getallheaders();
$get_key = $headers['api-key'];
$get_content_type = $headers['content-type'];

// Only allow POST requests
if (strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') {
    echo json_encode(['error' => 'WebCheerz PHP Webhook Listener. Use Correct method']);
} else {

    // Check whether API is available
    if (isset($get_key)) {

        // Store API to Variable
        $apikey = $get_key;

        // Match the Recieved API with our API KEY
        if ($apikey == API_KEY) {

            // Make sure Content-Type is application/json 
            if ($get_content_type === 'application/json') {

                // Read the input stream
                $body = file_get_contents("php://input");

                // Decode the JSON object
                $object = json_decode($body, true);

                // Throw an exception if decoding failed
                if (!is_array($object)) {
                    $message = "Invalid JSON Format, Failed to Decode";
                    echo json_encode(['error' => $message]);
                } else {
                    // If everything is correct, Print Recieved Data
                    echo json_encode($object);
                }
            } else {
                // Show Error Message if Content is not application/json
                $message = "Content-Type must be application/json";
                echo json_encode(['error' => $message]);
            }
        } else {
            // If the API doesn't match, Throw error
            $message = "Wrong API KEY";
            echo json_encode(['error' => $message]);
        }
    } else {
        // If API Key doesn't exist, Thow Missing error
        $message = "API KEY is Missing";
        echo json_encode(['error' => $message]);
    }
}
