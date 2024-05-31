<?php
include('functions.php');
// $data = bookingList();
// echo '<pre>';
// print_r($data);
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
    </style>
</head>
<body>
    <header>
        <h1>Hotel Booking</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="booking.php">Booking</a>
            <a href="booking-list.php">Booking List</a>
        </nav>
    </header>

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form action="#" method="get">
                    <div class="row">
                        <div class="col-3">
                            <label>Date Start:</label>
                            <input type="date" class="form-control" id="start">
                        </div>
                        <div class="col-3">
                            <label>Date End:</label>
                            <input type="date" class="form-control" id="end">
                        </div>
                        <div class="col-1">
                            <label>From:</label>
                            <input type="text" class="form-control" id="from" value="1">
                        </div>
                        <div class="col-1">
                            <label>To:</label>
                            <input type="text" class="form-control" id="to" value="10">
                        </div>
                        <div class="col-3">
                            <label>Status:</label>
                            <select class="form-control" aria-label="Default" id="status" style="text-align-last:center;">
                                <option value="ALL">ALL</option>
                                <option value="CONFIRMED">CONFIRMED</option>
                                <option value="CANCELLED">CANCELLED</option>
                            </select>
                        </div>
                        <div class="col-1">
                            <button type="button" class="btn btn-dark btn-sm mt-4" id="searchBtn"> Search</button>
                        </div>  
                    </div>
                </form>
            </div>
        </div>
        <h3 id="booking-title" style="display:none;">List of Bookings</h3>
        <div id="booking-cards" class="row">

        </div>
    </div>
</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function setDates() {
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(today.getDate() + 1);
        const formatDate = date => date.toISOString().split('T')[0];
        document.getElementById('start').value = formatDate(today);
        document.getElementById('end').value = formatDate(tomorrow);
    }
    window.onload = setDates;

    $(document).ready(function(){
        $('#searchBtn').on('click', function() {
            $(this).prop('disabled', true).html('Searching...');
            let start = $('#start').val();
            let end = $('#end').val();
            let from = $('#from').val();
            let to = $('#to').val();
            let status = $('#status').val();
            if (from || to || status) {
                $.ajax({
                    url: 'api.php',
                    type: 'GET',
                    data: { exec: 'booking-list', start: start, end: end, from: from, to: to, status: status },
                    success: function(response) {
                        $('#booking-title').css('display','block');
                        const bookings = response.bookings.bookings;
                        const container = $('#booking-cards');

                        container.empty();
                        bookings.forEach(booking => {
                            const card = `
                                <div class="col-md-4 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">${booking.hotel.name}</h5>
                                            <h6 class="card-subtitle mb-2 text-muted">${booking.hotel.destinationCode}</h6>
                                            <p class="card-text">
                                                <strong>Reference:</strong> ${booking.reference}<br>
                                                <strong>Status:</strong> ${booking.status}<br>
                                                <strong>Holder:</strong> ${booking.holder.name} ${booking.holder.surname}<br>
                                                <strong>Check-In:</strong> ${booking.hotel.checkIn}<br>
                                                <strong>Check-Out:</strong> ${booking.hotel.checkOut}<br>
                                                <strong>Total Amount:</strong> ${booking.totalSellingRate} ${booking.currency}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            `;
                            container.append(card);
                        });

                    },
                    error: function() {
                        alert('Error retrieving booking list.');
                        console.log("Error fetching data", error);
                        $('#searchBtn').prop('disabled', false).html('Search');
                        $('#booking-title').css('display','none');
                    },
                    complete: function() {
                        $('#searchBtn').prop('disabled', false).html('Search');
                    }
                });
            } else {
                alert('Please enter a reference number.');
                $('#searchBtn').prop('disabled', false).html('Search');
            }
        });
    });
</script>