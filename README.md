fleetlog-php
===============

Fleetlog API wrapper


- Include the class in your PHP code
- Set your access_token to FleetlogAPI
- Make request

Installation
------------

**Normally:** Include TwitterAPIExchange.php in your application. 

**Composer:** Add to your composer.json file to have FleetlogAPI.php automatically imported into your vendors folder:

    {
        "require": {
            "fleetlog/fleetlog-php": "0.0.1"
        }
    }

Of course, you'll then need to run `php composer.phar update`.

How To Use
----------

#### Include the class file ####

```php
require_once('FleetlogAPI.php');
```

GET Request Example
-------------------
[GET] https://api.fleetlog.com.au/v2/trips?limit=1

```php
$settings = array(
	'oauth_access_token' => "your_access_token",
);

$requestMethod = 'GET';
$fleetlog = new FleetlogAPI($settings);
echo json_encode($fleetlog->request('trips', 'GET', '?limit=1'));
```