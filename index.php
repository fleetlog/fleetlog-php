<?php
ini_set('display_errors', 1);
require_once('FleetlogAPI.php');

$settings = array(
	'oauth_access_token' => "",
);

/** Perform a GET request and echo the response **/
$requestMethod = 'GET';
$fleetlog = new FleetlogAPI($settings);
echo json_encode($fleetlog->request('trips', 'GET', '?limit=1'));