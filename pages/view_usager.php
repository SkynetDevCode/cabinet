<?php
$page_title = "Consulter un usager";
require_once 'includes/init.php';
require_once 'includes/templates/header.php';
require_once 'includes/templates/nav.php';

if (!$user->isLoggedIn()) Redirect::to('/login');

global $match;
$id = $match['params']['id'];

if ($id == null) Redirect::to('/users/view');
try {
  $usager = new Usager($id);
  $rdv = $usager->getRDV();
} catch (Exception $e) {
  Redirect::to('/users/view');
}
?>
<main role="main" class="container">
  <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    <h1 class="display-5">Consulter un usager</h1>
  </div>
  <div class="container-fluid">
    <div class="col-sm-12 col-md-6">
      <p>Civilite: <?= $usager->getInfo()->civilite == 'F' ? "Femme" : "Homme" ?></p>
      <p>Nom: <?= $usager->getInfo()->nom ?></p>
      <p>Prénom: <?= $usager->getInfo()->prenom ?></p>
      <p>Adresse: <?= $usager->getInfo()->adresse ?></p>
      <p>Ville: <?= $usager->getInfo()->ville ?></p>
      <p>CP: <?= $usager->getInfo()->cp ?></p>
      <p>Date de naissance: <?= $usager->getInfo()->date_naissance ?></p>
      <p>Lieu de naissance: <?= $usager->getInfo()->lieu_naissance ?></p>
      <p>Numéro de Sécurité Sociale: <?= $usager->getInfo()->numero_sc ?></p>
      <p>Medecin référent: <?= $usager->getMedecin() != null ? ($usager->getMedecin()->getInfo()->nom . ' ' . $usager->getMedecin()->getInfo()->prenom) : "Aucun" ?></p>
    </div>

    <div class="card">
      <h5 class="card-header">Rendez-vous</h5>
      <div class="card-body">
        <div class="table-responsive">
          <div class="alluinfo">&nbsp;</div>
          <div class="allutable">
            <table id="paginate" class='table table-hover table-list-search'>
              <thead>
                <tr>
                  <th>Civilite</th>
                  <th>Medecin</th>
                  <th>Date RDV</th>
                  <th>Heure RDV</th>
                  <th>Durée RDV</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($rdv as $r) {
                ?>
                  <tr>
                    <?php $m = new Medecin($r->id_m); ?>
                    <td><?= $m->getInfo()->civilite == 'F' ? "Femme" : "Homme" ?></td>
                    <td><a href="<?= Config::get('root') ?>/medecins/view/<?= $r->id_m ?>"><?= $m->getInfo()->nom . ' ' . $m->getInfo()->prenom ?></a></td>
                    <td><?= utf8_encode(strftime('%d %B %Y', strtotime($r->date_r))) ?></td>
                    <td><?= date('H\hi',strtotime($r->heure_r)) ?></td>
                    <td><?= $r->duree ?> minutes</td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div><br/>
  <div class="container-fluid">
    <a href="<?=Config::get('root')?>/usagers/edit/<?=$usager->getID()?>" class="btn btn-warning">Editer</a>
    <a href="<?=Config::get('root')?>/usagers/delete/<?=$usager->getID()?>" class="btn btn-danger">Supprimer</a>
    <a href="<?=Config::get('root')?>/usagers/view" class="btn btn-primary">Retourner à la liste</a>
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