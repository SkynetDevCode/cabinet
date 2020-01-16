<?php
$page_title = "Ajouter une consultation";
require_once 'includes/init.php';
require_once 'includes/templates/header.php';
require_once 'includes/templates/nav.php';

if (!$user->isLoggedIn()) Redirect::to('/login');

$error = null;

if (Input::exists()) {
  $token = Input::get('csrf');
  if (!Token::check($token)) require_once 'token_error.php';
  if (isset($_POST['select_usager'])) {
    try {
      $user = new Usager(Input::get('usager'));
    } catch (Exception $e) {
      Redirect::to('/consultations/view');
    }
  }
  else if (isset($_POST['add_consult'])) {
    $user = Input::get('userid');
    $medecin = Input::get('medecin');
    $date = date('Y-m-d',strtotime(Input::get('date')));
    $heure = Input::get('heure');
    $duree = Input::get('duree');
    if ($user == null || $medecin == null || $date == null || $heure == null || $duree == null || $duree == "0") {
      $error = "Veuillez vérifier les champs.";
    } else {
      $r = RDV::checkRDV($date,$heure,$duree,$medecin,$user);
      if($r) {
        $result = RDV::add(array("date_r" => $date, "heure_r" => $heure, "duree" => $duree, "id_u" => $user, "id_m" => $medecin));
        if(!$result) $error = "Une erreur s'est produite lors de l'ajout de la consultation.";
      }
      else {
        $error = "L'horaire de la consultation ne peut pas en chevaucher une autre.";
      }
    }
  }
}
?>
<main role="main" class="container">
  <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    <h1 class="display-5">Ajouter une consultation</h1>
  </div>

  <div class="container">
    <form action="" method="post">
      <?php if ($error != null) { ?><div class="alert alert-danger"><?= $error ?></div><?php } ?>
      <?php if(!isset($_POST['select_usager'])) { ?>
      <div class="form-group">
        <label for="inputMed">Usager</label>
        <?php $usager = $db->query("SELECT * FROM usager;")->results(); ?>
        <select <?php if(isset($_POST['select_usager'])) { echo 'disabled="disabled"'; } ?> class="form-control" name="usager" id="inputMed" required>
          <?php foreach ($usager as $u) { ?>
            <option value="<?= $u->id ?>"><?= $u->nom . " " . $u->prenom ?></option>
          <?php } ?>
        </select>
      </div>
      <button type="submit" name="select_usager" class="btn btn-primary">Sélectionner ce usager</button>
      <?php } ?>
      <?php if(isset($_POST['select_usager'])) { ?>
      <div class="form-group">
        <input type="hidden" name="userid" value="<?=$user->getID()?>">
        <label for="inputUsager">Usager: <?=($user->getInfo()->civilite == 'F' ? 'Monsieur' : 'Madame').' '.$user->getInfo()->nom.' '.$user->getInfo()->prenom?></label>
      </div>
      <div class="form-group">
        <label for="inputMed">Médecin</label>
        <?php $medecin = $db->query("SELECT * FROM medecin;")->results(); ?>
        <select class="form-control" name="medecin" id="inputMed" required>
          <?php foreach ($medecin as $med) { ?>
          <option <?php if($user->getInfo()->id_m == $med->id) { echo 'selected="selected"'; } ?> value="<?= $med->id ?>"><?= $med->nom . " " . $med->prenom ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="inputTime">Date</label>
          <input type="text" name="date" id="datepicker" class="form-control" required>
        </div>
        <div class="form-group col-md-6">
          <label for="inputDate">Heure</label>
          <input type="text" name="heure" id="timepicker" class="form-control" required>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="inputNum">Durée (en minutes)</label>
          <div class="input-group number-spinner">
            <span class="input-group-btn">
              <button type="button" class="btn btn-default" data-dir="dwn"><span class="glyphicon glyphicon-minus"></span></button>
            </span>
            <input type="text" name="duree" class="form-control text-center" value="30">
            <span class="input-group-btn">
              <button type="button" class="btn btn-default" data-dir="up"><span class="glyphicon glyphicon-plus"></span></button>
            </span>
          </div>
        </div>
      </div>
      <button type="submit" name="add_consult" class="btn btn-primary">Ajouter une consultation</button>
      <?php } ?>
      <input type="hidden" name="csrf" value="<?= Token::generate(); ?>">
    </form>
  </div>
</main>
<script type="text/javascript">
  $(function () {
      $('#timepicker').datetimepicker({
          format: 'LT',
          format : 'HH:mm'
      });
      $('#datepicker').datepicker();
  });
  $(document).on('click', '.number-spinner button', function () {    
	var btn = $(this),
		oldValue = btn.closest('.number-spinner').find('input').val().trim(),
		newVal = 0;
	
	if (btn.attr('data-dir') == 'up') {
		newVal = parseInt(oldValue) + 10;
	} else {
		if (oldValue > 1) {
			newVal = parseInt(oldValue) - 10;
		} else {
			newVal = 1;
		}
	}
	btn.closest('.number-spinner').find('input').val(newVal);
});
</script>
<?php require_once 'includes/templates/footer.php'; ?>