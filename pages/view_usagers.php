<?php
$page_title = "Consulter les usagers";
require_once 'includes/init.php';
require_once 'includes/templates/header.php';
require_once 'includes/templates/nav.php';

if (!$user->isLoggedIn()) Redirect::to('/login');

$users = $db->query("SELECT id,civilite,nom,prenom,adresse,cp,date_naissance,numero_sc FROM usager")->results();
?>
<main role="main" class="container">
  <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    <h1 class="display-5">Consulter les usagers</h1>
  </div>

  <div class="container">
    <div class="table-responsive">
      <div class="alluinfo">&nbsp;</div>
      <div class="allutable">
        <table id="paginate" class='table table-hover table-list-search'>
          <thead>
            <tr>
              <th>Civilite</th>
              <th>Nom</th>
              <th>Prénom</th>
              <th>Adresse</th>
              <th>Date Naissance</th>
              <th>Numéro de sécurité sociale</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($users as $u) {
              ?>
              <tr>
                <td><?= $u->civilite ?></td>
                <td><a href="<?= Config::get('root') ?>/usagers/view/<?= $u->id ?>"><?= $u->nom ?></td>
                <td><?= $u->prenom ?></td>
                <td><?= $u->adresse ?> (<?= $u->cp ?>)</td>
                <td><?= utf8_encode(strftime('%d %B %Y', strtotime($u->date_naissance))) ?></td>
                <td><?= $u->numero_sc ?></td>
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