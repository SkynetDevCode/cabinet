<?php
$page_title = "Connexion";
require_once 'includes/init.php';
require_once 'includes/templates/header.php';
require_once 'includes/templates/nav.php';

?>

<main role="main" class="container">

  <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    <h1 class="display-4">Cabinet Médical</h1>
  </div>

  <div class="container">
    <div class="alert alert-danger" role="alert">
    Une erreur s'est produite. Veuillez recharger la page svp.
    </div>
    <a class="btn btn-primary" href="javascript:history.back()">Retour</a>
  </div>
</main>
<?php die(); ?>