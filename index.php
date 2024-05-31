<?php
include('functions.php');
$data = hotels();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Booking Homepage</title>
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
            display: none; /* Hide by default */
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
            <!-- <a href="booking-list.php">Booking List</a>
            <a href="hotel-search.php">Hotel Search</a> -->
        </nav>
    </header>

    <div class="container">
        <div class="search-box">
            <h2>Find Your Perfect Hotel</h2>
            <form action="#" method="get">
                <input type="text" id="location-input" name="location" placeholder="Enter Location">
            </form>
        </div>

        <h2>Featured Hotels</h2>

        <?php if (!empty($data['hotels'])) { ?>
            <div class="hotel-list">
                <?php foreach ($data['hotels'] as $hotel) { ?>
                    <div class="hotel-card" data-address="<?= htmlspecialchars($hotel['address']['content']); ?>">
                        <!-- <img src="hotel1.jpg" alt="Hotel 1"> -->
                        <div class="details">
                            <h3><?= $hotel['name']['content']; ?></h3>
                            <p>Category: <?= $hotel['categoryCode']; ?></p>
                            <p>Address: <?= $hotel['address']['content']; ?></p>
                            <!-- <p>Selling Rate: <?= $hotel['totalSellingRate']['content']; ?>test</p> -->
                            <a href="hotel-details.php?exec=book-now&hotel_code=<?= $hotel['code']; ?>">Book Now</a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } else {
            echo 'No hotels found.';
        } ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#location-input').on('input', function() {
                var searchTerm = $(this).val().toLowerCase();
                $('.hotel-card').each(function() {
                    var address = $(this).data('address').toLowerCase();
                    if (address.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }).trigger('input');
        });
    </script>
</body>
</html>
