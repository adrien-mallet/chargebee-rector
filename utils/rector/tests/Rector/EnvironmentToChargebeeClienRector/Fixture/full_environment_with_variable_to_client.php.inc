<?php

use ChargeBee\ChargeBee\Models\Environment;

$connectTimeoutInMillis = 42;
$requestTimeoutInMillis = 42;
Environment::configure("{site}", "{site_api_key}");
Environment::updateConnectTimeoutInSecs($connectTimeoutInMillis);
Environment::updateRequestTimeoutInSecs($requestTimeoutInMillis);
Environment::setUserAgentSuffix("{user_agent_suffix}");

?>
-----
<?php

use ChargeBee\ChargeBee\Models\Environment;

$connectTimeoutInMillis = 42;
$requestTimeoutInMillis = 42;
$options = ['site' => "{site}", 'apiKey' => "{site_api_key}", 'connectTimeoutInMillis' => $connectTimeoutInMillis * 1000, 'requestTimeoutInMillis' => $requestTimeoutInMillis * 1000, 'userAgentSuffix' => "{user_agent_suffix}"];
$chargebee = new \Chargebee\ChargebeeClient($options);

?>
