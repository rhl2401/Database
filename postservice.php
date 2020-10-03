<?php
include_once "includes/db_connect.php";

$action = filter_input(INPUT_POST, "action", FILTER_SANITIZE_STRING);
$actionNoMatch = true;
$response = [];

$user_id = filter_input(INPUT_POST, "user_id", FILTER_SANITIZE_NUMBER_INT);


if ($action == "uploadLocation") {
    $actionNoMatch = false;

    $lat = filter_input(INPUT_POST, "lat", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $long = filter_input(INPUT_POST, "long", FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
    $response["lat"] = $lat;
    $response["long"] = $long;

    // Insert into DB
    $stmt = $mysqli->prepare("INSERT INTO locations (user_id, loc_lat, loc_long) VALUES (?, ?, ?)");
    $stmt->bind_param("idd", $user_id, $lat, $long);
    $response["success"] = $stmt->execute();
}

echo json_encode($response);