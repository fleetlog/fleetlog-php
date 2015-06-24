<?php

/**
 * Fleetlog-API : Simple PHP wrapper for the v2 API
 *
 * PHP version 5.3.10
 *
 * @package  Fleetlog-API
 * @author   Viktor Sincak <viktor@fleetlog.com.au>
 * @license  Apache-2.0
 * @version  0.0.2
 * @link     https://github.com/fleetlog/fleetlog-php
 */
class FleetlogAPI
{
	/**
	 * @var string
	 */
	private $oauth_access_token;

	/**
	 * @var array
	 */
	private $postfields;

	/**
	 * @var string
	 */
	private $getfield;

	/**
	 * @var string
	 */
	private $baseUrl;

	/**
	 * @var string
	 */
	public $url;


	/**
	 * @var string
	 */
	public $requestMethod;

	/**
	 * Create the API access object. Requires an array of settings::
	 * oauth access token
	 * These are all available by creating your own application on fleetog.com.au
	 * Requires the cURL library
	 *
	 * @throws \Exception When cURL isn't installed or incorrect settings parameters are provided
	 *
	 * @param array $settings
	 */
	public function __construct(array $settings)
	{
		if (!in_array('curl', get_loaded_extensions()))
		{
			throw new Exception('You need to install cURL, see: http://curl.haxx.se/docs/install.html');
		}

		if (!isset($settings['oauth_access_token']))
		{
			throw new Exception('Make sure you are passing in the correct parameters');
		}

		$this->oauth_access_token = $settings['oauth_access_token'];
		$this->baseUrl = 'https:/api.fleetlog.com.au/v2/';
	}

	/**
	 * Set postfields array, example: array('screen_name' => 'asd')
	 *
	 * @param array $array Array of parameters to send to API
	 *
	 * @throws \Exception When you are trying to set both get and post fields
	 *
	 * @return FleetlogAPI Instance of self for method chaining
	 */
	public function setPostfields(array $array)
	{
		if (!is_null($this->getGetfield()))
		{
			throw new Exception('You can only choose get OR post fields.');
		}

		if (isset($array['status']) && substr($array['status'], 0, 1) === '@')
		{
			$array['status'] = sprintf("\0%s", $array['status']);
		}

		$this->postfields = $array;

		return $this;
	}

	/**
	 * Set getfield string, example: '?screen_name=J7mbo'
	 *
	 * @param string $string Get key and value pairs as string
	 *
	 * @throws \Exception
	 *
	 * @return \FleetlogAPI Instance of self for method chaining
	 */
	public function setGetfield($string)
	{
		if (!is_null($this->getPostfields()))
		{
			throw new Exception('You can only choose get OR post fields.');
		}

		$getfields = preg_replace('/^\?/', '', explode('&', $string));
		$params = array();

		foreach ($getfields as $field)
		{
			if ($field !== '')
			{
				list($key, $value) = explode('=', $field);
				$params[$key] = $value;
			}
		}

		$this->getfield = '?' . http_build_query($params);

		return $this;
	}

	/**
	 * Get getfield string (simple getter)
	 *
	 * @return string $this->getfields
	 */
	public function getGetfield()
	{
		return $this->getfield;
	}

	/**
	 * Get postfields array (simple getter)
	 *
	 * @return array $this->postfields
	 */
	public function getPostfields()
	{
		return $this->postfields;
	}

	/**
	 * Resets the fields to allow a new query
	 * with different method
	 */
	public function resetFields() {
		$this->postfields = null;
		$this->getfield = null;
		$this->url = '';
		return $this;
	}

	/**
	 * Perform the actual data retrieval from the API
	 *
	 * @param array   $curlOptions Additional Curl options for this request
	 *
	 * @throws \Exception
	 *
	 * @return string json
	 */
	public function performRequest($curlOptions = array())
	{

		$headers =  ['Content-type: application/json', 'Authorization: Bearer '.$this->oauth_access_token];

		$getfield = $this->getGetfield();
		$postfields = $this->getPostfields();

		$options = array(
				CURLOPT_HTTPHEADER => $headers,
				CURLOPT_HEADER => false,
				CURLOPT_URL => $this->baseUrl.$this->url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_TIMEOUT => 10,
			) + $curlOptions;

		if (!is_null($postfields))
		{
			$options[CURLOPT_POSTFIELDS] = http_build_query($postfields);
		}
		else
		{
			if ($getfield !== '')
			{
				$options[CURLOPT_URL] .= $getfield;
			}
		}

		$feed = curl_init();
		curl_setopt_array($feed, $options);
		$json = curl_exec($feed);

		if (($error = curl_error($feed)) !== '')
		{
			curl_close($feed);

			throw new \Exception($error);
		}

		curl_close($feed);

		// reset fields
		$this->resetFields();
		return json_decode($json);
	}


	/**
	 * Helper method to perform our request
	 *
	 * @param string $url
	 * @param string $method
	 * @param string $data
	 * @param array  $curlOptions
	 *
	 * @throws \Exception
	 *
	 * @return string The json response from the server
	 */
	public function request($url, $method = 'get', $data = null, $curlOptions = array())
	{
		$this->url = $url;

		if (strtolower($method) === 'get')
		{
			$this->setGetfield($data);
		}
		else
		{
			$this->setPostfields($data);
		}

		return $this->performRequest($curlOptions);
	}
}