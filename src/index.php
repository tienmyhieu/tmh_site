<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use lib\core\TmhDatabase;
use lib\core\TmhDomain;
use lib\core\TmhEntityFactory;
use lib\core\TmhJson;
use lib\core\TmhLocale;
use lib\core\TmhRoute;
use lib\core\TmhRouteFactory;
use lib\html\TmhHtmlTransform;
use lib\transformers\TmhImageGroup;
use lib\transformers\TmhRouteTransformer;
use lib\transformers\TmhTransform;

require_once('lib/core/TmhDatabase.php');
require_once('lib/core/TmhDomain.php');
require_once('lib/core/TmhEntityFactory.php');
require_once('lib/core/TmhJson.php');
require_once('lib/core/TmhLocale.php');
require_once('lib/core/TmhRoute.php');
require_once('lib/core/TmhRouteFactory.php');
require_once('lib/html/TmhHtmlTransform.php');
require_once('lib/transformers/TmhImageGroup.php');
require_once('lib/transformers/TmhRouteTransformer.php');
require_once('lib/transformers/TmhTransform.php');

$json = new TmhJson();
$database = new TmhDatabase($json);
$domain = new TmhDomain($json);
$locale = new TmhLocale($domain, $json);
$route = new TmhRoute($json);
$routeFactory = new TmhRouteFactory($locale, $route);
$entityFactory = new TmhEntityFactory($json, $routeFactory);
$entity = $entityFactory->create();
$imageGroupTransformer =  new TmhImageGroup($database, $locale);
$routeTransformer = new TmhRouteTransformer($locale, $route);
$transformer =  new TmhTransform($imageGroupTransformer, $locale, $routeTransformer);
$htmlTransformer = new TmhHtmlTransform();
$transformedEntity = $transformer->transform($entity);
echo "<pre>";
$htmlTransformer->transform($transformedEntity);
//print_r($transformedEntity);
//print_r($entity);
echo "</pre>";
