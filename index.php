<?php
require_once 'includes/Router.php';

$router = new Router();
$router->setBasePath('/cabinet');

$router->map( 'GET', '/', function() {
  require __DIR__ . '/pages/home.php';
});

$router->map( 'GET|POST', '/login', function() {
  require __DIR__ . '/pages/login.php';
});

$router->map( 'GET', '/logout', function() {
  require __DIR__ . '/pages/logout.php';
});

$router->map( 'GET', '/home', function() {
  require __DIR__ . '/pages/home.php';
});

$router->map( 'GET', '/usagers/view', function() {
  require __DIR__ . '/pages/view_usagers.php';
});

$router->map( 'GET|POST', '/usagers/view/[*:id]', function($id) {
  require __DIR__ . '/pages/view_usager.php';
});

$router->map( 'GET|POST', '/usagers/add', function() {
  require __DIR__ . '/pages/add_usager.php';
});

$router->map( 'GET|POST', '/usagers/edit/[*:id]', function() {
  require __DIR__ . '/pages/edit_usager.php';
});

$router->map( 'GET|POST', '/usagers/delete/[*:id]', function() {
  require __DIR__ . '/pages/delete_usager.php';
});

$router->map( 'GET', '/medecins/view', function() {
  require __DIR__ . '/pages/view_medecins.php';
});

$router->map( 'GET|POST', '/medecins/view/[*:id]', function($id) {
  require __DIR__ . '/pages/view_medecin.php';
});

$router->map( 'GET|POST', '/medecins/add', function() {
  require __DIR__ . '/pages/add_medecin.php';
});

$router->map( 'GET|POST', '/medecins/edit/[*:id]', function() {
  require __DIR__ . '/pages/edit_medecin.php';
});

$router->map( 'GET|POST', '/medecins/delete/[*:id]', function() {
  require __DIR__ . '/pages/delete_medecin.php';
});

$router->map( 'GET', '/consultations/view', function() {
  require __DIR__ . '/pages/view_consultations.php';
});

$router->map( 'GET|POST', '/consultations/view/[*:id]', function($id) {
  require __DIR__ . '/pages/view_consultation.php';
});

$router->map( 'GET|POST', '/consultations/add', function() {
  require __DIR__ . '/pages/add_consultation.php';
});

$router->map( 'GET|POST', '/consultations/edit/[*:id]', function() {
  require __DIR__ . '/pages/edit_consultation.php';
});

$router->map( 'GET|POST', '/consultations/delete/[*:id]', function() {
  require __DIR__ . '/pages/delete_consultation.php';
});

$router->map( 'GET', '/home', function() {
  require __DIR__ . '/pages/home.php';
});

$router->map( 'GET', '/home', function() {
  require __DIR__ . '/pages/home.php';
});

$router->map( 'GET', '/stats', function() {
  require __DIR__ . '/pages/statistics.php';
});

$router->map( 'GET', '/download', function() {
  $file = 'cabinet.rar' . $_GET['cabinet.rar'];
  if (!file_exists($file)) {
    echo ("error: file not found");
    exit;
  }
  header('Content-Description: File Transfer');
  header('Content-Type: application/x-rar-compressed; charset=utf-8');
  header('Content-length: ' . filesize($file));
  header('Content-Disposition: attachment; filename=cabinet_medical_Thibault-SAINTURET_Lucas_OESLICK.rar');
  readfile($file);
  exit;
});

//$router->map( 'GET', '/users/view/[*:id]',"/pages/view_user.php",'user_page');

$match = $router->match();

if($match) {
  if(is_callable($match['target'])) {
    call_user_func_array( $match['target'], $match['params']); 
  }
  else require __DIR__ ."/{$match['target']}.php";
}
else {
  die("404");
}
?>