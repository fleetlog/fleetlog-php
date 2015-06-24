<?php
ini_set('display_errors', 1);
require_once('FleetlogAPI.php');

$settings = array(
	'oauth_access_token' => ""
);

/** Perform a GET request and echo the response **/
$requestMethod = 'GET';
$fleetlog = new FleetlogAPI($settings);
echo json_encode($fleetlog->request('trips', $requestMethod, ''));

/**
 * Perform a POST request to obtain the access token and echo the response
 */
$requestMethod = 'POST';
$body = array(
	'username' => 'joe@doe.com',
	'password' => 'keyBoardCat',
	'grant_type' => 'password',
	'client_id' => 'clientId',
	'client_secret' => 'clientSecret',
);


$customHeaders = ['Content-type: application/x-www-form-urlencoded'];
$fleetlog = new FleetlogAPI($settings);
echo json_encode($fleetlog->request('token', 'POST', $body, $customHeaders));