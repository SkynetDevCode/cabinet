<?php
$page_title = "Accueil";
require_once 'includes/init.php';
require_once 'includes/templates/header.php';
require_once 'includes/templates/nav.php';

if(!$user->isLoggedIn()) Redirect::to('/login');

$rdvs = $db->query('SELECT * FROM rdv ORDER BY heure_r AND date_r DESC LIMIT 3')->results();
?>
<main role="main" class="container">
<div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
  <h1 class="display-4">Accueil</h1>
  <p class="lead">Consulter les prochaines consultations à venir</p>
</div>

<div class="container">
  <div class="card-deck mb-3 text-center">
  <?php foreach($rdvs as $rdv) { 
    $medecin = new Medecin($rdv->id_m);
    $usager = new Usager($rdv->id_u);
    ?>
    <div class="card mb-4 shadow-sm">
      <div class="card-header">
        <h4 class="my-0 font-weight-normal">Consultation</h4>
      </div>
      <div class="card-body">
        <h1 class="card-title pricing-card-title"><small class="text-muted"><?= $usager->getInfo()->civilite == 'F' ? "Mme" : "Mr" ?></small> <?=$usager->getInfo()->prenom." ".$usager->getInfo()->nom?></h1>
        <ul class="list-unstyled mt-3 mb-4">
          <li><?= ($medecin->getInfo()->civilite == 'F' ? "Mme " : "Mr ").$medecin->getInfo()->prenom." ".$medecin->getInfo()->nom?></li>
          <li><?= utf8_encode(strftime('%d %B %Y', strtotime($rdv->date_r))) ?></li>
          <li><?= date('H\hi',strtotime($rdv->heure_r))?> - <?=date('H\hi',strtotime($rdv->heure_r." + ".$rdv->duree." minutes"))?></li>
          <li><?= $rdv->duree." minutes"?></li>
        </ul>
        <a href="<?=Config::get('root')?>/consultations/view/<?=$rdv->id?>" class="btn btn-lg btn-block btn-primary">Voir</a>
      </div>
    </div>
  <?php } ?>
  </div>
</main>
<?php require_once 'includes/templates/footer.php'; ?>