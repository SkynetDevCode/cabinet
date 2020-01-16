<?php
$page_title = "Editer un usager";
require_once 'includes/init.php';
require_once 'includes/templates/header.php';
require_once 'includes/templates/nav.php';

if (!$user->isLoggedIn()) Redirect::to('/login');

global $match;
$id = $match['params']['id'];

if ($id == null) Redirect::to('/users/view');
try {
  $usager = new Usager($id);
} catch (Exception $e) {
  Redirect::to('/users/view');
}

$error = null;

if (Input::exists()) {
  $token = Input::get('csrf');
  if (!Token::check($token)) require_once 'token_error.php';
  if (isset($_POST['update_client'])) {
    $civilite = Input::get('civilite');
    $nom = Input::get('nom');
    $prenom = Input::get('prenom');
    $adresse = Input::get('adresse');
    $ville = Input::get('ville');
    $cp = Input::get('cp');
    $date_naissance = date('Y-m-d',strtotime(Input::get('date_naissance')));
    $ville_naissance = Input::get('ville_naissance');
    $numero_sc = Input::get('numero_sc');
    $medecin_ref = Input::get('medecin_ref');

    if ($nom == null || $prenom == null || $adresse == null || $ville == null || $cp == null || $date_naissance == null || $ville_naissance == null || $numero_sc == null || $medecin_ref == null) {
      $error = "Veuillez vérifier les champs";
    } else {

      if (!is_numeric($numero_sc) || strlen($numero_sc) != 15) {
        $error = "Numéro de sécurité sociale invalide.";
      } else {
        if (!is_numeric($cp) || strlen($cp) != 5) {
          $error = "Code postal invalide.";
        } else { 
          $response = $usager->update(array("civilite" => $civilite, "nom" => $nom, "prenom" => $prenom, "adresse" => $adresse, "ville" => $ville, "cp" => $cp, "date_naissance" => $date_naissance, "lieu_naissance" => $ville_naissance, "numero_sc" => $numero_sc, "id_m" => $medecin_ref));
          if(!$response) $error = "Une erreur s'est produite lors de la mise à jour des données de l'usager.";
        }
      }
    }
  }
}

try {
  $usager = new Usager($id);
} catch (Exception $e) {
  Redirect::to('/users/view');
}

?>
<main role="main" class="container">
  <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    <h1 class="display-5">Editer un usager</h1>
  </div>
  <div class="container">
    <form action="" method="post">
      <?php if ($error != null) { ?><div class="alert alert-danger"><?= $error ?></div><?php } ?>
      <div class="form-group">
        <label for="inputCivilite">Civilite</label>
        <select class="form-control" name="civilite" id="inputCivilite" required>
          <option <?php if($usager->getInfo()->civilite == 'F') echo 'selected="selected"' ?> name="F">Femme</option>
          <option <?php if($usager->getInfo()->civilite == 'M') echo 'selected="selected"' ?> name="M">Homme</option>
        </select>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="inputNom">Nom</label>
          <input type="text" name="nom" class="form-control" id="inputNom" placeholder="<?=$usager->getInfo()->nom?>" value="<?=$usager->getInfo()->nom?>" required>
        </div>
        <div class="form-group col-md-6">
          <label for="inputPrenom">Prénom</label>
          <input type="text" name="prenom" class="form-control" id="inputPrenom" placeholder="<?=$usager->getInfo()->prenom?>" value="<?=$usager->getInfo()->prenom?>" required>
        </div>
      </div>
      <div class="form-group">
        <label for="inputAdresse">Adresse</label>
        <input type="text" name="adresse" class="form-control" id="inputAddress" placeholder="<?=$usager->getInfo()->adresse?>" value="<?=$usager->getInfo()->adresse?>" required>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="inputVille">Ville</label>
          <input type="text" name="ville" class="form-control" id="inputVille" placeholder="<?=$usager->getInfo()->ville?>" value="<?=$usager->getInfo()->ville?>" required>
        </div>
        <div class="form-group col-md-6">
          <label for="inputCP">Code Postal</label>
          <input type="text" name="cp" maxlength="5" class="form-control" id="inputCP" placeholder="<?=$usager->getInfo()->cp?>" value="<?=$usager->getInfo()->cp?>" required>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="inputDateNaissance">Date de naissance</label>
          <input type="text" name="date_naissance" id="datepicker" class="form-control" value="<?=date("m/d/Y",strtotime($usager->getInfo()->date_naissance))?>" required>
        </div>
        <div class="form-group col-md-6">
          <label for="inputVilleNaissance">Ville de naissance</label>
          <input type="text" name="ville_naissance" class="form-control" id="inputVilleNaissance" placeholder="<?=$usager->getInfo()->lieu_naissance?>" value="<?=$usager->getInfo()->lieu_naissance?>" required>
        </div>
      </div>
      <div class="form-group">
        <label for="inputNSC">Numéro Sécurité Sociale</label>
        <input type="text" name="numero_sc" maxlength="15" class="form-control" id="inputNSC" placeholder="<?=$usager->getInfo()->numero_sc?>" value="<?=$usager->getInfo()->numero_sc?>" required>
      </div>
      <div class="form-group">
        <label for="inputMed">Médecin référent</label>
        <?php $medecin = $db->query("SELECT * FROM medecin;")->results(); ?>
        <select class="form-control" name="medecin_ref" id="inputMed" required>
          <?php foreach ($medecin as $med) { ?>
            <option <?php if($med->id == $usager->getInfo()->id_m) echo 'selected="selected"';?> value="<?= $med->id ?>"><?= $med->nom . " " . $med->prenom ?></option>
          <?php } ?>
        </select>
      </div>
      <input type="hidden" name="csrf" value="<?= Token::generate(); ?>">
      <button type="submit" name="update_client" class="btn btn-success">Valider les changements</button>
      <a href="<?=Config::get('root')?>/usagers/delete/<?=$usager->getID()?>" class="btn btn-danger">Supprimer</a>
      <a href="<?=Config::get('root')?>/usagers/view" class="btn btn-primary">Retourner à la liste</a>
    </form>
  </div>
</main>
<script type="text/javascript">
  $(function () {
      $('#datepicker').datepicker();
  });
</script>
<?php require_once 'includes/templates/footer.php'; ?>