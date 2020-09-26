<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tracking map</title>

    <!-- For map service -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>

    <!-- Bootstrap and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php if (login_check($mysqli) == true) : ?>
    <div id="map"></div>

    <div id="map-overlay">
        <div class="box desktop">
            <h1>Hi, <?php echo htmlentities($_SESSION['username']); ?></h1>
            <p>This map shows your location history that you have recorded using this website. <br><br>Furthermore, you can click at each marker to see when your location was recorded.</p>
            <br><br>
        </div>
    </div>
    <p>Welcome <?php echo htmlentities($_SESSION['username']); ?>!</p>
    <p>
        This is an example protected page. To access this page, users
        must be logged in. At some stage, we'll also check the role of
        the user, so pages will be able to determine the type of user
        authorised to access the page.
    </p>
    <p>Return to <a href="index.php">login page</a></p>
    <p>Do you want to change user? <a href="includes/logout.php">Log out</a>

    <script>
        $("#map").height($(window).height());
        var mymap = L.map('map').setView([55.7971, 12.40508], 13);
        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            maxZoom: 18,
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1,
            accessToken: 'pk.eyJ1IjoicmhsMjQwMSIsImEiOiJjanRzeW1pamQwM3duM3lscHVsbm4zN2oxIn0.enev6ImNJFLfenYK0EL3Dw'
        }).addTo(mymap);
    </script>

    <?php
        include_once "includes/db_connect.php";

        $sql = "SELECT loc_lat, loc_long, dt_created FROM locations WHERE user_id = " . $_SESSION['user_id'];
        $result = $mysqli->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                // Create a marker
                echo "<script>L.marker([". $row["loc_lat"] .", ". $row["loc_long"] ."], {title: '". $_SESSION["username"] ."'}).addTo(mymap).bindPopup('<b>". $_SESSION["username"] ."</b><br><small>". $row["dt_created"] ."</small>').openPopup();</script>";
            }
        } else {
            echo "<script>console.log('0 results for current user')</script>";
        }

    ?>


<?php else : ?>
    <p>
        <span class="error">You are not authorized to access this page.</span> Please <a href="index.php">login</a>.
    </p>
<?php endif; ?>
</body>
</html>