<?php
$page_title = "Voir les consultations";
require_once 'includes/init.php';
require_once 'includes/templates/header.php';
require_once 'includes/templates/nav.php';

if (!$user->isLoggedIn()) Redirect::to('/login');

$rdv = $db->query("SELECT * FROM rdv ORDER BY heure_r AND date_r DESC")->results();
?>
<main role="main" class="container">
  <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    <h1 class="display-5">Voir les consultations</h1>
  </div>

  <div class="container">
    <div class="table-responsive">
      <div class="alluinfo">&nbsp;</div>
      <div class="allutable">
        <table id="paginate" class='table table-hover table-list-search'>
          <thead>
            <tr>
              <th>Usager</th>
              <th>Médecin</th>
              <th>Date</th>
              <th>Heure</th>
              <th>Durée</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($rdv as $r) {
              ?>
              <tr>
              <?php 
              $u = new Usager($r->id_u);
              $m = new Medecin($r->id_m);
              
               ?>
                <td><a href="<?= Config::get('root') ?>/usagers/view/<?= $r->id_u ?>"><?= $u->getInfo()->nom . ' ' . $u->getInfo()->prenom ?></a></td>
                <td><a href="<?= Config::get('root') ?>/medecins/view/<?= $r->id_m ?>"><?= $m->getInfo()->nom . ' ' . $m->getInfo()->prenom ?></a></td>
                <td><a href="<?= Config::get('root') ?>/consultations/view/<?= $r->id ?>"><?= utf8_encode(strftime('%d %B %Y', strtotime($r->date_r))) ?></a></td>
                <td><a href="<?= Config::get('root') ?>/consultations/view/<?= $r->id ?>"><?= date('H\hi',strtotime($r->heure_r)); ?></a></td>
                <td><?= $r->duree. " minutes"; ?></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
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