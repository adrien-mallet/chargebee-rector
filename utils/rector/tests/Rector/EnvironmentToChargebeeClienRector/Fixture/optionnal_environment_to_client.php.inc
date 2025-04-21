<?php

use ChargeBee\ChargeBee\Models\Environment;

$env = [
    'chargebee_connect_timeout' => 42,
    'chargebee_request_timeout' => 42,
];
$chargebeeSite = '{site}';
$chargebeePrivateApiKey = '{site_api_key}';

Environment::configure($chargebeeSite, $chargebeePrivateApiKey);

if (isset($env['chargebee_connect_timeout'])) {
    Environment::updateConnectTimeoutInSecs($env['chargebee_connect_timeout']);
}

if (isset($env['chargebee_request_timeout'])) {
    Environment::updateRequestTimeoutInSecs($env['chargebee_request_timeout']);
}

?>
-----
<?php

use ChargeBee\ChargeBee\Models\Environment;

$env = [
    'chargebee_connect_timeout' => 42,
    'chargebee_request_timeout' => 42,
];
$chargebeeSite = '{site}';
$chargebeePrivateApiKey = '{site_api_key}';
$options = [
    'site' => $chargebeeSite,
    'apiKey' => $chargebeePrivateApiKey,
];
if (isset($env['chargebee_connect_timeout'])) {
    $options['connectTimeoutInMillis'] = $env['chargebee_connect_timeout'] * 1000;
}

if (isset($env['chargebee_request_timeout'])) {
    $options['requestTimeoutInMillis'] = $env['chargebee_request_timeout'] * 1000;
}

$chargebee = new \Chargebee\ChargebeeClient($options);

?>
