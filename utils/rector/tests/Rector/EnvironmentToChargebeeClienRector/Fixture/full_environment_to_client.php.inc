<?php

use ChargeBee\ChargeBee\Models\Environment;

Environment::configure("{site}", "{site_api_key}");
Environment::updateConnectTimeoutInSecs(42);
Environment::updateRequestTimeoutInSecs(42);
Environment::setUserAgentSuffix("{user_agent_suffix}");

?>
-----
<?php

use ChargeBee\ChargeBee\Models\Environment;
$options = ['site' => "{site}", 'apiKey' => "{site_api_key}", 'connectTimeoutInMillis' => 42 * 1000, 'requestTimeoutInMillis' => 42 * 1000, 'userAgentSuffix' => "{user_agent_suffix}"];

$chargebee = new \Chargebee\ChargebeeClient($options);

?>
