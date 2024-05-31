<?php
include('functions.php');

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

if ($method === 'GET' && isset($_GET['exec'])) {
    switch ($_GET['exec']) {
        case 'book-now':
            if (isset($_GET['hotel_code'])) {
                $hotelCode = $_GET['hotel_code'];
                $response = hotelDetail($hotelCode);
                header('content-type: application/json');
                echo json_encode($response);
            }
            break;

        case 'booking':
            if (isset($_GET['referenceNumber'])) {
                $referenceNumber = $_GET['referenceNumber'];
                $response = bookingSearch($referenceNumber);
                header('content-type: application/json');
                echo json_encode($response);
            }
            break;

        case 'booking-list':
            if (isset($_GET['start']) && isset($_GET['end']) && isset($_GET['from']) && isset($_GET['to']) && isset($_GET['status'])) {
                $start = $_GET['start'];
                $end = $_GET['end'];
                $from = $_GET['from'];
                $to = $_GET['to'];
                $status = $_GET['status'];
                $response = bookingList($start,$end,$from,$to,$status);
                header('content-type: application/json');
                echo json_encode($response);
            }
            break;

        case 'hotels':
            $response = hotels();
            header('content-type: application/json');
            echo json_encode($response);
            break;

        case 'xsig':
            echo json_encode(getXSignature());
            break;

        default:
            echo json_encode(['error' => 'Invalid exec parameter']);
            break;
    }
} elseif ($method === 'POST' && isset($data['exec'])) {
    switch ($data['exec']) {
        case 'cancel-booking':
            if (isset($data['referenceNumber'])) {
                $referenceNumber = $data['referenceNumber'];
                $response = cancelBooking($referenceNumber);
                header('content-type: application/json');
                echo json_encode($response);
            }
            break;

        default:
            echo json_encode(['error' => 'Invalid exec parameter']);
            break;
    }
} else {
    echo json_encode(['error' => 'Invalid request method or missing exec parameter']);
}
?>
