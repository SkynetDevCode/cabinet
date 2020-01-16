<?php
$page_title = "Consulter les médecins";
require_once 'includes/init.php';
require_once 'includes/templates/header.php';
require_once 'includes/templates/nav.php';

if (!$user->isLoggedIn()) Redirect::to('/login');

$medecins = $db->query("SELECT * FROM medecin")->results();
?>
<main role="main" class="container">
  <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    <h1 class="display-5">Consulter les médecins</h1>
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
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($medecins as $m) {
              ?>
              <tr>
                <td><?= $m->civilite == 'F' ? "Femme" : "Homme" ?></td>
                <td><a href="<?= Config::get('root') ?>/medecins/view/<?= $m->id ?>"><?= $m->nom ?></td>
                <td><?= $m->prenom ?></td>
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