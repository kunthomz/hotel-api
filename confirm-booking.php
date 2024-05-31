<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $apiKey = "b0689713818666de7a176166f60d688a";
    $secret = "683bbfe1ef";
    $timestamp = time();
    $signature = hash('sha256', $apiKey . $secret . $timestamp);
    
    $headers = [
        'Content-Type: application/json',
        'Api-Key: ' . $apiKey,
        'X-Signature: ' . $signature,
        'X-Timestamp: ' . $timestamp
    ];

    $rateKey = $data['rateKey'];
    $hotelCode = $data['hotelCode'];
    $checkIn = $data['checkIn'];
    $checkOut = $data['checkOut'];
    $selectedRoom = $data['room'];
    $adults = $data['adults'];
    $children = $data['children'];
    $remarks = $data['remarks'];

    // Prepare the payload for the API request
    $payload = [
        "clientReference" => "IntegrationAgency", 
        "remark"  =>  $remarks,
        "holder" => [
            "name" => $adults[0]['firstName'],
            "surname" => $adults[0]['lastName']
        ],
        "rooms" => [
            [
                "rateKey" => $rateKey,
                "paxes" => array_map(function($adult) {
                    return [
                        "roomId" => 1,
                        "type" => "AD",
                        "name" => $adult['firstName'],
                        "surname" => $adult['lastName']
                    ];
                }, $adults)
            ]
        ],
        "hotel" => [
            "checkIn" => $checkIn,
            "checkOut" => $checkOut,
            "code" => $hotelCode,
            "rooms" => 1
        ]
    ];


    $apiUrl = 'https://api.test.hotelbeds.com/hotel-api/1.0/bookings'; 

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // if ($httpCode == 200) {
    //     echo json_encode(["message" => "Booking confirmed", "response" => json_decode($response, true)]);
    // } else {
    //     echo json_encode(["error" => "Booking failed", "response" => $response]);
    // }

    if ($httpCode == 200) {
        $responseData = json_decode($response, true);
        $referenceNumber = $responseData['booking']['reference']; 
        echo json_encode(["message" => "Booking confirmed", "referenceNumber" => $referenceNumber]);
    } else {
        echo json_encode(["error" => "Booking failed", "response" => $response]);
    }

    curl_close($ch);
}
?>
