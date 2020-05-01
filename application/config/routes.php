<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = 'page/show/1';
$route['velkommen'] = "page/show/1";
$route['faciliteter'] = "page/show/2";
$route['kollegielivet'] = "page/show/3";
$route['vision'] = "page/show/22";
$route['legater'] = "page/show/4";
$route['pylon'] = "pylon/show";
$route['optagelse'] = "optagelse";
$route['kontakt'] = "page/show/21";
$route['admin'] = "admin";

$route['faciliteter/vaerelse'] = "page/show/10";
$route['faciliteter/faellesomraede'] = "page/show/11";
$route['faciliteter/kokken'] = "page/show/12";

$route['legater/modtagne'] = "page/show/18";


$route['kollegielivet/historie'] = "page/show/14";
$route['kollegielivet/aaretsgang'] = "page/show/15";
$route['kollegielivet/alumnerne'] = "page/show/20";
$route['kollegielivet/selvstyre'] = "page/show/16";
$route['kollegielivet/bestyrelse'] = "page/show/17";
$route['404_override'] = '';

//Gahk intern
$route['nyintern/mydata'] = "intern/mydata";
$route['nyintern/alumneliste/configure'] = "intern/alumneliste/configure";
$route['nyintern/alumneliste/closeNetwork'] = "intern/alumneliste/closeNetwork";
$route['nyintern/alumneliste/update'] = "intern/alumneliste/update";
$route['nyintern']  = "intern/dashboard";
$route['nyintern/(:any)']  = "intern/$1";
$route['nyintern/(:any)/']  = "intern/$1/$2";


/* End of file routes.php */
/* Location: ./application/config/routes.php */
