<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$general_settings = $this->config->item('general_settings');
$routes = $this->config->item('routes');

$route['default_controller'] = 'home_controller';
$route['404_override'] = 'home_controller/error_404';
$route['translate_uri_dashes'] = FALSE;
//$route['index'] = 'home_controller/index';
$route['error-404'] = 'home_controller/error_404';

$route['monthly-list']['GET'] = 'home_controller/monthlyList';
$route['preview/(:any)']['GET'] = 'home_controller/preview/$1';

$route['api']['GET'] = 'api_controller';
//$route['api/news']['GET'] = 'api/news_controller';
$route['api/news']['GET'] = 'api/news_controller';


//หาปี-เดือน
//$1=yyyy=2022 คศ., $2=mm=11
//$route['api/monthlylist/(:any)/(:any)']['GET'] = 'api/monthlylist_controller/$1/$2';
//$1=yyyy=2022 คศ., $2=mm=11
//monthlylist?crty=2023&crtm=04&limitpermonth=10
//$route['api/monthlylist/']['GET'] = 'api/monthlylist_controller/';
$route['api/monthlylist']['GET'] = 'api/monthlylist_controller';

//coverageitem/10 //$1=item_sets id  สิ่งแวดล้อม collection
$route['api/coverageitem/(:any)']['GET'] = 'api/coverageitem_controller/$1';

//api/items?id=19   ข้อมูลรายการ
$route['api/item']['GET'] = 'api/item_controller';

//popularlist  เนื้อหายอดนิยม
//$route['api/popularlist/(:any)']['GET'] = 'api/popularlist_controller/$1';
//?toplimit=10
$route['api/popularlist']['GET'] = 'api/popularlist_controller';
//flashback
//หาปี-เดือน
//  20 ปีที่แล้ว = 2023-20=2003
//  เดือนปัจจุบัน =เมษายน =  4
//$1=yyyy=2022-03 คศ., $2=mm=11
//$1=เลขย้อนหลัง นับจากปีปัจจุบัน 5 = 2023-5 => 2018, $2=เดือน ถ้า 00 คิดเป็นทั้งปี , $3=per_page
//$route['api/flashbacklist/(:any)/(:any)/(:any)']['GET'] = 'api/flashbacklist_controller/$1/$2/$3';
//skipy=เลขย้อนหลัง นับจากปีปัจจุบัน 5 = 2023-5 => 2018, skipm=เดือน ถ้า 00 คิดเป็นทั้งปี , limitperpage=per_page
//flashbacklist?skipy=5&skipm=04&limitperpage=10
$route['api/flashbacklist']['GET'] = 'api/flashbacklist_controller';
//http://localhost/learnci/api/news/index/?id=3
//หรือ
//http://localhost/learnci/api/news/index/id/3
//letsgolist?limit=10
$route['api/letsgolist']['GET'] = 'api/letsgolist_controller';

//relations?limit=10
$route['api/relations']['GET'] = 'api/relations_controller';

//relations?limit=10
$route['api/archivelist']['GET'] = 'api/archivelist_controller';

//http://localhost/omekaapi3/api/archivelistfilter?page=1&limitperpage=10&cates=0
$route['api/archivelistfilter']['GET'] = 'api/archivelistfilter_controller';

$route['api/archivegroup']['GET'] = 'api/archivegroup_controller';


//ลองสำรวจจากชนิดของสื่อที่หลากหลาย
$route['api/statlist']['GET'] = 'api/statlist_controller';

//เนื้อหาโดดเด่น ที่คัดสรรจากทีมงาน

//หาปี-เดือน
//$1=yyyy=2022 คศ., $2=mm=11
//$route['api/monthlylist/(:any)/(:any)']['GET'] = 'api/monthlylist_controller/$1/$2';
//$1=yyyy=2022 คศ., $2=mm=11
//monthlylist?crty=2023&crtm=04&limitpermonth=10
//$route['api/monthlylist/']['GET'] = 'api/monthlylist_controller/';
//?revm=4&limitpermonth=10  หรือ ถ้า 0 คือทั้งหมด
$route['api/staffpicks']['GET'] = 'api/staffpicks_controller';

$route['api/popularall']['GET'] = 'api/popularall_controller';

//คอลเลกชั่น
//$route['api/itemsetall']['GET'] = 'api/itemsetall_controller';
$route['api/archivegroupfilter']['GET'] = 'api/archivegroupfilter_controller';

//properties
$route['api/archiveproperties']['GET'] = 'api/archiveproperties_controller';

//หารายการทั้งหมด
$route['api/archivelistall']['GET'] = 'api/archivelistall_controller';

//หารายการ ทั้งหมด ต่อหน้า

$route['api/archivelistallpage']['GET'] = 'api/archivelistallpage_controller';
/*
$route[$routes->posts]['GET'] = 'home_controller/posts';
$route[$routes->gallery_album . '/(:num)']['GET'] = 'home_controller/gallery_album/$1';
$route[$routes->tag . '/(:any)']['GET'] = 'home_controller/tag/$1';
$route[$routes->reading_list]['GET'] = 'home_controller/reading_list';
$route[$routes->search]['GET'] = 'home_controller/search';


//rss routes
$route[$routes->rss_feeds]['GET'] = 'home_controller/rss_feeds';
$route['rss/latest-posts']['GET'] = 'home_controller/rss_latest_posts';
$route['rss/category/(:any)']['GET'] = 'home_controller/rss_by_category/$1';
$route['rss/author/(:any)']['GET'] = 'home_controller/rss_by_user/$1';

//auth routes
$route[$routes->register]['GET'] = 'auth_controller/register';
$route[$routes->change_password]['GET'] = 'auth_controller/change_password';
$route[$routes->forgot_password]['GET'] = 'auth_controller/forgot_password';
$route[$routes->reset_password]['GET'] = 'auth_controller/reset_password';
$route['connect-with-facebook'] = 'auth_controller/connect_with_facebook';
$route['facebook-callback'] = 'auth_controller/facebook_callback';
$route['connect-with-google'] = 'auth_controller/connect_with_google';
$route['connect-with-vk'] = 'auth_controller/connect_with_vk';


*/
$route['(:any)/(:any)']['GET'] = 'home_controller/subcategory/$1/$2';
$route['(:any)']['GET'] = 'home_controller/any/$1';
