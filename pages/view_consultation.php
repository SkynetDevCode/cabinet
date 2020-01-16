<?php
$page_title = "Voir une consultation";
require_once 'includes/init.php';
require_once 'includes/templates/header.php';
require_once 'includes/templates/nav.php';

if (!$user->isLoggedIn()) Redirect::to('/login');

global $match;
$id = $match['params']['id'];

if ($id == null) Redirect::to('/consultations/view');
try {
  $consult = new RDV($id);
  $medecin = $consult->getMedecin();
  $usager = $consult->getUsager();
} catch (Exception $e) {
  Redirect::to('/consultations/view');
}

$formated_datetime = "le " .utf8_encode(strftime('%d %B %Y', strtotime($consult->getInfo()->date_r)))." de ".date('H\hi',strtotime($consult->getInfo()->heure_r))." à ".date('H\hi',strtotime($consult->getInfo()->heure_r." + ".$consult->getInfo()->duree." minutes"));
?>
<main role="main" class="container">
  <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    <h1 class="display-5">Voir une consultation</h1>
  </div>
  <div class="container-fluid">
    <div class="col-sm-12 col-md-6">
      <p>Date et Heure de la consultation: <?= $formated_datetime ?></p>
      <p>Durée: <?= $consult->getInfo()->duree." minutes"?></p>
      <p>Patient: <a href="<?=Config::get('root')?>/usagers/view/<?=$usager->getID()?>"><?= $usager->getInfo()->nom.' '.$usager->getInfo()->prenom ?></a></p>
      <p>Médecin: <a href="<?=Config::get('root')?>/medecins/view/<?=$medecin->getID()?>"><?= $medecin->getInfo()->nom.' '.$medecin->getInfo()->prenom ?></a></p>
    </div>
  </div><br/>
  <div class="container-fluid">
    <a href="<?=Config::get('root')?>/consultations/edit/<?=$consult->getID()?>" class="btn btn-warning">Modifier</a>
    <a href="<?=Config::get('root')?>/consultations/delete/<?=$consult->getID()?>" class="btn btn-danger">Supprimer</a>
    <a href="<?=Config::get('root')?>/consultations/view" class="btn btn-primary">Retourner à la liste</a>
  </div>
</main>
<script type="text/javascript" src="<?= Config::get('root') ?>/assets/js/pagination/datatables.min.js"></script>
<script src="<?= Config::get('root') ?>/assets/js/jwerty.js"></script>
<script>
  $(document).ready(function() {
    $('#paginate').DataTable({
      "pageLength": 25,
      "stateSave": true,
      "aLengthMenu": [
        [25, 50, 100, -1],
        [25, 50, 100, "Tout"]
      ],
      "aaSorting": [],
      "language": {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
      }
    });
  });
</script>
<?php require_once 'includes/templates/footer.php'; ?>