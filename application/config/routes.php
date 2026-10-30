<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| URI ROUTING
|--------------------------------------------------------------------------
|
| Routing dasar aplikasi CMS Company Profile.
|
*/

$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['home'] = 'home/index';

$route['admin'] = 'admin/dashboard';
$route['admin/dashboard'] = 'admin/dashboard';
// $route['admin/(:any)'] = 'admin/$1';

$route['page/(:any)'] = 'page/detail/$1';

$route['service'] = 'service/index';
$route['service/(:any)'] = 'service/detail/$1';

$route['product'] = 'product/index';
$route['product/(:any)'] = 'product/detail/$1';

$route['portfolio'] = 'portfolio/index';
$route['portfolio/(:any)'] = 'portfolio/detail/$1';

$route['team'] = 'team/index';
$route['team/(:any)'] = 'team/detail/$1';

$route['testimonial'] = 'testimonial/index';
$route['testimonial/(:any)'] = 'testimonial/detail/$1';

$route['client'] = 'client/index';
$route['client/(:any)'] = 'client/detail/$1';

$route['news'] = 'news/index';
$route['news/(:any)'] = 'news/detail/$1';

// Sitemap routes
$route['sitemap.xml'] = 'sitemap/index';
$route['sitemap'] = 'sitemap/index';
$route['sitemaps.xml'] = 'sitemap/sitemaps';
