<?php
$page_title = "Statistiques";
require_once 'includes/init.php';
require_once 'includes/templates/header.php';
require_once 'includes/templates/nav.php';

if (!$user->isLoggedIn()) Redirect::to('/login');


$response = $db->query("SELECT civilite,date_naissance FROM usager")->results();

$moinsde25F = 0;
$entre25et50F = 0;
$plusde50F = 0;

$moinsde25M = 0;
$entre25et50M = 0;
$plusde50M = 0;

foreach ($response as $r) {
  $dob = new DateTime($r->date_naissance);
  $now = new DateTime();
  $difference = $now->diff($dob);

  if ($r->civilite == 'F') {
    if ($difference->y < 25) $moinsde25F++;
    else if ($difference->y >= 25 && $difference->y <= 50) $entre25et50F++;
    else if ($difference->y > 50) $plusde50F++;
  } else {
    if ($difference->y < 25) $moinsde25M++;
    else if ($difference->y >= 25 && $difference->y <= 50) $entre25et50M++;
    else if ($difference->y > 50) $plusde50M++;
  }
}

?>
<main role="main" class="container">
  <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    <h1 class="display-4">Accueil</h1>
    <p class="lead">Consulter les prochaines consultations à venir</p>
  </div>

  <div class="container">
    <div class="card">
      <div class="card-header">Répartition des usagers selon leurs sexe et leur âge.</div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th scope="col">Tranche d'âge</th>
                <th scope="col">Nombre d'Hommes</th>
                <th scope="col">Nombre de Femmes</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th scope="row">Moins de 25 ans</th>
                <td><?= $moinsde25M ?></td>
                <td><?= $moinsde25F ?></td>
              </tr>
              <tr>
                <th scope="row">Entre 25 et 50 ans</th>
                <td><?= $entre25et50M ?></td>
                <td><?= $entre25et50F ?></td>
              </tr>
              <tr>
                <th scope="row">Plus de 50 ans</th>
                <td><?= $plusde50M ?></td>
                <td><?= $plusde50F ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <br/>

    <?php $res = $db->query('SELECT medecin.nom,medecin.prenom, SUM(rdv.duree) AS duree_totale FROM `rdv`, `medecin` WHERE medecin.id = rdv.id_m GROUP BY medecin.nom, medecin.prenom;')->results(); ?>
    <div class="card">
      <div class="card-header">Durée totale des consultations effectuées par chaque médecin.</div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th scope="col">Nom</th>
                <th scope="col">Prénom</th>
                <th scope="col">Durée totale (en heures)</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($res as $r) { ?>
              <tr>
                <td><?=$r->nom?></td>
                <td><?=$r->prenom?></td>
                <td><?= date('H:i', mktime(0,$r->duree_totale)); ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</main>
<?php require_once 'includes/templates/footer.php'; ?>