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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php if (login_check($mysqli) == true) : ?>
    <div id="map"></div>

    <div id="map-overlay">
        <div class="box desktop">
            <h1 class="inline">Hi, <?php echo htmlentities($_SESSION['username']); ?></h1>
            <p class="label"><?php echo $_SESSION["membership_level"]; ?></p>
            <p>This map shows your location history that you have recorded using this website. <br><br>Furthermore, you can click at each marker to see when your location was recorded.</p>
            <br>
            <h3>Activate tracking</h3>
            <p>Enter the interval of saving your location below and hit start</p>
            <p>Interval (mins.):</p>
            <input type="number" id="interval" class="input-small inline" value="2">
            <button type="button" id="track-btn" class="btn btn-success">Start!</button>
            <br>
            <h3>View other user's location</h3>
            <p>Enter the user id of your desired user in order to show their track on the map.</p>
            <p>Your id: <?php echo $_SESSION["user_id"]; ?></p>
            <form action="map.php" method="get">
                <input type="number" id="user-id-track" name="u" class="input-small inline" placeholder="ID">
                <input type="submit" class="btn btn-success" value="Load user">
            </form>
            <br><br><br>
            <a href="includes/logout.php" id="logout" class="btn btn-danger">Log out</a>
        </div>
    </div>


    <script>
        $("#map").height($(window).height()-20);

        getLocation();

        // Crete map
        var mymap = L.map('map').setView([55.79, 12.35818], 13);
        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            maxZoom: 18,
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1,
            accessToken: 'pk.eyJ1IjoicmhsMjQwMSIsImEiOiJjanRzeW1pamQwM3duM3lscHVsbm4zN2oxIn0.enev6ImNJFLfenYK0EL3Dw'
        }).addTo(mymap);


        var x = document.getElementById("position");
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                x.innerHTML = "Geolocation is not supported by this browser.";
            }
        }

        function showPosition(position) {
            let lat = position.coords.latitude;
            let long = position.coords.longitude;

            x.innerHTML = "Latitude: " + position.coords.latitude +
                "<br>Longitude: " + position.coords.longitude;
            L.marker([lat, long], {title: 'Your position'}).addTo(mymap).bindPopup('<b>Your location<br>').openPopup();
        }


        // Function to save location. First get location
        function saveLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(uploadLocation);
            } else {
                x.innerHTML = "Geolocation is not supported by this browser.";
            }
        }

        // Function to upload location to db. Called from saveLocation()
        function uploadLocation(position) {
            $.post("postservice.php", {
                action: "uploadLocation",
                user_id: <?php echo $_SESSION["user_id"]; ?>,
                lat: position.coords.latitude,
                long: position.coords.longitude
            }, function(data, status){
                let response = JSON.parse(data);
                console.log(response);
                if (response.success) {
                    L.marker([response.lat, response.long], {title: "<?php echo $_SESSION["username"]; ?>"}).addTo(mymap).bindPopup('<?php echo $_SESSION["username"]; ?>');
                } else {
                    console.log("Failed to uplaod location");
                }
            });
        }


        let interval = 1000;
        let trackingActive = false;
        let tracking_interval;
        $("#track-btn").click(function() {
            if (!trackingActive) {
                trackingActive = true;
                interval = $("#interval").val() * 1000 * 60;
                $("#track-btn").text("Tracking...");
                tracking_interval = setInterval(function(){saveLocation()}, interval);
                console.log("Started tracking with interval: " + interval);
            } else {
                trackingActive = false;
                clearInterval(tracking_interval);
                $("#track-btn").text("Start!");
                console.log("Stopped tracking");
            }
        });


    </script>

    <?php
        include_once "includes/db_connect.php";

        if (isset($_GET["u"])) {
            $loc_user_id = filter_input(INPUT_GET, "u", FILTER_SANITIZE_NUMBER_INT);
        } else {
            $loc_user_id = filter_var($_SESSION["user_id"], FILTER_SANITIZE_NUMBER_INT);
        }

        $sql = "SELECT loc_lat, loc_long, dt_created FROM locations WHERE user_id = " . $loc_user_id;
        $result = $mysqli->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                // Create a marker
                echo "<script>L.marker([". $row["loc_lat"] .", ". $row["loc_long"] ."], {title: '". $_SESSION["username"] ."'}).addTo(mymap).bindPopup('<b>". $_SESSION["username"] ."</b><br><small>". $row["dt_created"] ."</small>');</script>";
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