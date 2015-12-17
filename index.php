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
$body = array(
	'grant_type' => 'client_credentials',
	'client_id' => 'yourCLientId',
	'client_secret' => 'yourClientSecret'
);

$customHeaders = ['Content-type: application/x-www-form-urlencoded'];
$fleetlog = new \FleetlogAPI();
$resultBody = $fleetlog->request('token', 'POST', $body, $customHeaders);
echo json_encode($resultBody);

$fleetlog->setAccessToken($resultBody->access_token);
$vehicles =  $fleetlog->request('vehicles', 'GET');
echo json_encode($vehicles);
