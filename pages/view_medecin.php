<?php
$page_title = "Consulter un médecin";
require_once 'includes/init.php';
require_once 'includes/templates/header.php';
require_once 'includes/templates/nav.php';

if (!$user->isLoggedIn()) Redirect::to('/login');

global $match;
$id = $match['params']['id'];

if ($id == null) Redirect::to('/medecins/view');
try {
  $medecin = new Medecin($id);
  $rdv = $medecin->getRDV();
} catch (Exception $e) {
  Redirect::to('/users/view');
}
?>
<main role="main" class="container">
  <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    <h1 class="display-5">Consulter un médecin</h1>
  </div>
  <div class="container-fluid">
    <div class="col-sm-12 col-md-6">
      <p>Civilite: <?= $medecin->getInfo()->civilite == 'F' ? "Femme" : "Homme" ?></p>
      <p>Nom: <?= $medecin->getInfo()->nom ?></p>
      <p>Prénom: <?= $medecin->getInfo()->prenom ?></p>
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
                  <th>Patient</th>
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
                    <?php $u = new Usager($r->id_u); ?>
                    <td><?= $u->getInfo()->civilite == 'F' ? "Femme" : "Homme" ?></td>
                    <td><a href="<?= Config::get('root') ?>/usagers/view/<?= $r->id_u ?>"><?= $u->getInfo()->nom . ' ' . $u->getInfo()->prenom ?></a></td>
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
    <a href="<?=Config::get('root')?>/medecins/edit/<?=$medecin->getID()?>" class="btn btn-warning">Editer</a>
    <a href="<?=Config::get('root')?>/medecins/delete/<?=$medecin->getID()?>" class="btn btn-danger">Supprimer</a>
    <a href="<?=Config::get('root')?>/medecins/view" class="btn btn-primary">Retourner à la liste</a>
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