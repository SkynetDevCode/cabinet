<?php
$page_title = "Déconnexion";
require_once 'includes/init.php';
require_once 'includes/templates/header.php';
require_once 'includes/templates/nav.php';

if(!$user->isLoggedIn()) Redirect::to('/home');

$user->logout();
?>

<main role="main" class="container">

  <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    <h1 class="display-4">Cabinet Médical</h1>
  </div>

  <div class="container">
    <div class="alert alert-success" role="alert">
      Vous avez bien été déconnecté de l'application.
    </div>
  </div>
  <meta http-equiv="refresh" content="3;URL=<?=Config::get('root')?>/login"> 
</main>