<?php

use ChargeBee\ChargeBee\Models\Environment;

Environment::configure("{site}", "{site_api_key}");

?>
-----
<?php

use ChargeBee\ChargeBee\Models\Environment;
$options = ['site' => "{site}", 'apiKey' => "{site_api_key}"];

$chargebee = new \Chargebee\ChargebeeClient($options);

?>
