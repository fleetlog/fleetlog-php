<?php


class FleetlogAPITest extends \PHPUnit_Framework_TestCase
{

	public function testCanBeConstructed()
	{
		$f = new \FleetlogAPI();
		$this->assertInstanceOf(FleetlogAPI::class, $f);

		return $f;
	}

	public function testCanBeConstructedWithArray()
	{

		$f = new \FleetlogAPI(array());
		$this->assertInstanceOf(FleetlogAPI::class, $f);

		return $f;
	}

	public function testBadClientCredentials()
	{
		$body = array(
			'grant_type' => 'client_credentials',
			'client_id' => 'badClientId',
			'client_secret' => 'badclientSecret'
		);

		$customHeaders = ['Content-type: application/x-www-form-urlencoded'];
		$fleetlog = new \FleetlogAPI();
		$resultBody = $fleetlog->request('token', 'POST', $body, $customHeaders);
		$this->assertArrayHasKey('message',json_decode(json_encode($resultBody), true));
	}

	public function testRequestWithoutCustomHeaders()
	{
		$fleetlog = new \FleetlogAPI();
		$resultBody = $fleetlog->request('token', 'POST');
	}

	public function testSetPostfieldsWithNull()
	{
		$fleetlog = new \FleetlogAPI();
		$fleetlog->setPostfields(NULL);
	}

	public function testSetPostfields()
	{
		$fleetlog = new \FleetlogAPI();
		$fleetlog->setPostfields(array("something"=> true));
		$postFields = $fleetlog->getPostfields();
		$this->assertArrayHasKey("something", $postFields);
	}

	public function testSetGetfields()
	{
		$fleetlog = new \FleetlogAPI();
		$fleetlog->setGetfield("something=true");
		$getFields = $fleetlog->getGetfield();
		$this->assertContains("something=true", $getFields);
	}

	public function testSetGetfieldsWithNull()
	{
		$fleetlog = new \FleetlogAPI();
		$fleetlog->setGetfield(NULL);
	}

	public function testGetPostfieldsWithNull()
	{
		$fleetlog = new \FleetlogAPI();
		$fleetlog->getPostfields();
	}

	public function testGetGetfieldsWithNull()
	{
		$fleetlog = new \FleetlogAPI();
		$fleetlog->getGetfield();
	}

	public function testShouldRemoveFieldsAfterRequest()
	{
		$fleetlog = new \FleetlogAPI();
		$fleetlog->setGetfield("something=true");
		$fleetlog->request('trips', 'GET');
		$getFields = $fleetlog->getGetfield();
		$this->assertNull($getFields);
	}

	public function testShouldRemoveFieldsAfterRequestPost()
	{
		$fleetlog = new \FleetlogAPI();
		$fleetlog->setPostfields(array("something"=> true));
		$fleetlog->request('vehicles', 'POST');
		$postFields = $fleetlog->getPostfields();
		$this->assertNull($postFields);
	}

//	public function testGoodClientCredentials()
//	{
//		$body = array(
//			'grant_type' => 'client_credentials',
//			'client_id' => 'yourCLientId',
//			'client_secret' => 'yourClientSecret'
//		);
//
//		$customHeaders = ['Content-type: application/x-www-form-urlencoded'];
//		$fleetlog = new \FleetlogAPI();
//		$resultBody = $fleetlog->request('token', 'POST', $body, $customHeaders);
//		$this->assertArrayHasKey('access_token',json_decode(json_encode($resultBody), true));
//	}
//
//	public function testRequest()
//	{
//		$body = array(
//			'grant_type' => 'client_credentials',
//			'client_id' => 'yourCLientId',
//			'client_secret' => 'yourClientSecret'
//		);
//
//		$customHeaders = ['Content-type: application/x-www-form-urlencoded'];
//		$fleetlog = new \FleetlogAPI();
//		$resultBody = $fleetlog->request('token', 'POST', $body, $customHeaders);
//		$fleetlog->setAccessToken($resultBody->access_token);
//		$tripsBody =  $fleetlog->request('vehicles', 'GET');
//		$this->assertArrayHasKey('data',json_decode(json_encode($tripsBody), true));
//	}
}
