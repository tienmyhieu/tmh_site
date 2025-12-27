<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use lib\core\TmhDomain;
use lib\core\TmhEntityFactory;
use lib\core\TmhJson;
use lib\core\TmhLocale;
use lib\core\TmhRoute;
use lib\core\TmhRouteFactory;

require_once('lib/core/TmhDomain.php');
require_once('lib/core/TmhEntityFactory.php');
require_once('lib/core/TmhJson.php');
require_once('lib/core/TmhLocale.php');
require_once('lib/core/TmhRoute.php');
require_once('lib/core/TmhRouteFactory.php');

$json = new TmhJson();
$domain = new TmhDomain($json);
$locale = new TmhLocale($domain, $json);
$route = new TmhRoute($json);
$routeFactory = new TmhRouteFactory($locale, $route);
$entityFactory = new TmhEntityFactory($json, $routeFactory);
//echo "<pre>";
//print_r($entityFactory->create());
//echo "</pre>";
