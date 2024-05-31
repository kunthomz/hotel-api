<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Search Results</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/crypto-js.min.js"></script>
    <style>
        /* Add CSS styling here */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
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
            width: calc(33.33% - 20px);
            background-color: #fff;
            margin-bottom: 20px;
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
        .book-now-btn {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .book-now-btn:hover {
            background-color: #0056b3;
        }
        .search-form {
            text-align: center;
            margin-bottom: 20px;
        }
        .search-form select {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
        }
        .search-form input[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .search-form input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <h1>Hotel Search Results</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="booking.php">Booking</a>
            <a href="booking-list.php">Booking List</a>
            <!-- <a href="hotel-search.php">Hotel Search</a> -->
        </nav>
    </header>

    <div class="container">
        <div class="search-form">
            <div class="card">
                <div class="card-body pt-2">
                    <legend for="search-reference">Booking Reference #:</legend>
                    <input type="text" id="search-reference">
                    <button class="btn btn-sm btn-dark" id="search-button">Search</button>
                    <hr>
                    <div class="row" style="text-align: left;">
                        <legend class="text-center" id="hotel-name">--</legend>
                        <div class="col-6 mt-3">
                            <label for="">Reference #: <span id="reference-number">--</span></label><br>
                            <label for="">Holder Name: <span id="holder-name">--</span></label><br>
                            <label for="">Adult: <span id="adult-pax">--</span> Pax | Children: <span id="children-pax">--</span> Pax</label><br>
                            <label for="">Check In: <span id="check-in">--</span></label> | 
                            <label for="">Check Out: <span id="check-out">--</span></label><br>
                            <label for="">Remarks: <span id="remarks">--</span></label>
                        </div>
                        <div class="col-6 mt-3">
                            <label for="">Date Created: <span id="date-created">--</span></label><br>
                            <label for="">Status: <span id="status">--</span></label><br>
                            <label for="">Room Name: <span id="room-name">--</span></label><br>
                            <label class="text-left mt-5 mb-2"><b>Pending Amount: </b> <span id="pending-amount">0.00</span></label>
                        </div>
                        <hr>
                        <button class="btn btn-sm btn-danger" style="width: 25%; margin-left: 35%; display:none;" id="cancel-booking">Cancel Booking</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

    $(document).ready(function(){
        $('#search-button').on('click', function() {
            $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Searching...');
            var referenceNumber = $('#search-reference').val();
            if (referenceNumber) {
                $.ajax({
                    url: 'api.php',
                    type: 'GET',
                    data: { exec: 'booking', referenceNumber: referenceNumber },
                    success: function(response) {
                        if (response.error) {
                            alert('Booking not found.');
                        } else if (response.booking) {
                            $('#hotel-name').text(response.booking.hotel.name);
                            $('#reference-number').text(response.booking.reference);
                            $('#holder-name').text(response.booking.holder.name + ' ' + response.booking.holder.surname);
                            var room = response.booking.hotel.rooms[0];
                            $('#adult-pax').text(room.rates[0].adults);
                            $('#children-pax').text(room.rates[0].children);
                            $('#room-name').text(room.name);
                            $('#date-created').text(response.booking.creationDate);
                            $('#status').text(response.booking.status);
                            $('#check-in').text(response.booking.hotel.checkIn);
                            $('#check-out').text(response.booking.hotel.checkOut);
                            $('#remarks').text(response.booking.remark);
                            $('#pending-amount').text(response.booking.pendingAmount.toFixed(2));
                            $("#cancel-booking").css('display','block');
                            if (response.booking.status == 'CANCELLED') {
                                $('#cancel-booking').css('display', 'none');
                            }else{
                                $('#cancel-booking').css('display', 'block');
                            }
                        } else {
                            alert('Booking not found.');
                            $('#search-button').prop('disabled', false).html('Search');
                        }
                    },
                    error: function() {
                        alert('Error retrieving booking information.');
                        $('#search-button').prop('disabled', false).html('Search');
                    },
                    complete: function() {
                        $('#search-button').prop('disabled', false).html('Search');
                    }
                });
            } else {
                alert('Please enter a reference number.');
                $('#search-button').prop('disabled', false).html('Search');
            }
        });

        $('#cancel-booking').on('click', function() {
            if (confirm('Are you sure you want to proceed?')) {
                $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Cancel Booking...');
                var referenceNumber = $('#search-reference').val();
                var data = { exec: 'cancel-booking', referenceNumber: referenceNumber };
                console.log('AJAX Data:', data); 
                if (referenceNumber) {
                    $.ajax({
                        url: 'api.php',
                        type: 'POST',
                        data: JSON.stringify(data),
                        contentType: 'application/json',
                        success: function(response) {
                            $('#status').text(response.booking.status);
                            $('#pending-amount').text(response.booking.pendingAmount.toFixed(2));
                            alert('Booking successfully canceled.');
                            $('#cancel-booking').prop('disabled', false).html('Cancel Booking');
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseText);
                            alert('Error canceling booking. Please check console for details.');
                            $('#cancel-booking').prop('disabled', false).html('Cancel Booking');
                        }
                    });
                } else {
                    alert('Please enter a reference number.');
                    $('#cancel-booking').prop('disabled', false).html('Cancel Booking');
                }
            }
        });
    });


</script>
