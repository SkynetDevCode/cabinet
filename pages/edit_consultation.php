<?php
$page_title = "Editer une consultation";
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

$error = null;

if (Input::exists()) {
  $token = Input::get('csrf');
  if (!Token::check($token)) require_once 'token_error.php';
  if (isset($_POST['update_consult'])) {
    $user = Input::get('usager');
    $medecin = Input::get('medecin');
    $date = date('Y-m-d',strtotime(Input::get('date')));
    $heure = Input::get('heure');
    $duree = Input::get('duree');
    if ($user == null || $medecin == null || $date == null || $heure == null || $duree == null || $duree == "0") {
      $error = "Veuillez vérifier les champs.";
    } else {
      $r = $consult->checkExistingRDV($date,$heure,$duree,$medecin,$user);
      if($r) {
        $result = $consult->update(array("date_r" => $date, "heure_r" => $heure, "duree" => $duree, "id_u" => $user, "id_m" => $medecin));
        if(!$result) $error = "Une erreur s'est produite lors de la mise à jour de la consultation.";
      }
      else {
        $error = "L'horaire de la consultation ne peut pas en chevaucher un autre.";
      }
    }
  }
}

try {
  $consult = new RDV($id);
  $medecin = $consult->getMedecin();
  $usager = $consult->getUsager();
} catch (Exception $e) {
  Redirect::to('/consultations/view');
}
?>
<main role="main" class="container">
  <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    <h1 class="display-5">Editer une consultation</h1>
  </div>
  <div class="container">
    <form action="" method="post">
      <?php if ($error != null) { ?><div class="alert alert-danger"><?= $error ?></div><?php } ?>
      <div class="form-group">
        <label for="inputMed">Usager</label>
        <?php $usagers = $db->query("SELECT * FROM usager;")->results(); ?>
        <select class="form-control" name="usager" id="inputMed" required>
          <?php foreach ($usagers as $u) { ?>
            <option <?php if($usager->getID() == $u->id) { echo 'selected="selected"'; } ?> value="<?= $u->id ?>"><?= $u->nom . " " . $u->prenom ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="form-group">
        <label for="inputMed">Médecin</label>
        <?php $medq = $db->query("SELECT * FROM medecin;")->results(); ?>
        <select class="form-control" name="medecin" id="inputMed" required>
          <?php foreach ($medq as $med) { ?>
          <option <?php if($medecin->getID() == $med->id) { echo 'selected="selected"'; } ?> value="<?= $med->id ?>"><?= $med->nom . " " . $med->prenom ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="inputTime">Date</label>
          <input type="text" name="date" id="datepicker" class="form-control" value="<?=date("m/d/Y",strtotime($consult->getInfo()->date_r))?>" required>
        </div>
        <div class="form-group col-md-6">
          <label for="inputDate">Heure</label>
          <input type="text" name="heure" id="timepicker" class="form-control" value="<?=$consult->getInfo()->heure_r?>" required>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="inputNum">Durée (en minutes)</label>
          <div class="input-group number-spinner">
            <span class="input-group-btn">
              <button type="button" class="btn btn-default" data-dir="dwn"><span class="glyphicon glyphicon-minus"></span></button>
            </span>
            <input type="text" name="duree" class="form-control text-center" value="<?=$consult->getInfo()->duree?>">
            <span class="input-group-btn">
              <button type="button" class="btn btn-default" data-dir="up"><span class="glyphicon glyphicon-plus"></span></button>
            </span>
          </div>
        </div>
      </div>
      <input type="hidden" name="csrf" value="<?= Token::generate(); ?>">
      <button type="submit" name="update_consult" class="btn btn-success">Valider les changements</button>
      <a href="<?=Config::get('root')?>/consultations/delete/<?=$consult->getID()?>" class="btn btn-danger">Supprimer</a>
      <a href="<?=Config::get('root')?>/consultations/view" class="btn btn-primary">Retourner à la liste</a>
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