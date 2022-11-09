<?php

require '../vendor/autoload.php';

use Mia\Datafactory\Service\MiaDatafactoryService;

$pass = '';
$service = new MiaDatafactoryService($pass);

$channels = $service->getUpdatedChannels('20221108', '000000');

$fixture = $service->getChannel('deportes.futbol.mundial.fixture');

var_dump($fixture);
exit();