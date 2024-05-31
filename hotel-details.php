<?php
    include('functions.php');
    $getHotel = hotelDetail($_GET['hotel_code']);
    $hotel = $getHotel['hotel'];

    $getRoom = roomDetail($_GET['hotel_code']);

    if (isset($getRoom['hotels']) && isset($getRoom['hotels']['total']) && $getRoom['hotels']['total'] == 0) {
        $room = [];
    } else {
        $room = isset($getRoom['hotels']['hotels']) ? $getRoom['hotels']['hotels'] : [];
    }

    if (isset($room[0]['rooms'])) {
        foreach ($room[0]['rooms'] as $rm) {
            $rateKey = 0;
            $maxAdults = 0;
            $maxChildren = 0;
            
            if (isset($rm['rates'])) {
                foreach ($rm['rates'] as $rate) {
                    if ($rate['rateKey'] > $rateKey) {
                        $rateKey = $rate['rateKey'];
                    }
                    if ($rate['adults'] > $maxAdults) {
                        $maxAdults = $rate['adults'];
                    }
                    if ($rate['children'] > $maxChildren) {
                        $maxChildren = $rate['children'];
                    }
                }
            }
    
            $aggregatedRooms[] = [
                'name' => $rm['name'],
                'rate_key' => $rateKey,
                'max_adults' => $maxAdults,
                'max_children' => $maxChildren
            ];
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Booking Homepage</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        /* Add CSS styling here */
        body {
            font-family: Arial, sans-serif;
            /* margin: 0;
            padding: 0; */
            background-color: #f2f2f2;
        }
        /* .container {
            max-width: 1500px;
            margin: 0 auto;
            padding: 20px;
        } */
        header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
        nav {
            text-align: center;
            margin-bottom: 20px;
        }
        nav a {
            text-decoration: none;
            color: #fff;
            margin: 0 10px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            font-size: 20px;
        }
        .search-box {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        .search-box input[type="text"] {
            width: 300px;
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 16px;
        }
        .search-box input[type="submit"] {
            padding: 10px 20px;
            border: none;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            border-radius: 3px;
        }
        .hotel-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-top: 20px;
        }
        .hotel-card {
            background-color: #fff;
            /* margin-bottom: 20px; */
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .hotel-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #ccc;
        }
        .hotel-card .details {
            padding: 20px;
        }
        .hotel-card h3 {
            margin-top: 0;
        }
        .hotel-card p {
            margin: 10px 0;
            color: #666;
        }
        .card-header {
            background-color: #333;
            color: #fff;
        }
        .reserve-btn {
            background-color: #333;
            color: #fff;
        }

        .input-container {
            position: relative;
            display: inline-block;
        }

        .input-container .tooltip {
            visibility: hidden;
            width: 120px;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 5px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .input-container:hover .tooltip {
            visibility: visible;
            opacity: 1;
        }
        .room-img {
            display: flex;
            justify-content: center;
            align-items: center;
            border-style: groove;
            text-align: center;
            height: 180px;
        }
        .room-names{
            text-transform: uppercase;
        }

        .sidebar-box {
        max-height: 120px;
        position: relative;
        overflow: hidden;
        }
        .sidebar-box .read-more { 
        position: absolute; 
        bottom: 0; 
        left: 0;
        width: 100%; 
        text-align: center; 
        margin: 0; padding: 30px 0; 
            
        /* "transparent" only works here because == rgba(0,0,0,0) */
        background-image: linear-gradient(to bottom, transparent, gray);
        }

        .card-booking {
            position: -webkit-sticky;
            position: sticky;
            top: 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Hotel Booking</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="booking.php">Booking</a>
            <a href="booking-list.php">Booking List</a>
            <!-- <a href="hotel-search.php">Hotel Search</a> -->
        </nav>
    </header>
    <div class="container-fluid px-5">
        <div class="row">
            <div class="col-12 col-sm-12 col-lg-12 col-lg-4 col-xl-4 p-2">  
                <div class="card-booking">
                    <h2><b><?= $hotel['name']['content'] ?></b></h2>
                    <div class="card">
                        <h5 class="card-header text-center py-4">
                            <strong>YOUR RESERVATION</strong>
                        </h5>
                        <div class="card-body px-lg-3 pt-0">
                            <form id="BookingForm" class="text-center" hotelCode="<?= $_GET['hotel_code'] ?>">
                                <div class="row">

                                    <div class="col-6 mt-4 text-left">
                                        <label for="">Check In</label>
                                        <input type="date" id="checkIn" class="form-control text-left">
                                    </div>
        
                                    <div class="col-6 mt-4 text-left">
                                        <label for="">Check Out</label>
                                        <input type="date" id="checkOut" class="form-control text-left">
                                    </div>
        
                                    <div class="col-12 mt-4 text-left">
                                        <label for="">Select Room</label>
                                        <select class="form-select" aria-label="Default" id="selectedRoom" style="text-transform:uppercase;">
                                            <option value="">--</option>
                                            <?php foreach ($aggregatedRooms as $roomData): ?>
                                                <option value="<?= htmlspecialchars($roomData['name'], ENT_QUOTES, 'UTF-8') ?>" 
                                                        data-max-adults="<?= htmlspecialchars($roomData['max_adults'], ENT_QUOTES, 'UTF-8') ?>" 
                                                        data-max-children="<?= htmlspecialchars($roomData['max_children'], ENT_QUOTES, 'UTF-8') ?>"
                                                        data-ratekey="<?= htmlspecialchars($roomData['rate_key'], ENT_QUOTES, 'UTF-8') ?>">
                                                    <?= htmlspecialchars($roomData['name'], ENT_QUOTES, 'UTF-8') ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if (!$room): ?>
                                            <small class="text-danger">No rooms avaiable.</small>
                                        <?php endif; ?>
                                    </div>
                                
                                    <div class="col-12 text-left">
                                        <h5 class=" h5 card-title pt-3">Guest/s</h5>
                                    </div>
                                    <div class="col-6 text-left">
                                        <label for="adult">Adult/s</label>
                                        <div class="input-container">
                                            <input type="text" id="adults" class="form-control validate" data-allowcharacters="[0-9]" maxlength="2">
                                            <small class="text-danger" id="max-adult-display"></small>
                                            <span class="tooltip">Age 13+</span>
                                        </div>
                                    </div>
                                    <div class="col-6 text-left">
                                        <label for="children">Children</label>
                                        <div class="input-container">
                                            <input type="text" id="childrens" class="form-control validate" data-allowcharacters="[0-9]" maxlength="2">
                                            <small class="text-danger" id="max-children-display"></small>
                                            <span class="tooltip">Ages 2–12</span>
                                        </div>
                                    </div>
                                </div>
    
                                <button type="button" class="reserve-btn btn py-2 mt-4" id="checkBook">
                                    Book Now <i class="fa-solid fa-arrow-right"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-lg-12 col-lg-8 col-xl-8 p-2">
                <div id="carouselExample" class="carousel slide">
                    <div class="carousel-inner">
                        <?php $i = 0; ?>
                        <?php foreach ($hotel['images'] as $img): ?>
                            <div class="carousel-item <?= $i == 0 ? 'active' : '' ?>">
                                <img src="https://photos.hotelbeds.com/giata/bigger/<?= $img['path'] ?>" class="d-block w-100" style="max-height: 350px; width: auto; " alt="...">
                            </div>
                            <?php $i++; ?>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
                <div class="hotel-details mt-4">
                    <h4><b>About this place</b></h4>
                    <div class="sidebar-boxx">
                        <p><?= $hotel['description']['content'] ?></p>
                        <!-- <p class="read-more"><a href="#" class="button">Read More</a></p> -->
                    </div>
                    <hr>
                    <ul>
                        <li>Accommodation Type: <?= $hotel['accommodationType']['typeDescription'] ?></li><hr>
                        <li>List of Boards: 
                            <?php foreach($hotel['boards'] as $board): ?>
                                <ul style="list-style-type: square; padding-left:5%">
                                    <li><small><?= $board['description']['content'] ?></small></li>
                                </ul>
                            <?php endforeach ?>
                        </li><hr>
                        <li>Address: <?= $hotel['address']['content'] ?></li><hr>
                        <li>City: <?= $hotel['city']['content'] ?></li><hr>
                        <li>Postal Code: <?= $hotel['postalCode'] ?></li><hr>
                        <li>Email: <small><?= $hotel['email'] ?></small></li><hr>
                        <li>List of Phones: 
                            <?php foreach($hotel['phones'] as $phone): ?>
                                <ul style="list-style-type: square; padding-left:5%">
                                    <li><small><?= $phone['phoneNumber'] ?>  (<?= $phone['phoneType'] ?>)</small></li>
                                </ul>
                            <?php endforeach ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="roomTitle"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <label for="">Check In: <span id="roomCheckIn"></span></label>
                        </div>
                        <div class="col-6">
                            <label for="">Check Out: <span id="roomCheckOut"></span></label>
                        </div>
                        <div class="checkAdult">
                            <hr class="mt-3">
                            <label for="">Adult (<span id="adultsNumber"></span>)</label>
                        </div>
                        <div class="row">
                            <div class="col-6 adultFirstName"></div>
                            <div class="col-6 adultLastName"></div>
                        </div>
                        <div class="checkChildren">
                            <hr class="mt-3">
                            <label>Children (<span id="childrensNumber"></span>)</label>
                        </div>
                        <div class="row">
                            <div class="col-6 childrenFirstName"></div>
                            <div class="col-6 childrenLastName"></div>
                        </div>
                        <hr class="mt-3">
                        <label>Remarks</label>
                        <textarea  id="bookingremarks" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-sm btn-dark" id="confirmBooking">Confirm Booking</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
var $el, $ps, $up, totalHeight;
    $(".sidebar-box .button").click(function() {
    totalHeight = 0

    $el = $(this);
    $p  = $el.parent();
    $up = $p.parent();
    $ps = $up.find("p:not('.read-more')");
    
    $ps.each(function() {
        totalHeight += $(this).outerHeight();
    });
            
    $up
        .css({
        "height": $up.height(),
        "max-height": 9999
        })
        .animate({
        "height": totalHeight
        });
    
    // fade out read-more
    $p.fadeOut();
    
    // prevent jump-down
    return false;
        
    });
   function setDates() {
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(today.getDate() + 1);
        const formatDate = date => date.toISOString().split('T')[0];
        document.getElementById('checkIn').value = formatDate(today);
        document.getElementById('checkOut').value = formatDate(tomorrow);
    }
    window.onload = setDates;

    $(document).ready(function(){
        $('#selectedRoom').change(function() {
            const maxAdults = $(this).find(':selected').data('max-adults');
            const maxChildren = $(this).find(':selected').data('max-children');
            const rateKey = $(this).find(':selected').data('ratekey');
            let msgAdults = maxAdults != undefined? `${maxAdults} Max Adult`:'';
            let msgChildres = maxChildren != undefined? `${maxChildren} Max Children`:'';
            $('#adults').attr('max', maxAdults);
            $('#adults').attr('ratekey', rateKey);
            $('#childrens').attr('max', maxChildren);
            $('#max-adult-display').text(msgAdults);
            $('#max-children-display').text(msgChildres);
        });
        
        $('#selectedRoom').change();

        $('#checkBook').click(function(e) {
            e.preventDefault();

            let hotelCode = $('#BookingForm').attr('hotelCode');
            let checkin = $('#checkIn').val();
            let checkout = $('#checkOut').val();
            let selectedroom = $('#selectedRoom').val();
            let adultsNum = parseInt($('#adults').val(), 10);
            let childrensNum = parseInt($('#childrens').val(), 10);
            const maxAdults = parseInt($('#adults').attr('max'), 10);
            const maxChildren = parseInt($('#childrens').attr('max'), 10);

            // Validation checks
            if (!checkin || !checkout || !selectedroom || isNaN(adultsNum) || adultsNum <= 0) {
                alert("Please fill in all required fields.");
                return;
            }
            if (adultsNum > maxAdults) {
                alert(`${maxAdults} Adult only.`);
                return;
            }
            if (childrensNum > maxChildren) {
                alert(`${maxChildren} Children only.`);
                return;
            }
            if (isNaN(childrensNum) || childrensNum <= 0) {
                $(".checkChildren").hide();
            } else {
                $(".checkChildren").show();
            }

            $('#roomTitle').text(selectedroom).css('text-transform', 'uppercase');
            $('#roomCheckIn').text(checkin);
            $('#roomCheckOut').text(checkout);
            $('#adultsNumber').text(adultsNum);
            $('#childrensNumber').text(childrensNum);

            const $adultfnameContainer = $('.adultFirstName');
            const $adultlnameContainer = $('.adultLastName');
            const $childrenfnameContainer = $('.childrenFirstName');
            const $childrenlnameContainer = $('.childrenLastName');
            $adultfnameContainer.empty(); // Clear previous input boxes
            $adultlnameContainer.empty();
            $childrenfnameContainer.empty();
            $childrenlnameContainer.empty();

            if (adultsNum > 0) {
                for (let i = 0; i < adultsNum; i++) {
                    $adultfnameContainer.append(`<input type="text" class="form-control" id="adultFirstName${i}" placeholder="Enter First Name" required><br>`);
                    $adultlnameContainer.append(`<input type="text" class="form-control" id="adultLastName${i}" placeholder="Enter Last Name" required><br>`);
                }
            }
            if (childrensNum > 0) {
                for (let i = 0; i < childrensNum; i++) {
                    $childrenfnameContainer.append(`<input type="text" class="form-control" id="childrenFirstName${i}" placeholder="Enter First Name" required><br>`);
                    $childrenlnameContainer.append(`<input type="text" class="form-control" id="childrenLastName${i}" placeholder="Enter Last Name" required><br>`);
                }
            }
            const myModal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
            myModal.show();
        });

        $('#confirmBooking').click(function(e) {
            e.preventDefault();

            $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');

            let rateKey = $('#adults').attr('ratekey');
            let hotelCode = $('#BookingForm').attr('hotelCode');
            let checkin = $('#checkIn').val();
            let checkout = $('#checkOut').val();
            let selectedroom = $('#selectedRoom').val();
            let bookingremarks = $('#bookingremarks').val();
            let adultsNum = parseInt($('#adults').val(), 10);
            let childrensNum = parseInt($('#childrens').val(), 10);

            let adults = [];
            for (let i = 0; i < adultsNum; i++) {
                let fname = $(`#adultFirstName${i}`).val();
                let lname = $(`#adultLastName${i}`).val();
                adults.push({ firstName: fname, lastName: lname });
            }

            let children = [];
            for (let i = 0; i < childrensNum; i++) {
                let fname = $(`#childrenFirstName${i}`).val();
                let lname = $(`#childrenLastName${i}`).val();
                children.push({ firstName: fname, lastName: lname });
            }

            let bookingData = {
                hotelCode: hotelCode,
                rateKey: rateKey,
                checkIn: checkin,
                checkOut: checkout,
                room: selectedroom,
                adults: adults,
                children: children,
                remarks: bookingremarks
            };

            $.ajax({
                type: 'POST',
                url: 'confirm-booking.php',
                data: JSON.stringify(bookingData),
                contentType: 'application/json',
                success: function(response) {
                    let responseData = JSON.parse(response);
                    if (responseData.message === "Booking confirmed") {
                        let referenceNumber = responseData.referenceNumber;
                        window.location.href = 'booking-confirmation.php?bookingReference=' + encodeURIComponent(referenceNumber);
                    } else {
                        alert('Booking failed: ' + responseData.error);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error: ' + xhr.responseJSON.message);
                },
                complete: function() {
                    $('#confirmBooking').prop('disabled', false).html('Confirm Booking');
                }
            });
        });


    });
    
</script>
