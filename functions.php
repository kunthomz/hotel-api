<?php

function sendRequest($url, $method = 'GET', $data = [], $additionalHeaders = []) {
    $apiKey = "b0689713818666de7a176166f60d688a";
    $secret = "683bbfe1ef";
    $headers = [
        'Accept: application/json',
        'Api-Key: ' . $apiKey,
        'X-Signature: ' . hash('sha256', $apiKey . $secret . time())
    ];
    $headers = array_merge($headers, $additionalHeaders);

    // Initialize cURL session
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Set method-specific options
    if ($method == 'POST' || $method == 'DELETE') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        if (!empty($data)) {
            $data = json_encode($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
    } elseif ($method == 'PUT') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        if (!empty($data)) {
            $data = json_encode($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
    }

    // Execute cURL request
    $response = curl_exec($ch);

    // Check for cURL errors
    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        error_log('cURL error: ' . $error);
        die('cURL error: ' . $error);
    }

    // Close cURL session
    curl_close($ch);

    // Decode the JSON response
    $responseData = json_decode($response, true);

    // Check for JSON decode errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log('JSON decode error: ' . json_last_error_msg());
        var_dump($response);
        die('JSON decode error: ' . json_last_error_msg());
    }

    // Log response data for debugging
    error_log('Response data: ' . json_encode($responseData));

    return $responseData;
}

function hotels() {
    $url = "https://api.test.hotelbeds.com/hotel-content-api/1.0/hotels";
    return sendRequest($url);
}

function bookingList($start,$end,$from,$to,$status) {
    $url = "https://api.test.hotelbeds.com/hotel-api/1.0/bookings?start=$start&end=$end&from=$from&to=$to&filterType=CREATION&status=$status";
    return sendRequest($url);
}

function hotelDetail($hotelCode) {
    $url = "https://api.test.hotelbeds.com/hotel-content-api/1.0/hotels/$hotelCode/details?hotelCodes=$hotelCode";
    $headers = [
        'Content-Type: application/json'
    ];
    $data = [
        "stay"=> [
            "checkIn"=> "2024-06-15",
            "checkOut"=> "2024-06-16"
        ],
        "occupancies"=> [
            [
                "rooms"=> 1,
                "adults"=> 2,
                "children"=> 0
            ]
        ],
        "hotels"=> [
                "hotel"=> [$hotelCode]
            ]
        ];

    // return sendRequest($url, "POST", [], $headers);
    return sendRequest($url, "GET", $data, $headers);
}

function roomDetail($hotelCode) {
    $url = "https://api.test.hotelbeds.com/hotel-api/1.0/hotels";
    $headers = [
        'Content-Type: application/json'
    ];
    $data = [
        "stay"=> [
            "checkIn"=> "2024-06-15",
            "checkOut"=> "2024-06-16"
        ],
        "occupancies"=> [
            [
                "rooms"=> 1,
                "adults"=> 2,
                "children"=> 0
            ]
        ],
        "hotels"=> [
                "hotel"=> [$hotelCode]
            ]
        ];

    // return sendRequest($url, "POST", [], $headers);
    return sendRequest($url, "POST", $data, $headers);
}

function bookingSearch($referenceNumber){
    $url = "https://api.test.hotelbeds.com/hotel-api/1.0/bookings/$referenceNumber";
    return sendRequest($url);
}

function cancelBooking($referenceNumber){
    $url = "https://api.test.hotelbeds.com/hotel-api/1.0/bookings/$referenceNumber";
    $response = sendRequest($url, 'DELETE');
    
    // Log the response
    error_log("Cancel Booking Response: " . json_encode($response));

    return $response;
}

function getXSignature(){
    $apiKey = "b0689713818666de7a176166f60d688a";
    $secret = "683bbfe1ef";
    $headers = array(
        'Accept: application/json',
        'Api-Key: ' . $apiKey,
        'X-Signature: ' . hash('sha256', $apiKey . $secret . time())
    );
    
    return $headers[2];
}

function sendRequest2($url, $method = 'GET', $data = [], $additionalHeaders = []){
    $apiKey = "b0689713818666de7a176166f60d688a";
    $secret = "683bbfe1ef";
    $headers = [
        'Accept: application/json',
        'Api-Key: ' . $apiKey,
        'X-Signature: ' . hash('sha256', $apiKey . $secret . time())
    ];
    $headers = array_merge($headers, $additionalHeaders);
    
    $optHeaders = "";
    foreach($headers as $header) {
        $optHeaders = $optHeaders . $header ."\r\n";
    }

    $data = json_encode($data);
    $opts = [
        "http" => [
            'method' => $method,
            'ignore_errors' => TRUE,
            'header' => $optHeaders,
            'content' => $data
        ]
    ];
    $context = stream_context_create($opts);
    $result = file_get_contents($url, false, $context);
    if (!in_array('HTTP/1.1 200 OK', $http_response_header)) {
        return [
            'headers' => $http_response_header,
            'error' => json_decode($result, true)
        ];
    }
    return json_decode($result, true);
}

?>