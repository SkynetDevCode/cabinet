<?php
$page_title = "Editer un médecin";
require_once 'includes/init.php';
require_once 'includes/templates/header.php';
require_once 'includes/templates/nav.php';

if (!$user->isLoggedIn()) Redirect::to('/login');

global $match;
$id = $match['params']['id'];

if ($id == null) Redirect::to('/medecins/view');
try {
  $medecin = new Medecin($id);
} catch (Exception $e) {
  Redirect::to('/medecins/view');
}

$error = null;

if (Input::exists()) {
  $token = Input::get('csrf');
  if (!Token::check($token)) require_once 'token_error.php';
  if (isset($_POST['update_medecin'])) {
    $civilite = Input::get('civilite');
    $nom = Input::get('nom');
    $prenom = Input::get('prenom');

    if ($civilite == null || $nom == null || $prenom == null) {
      $error = "Veuillez vérifier les champs";
    } else {
      $response = $medecin->update(array("civilite" => $civilite, "nom" => $nom, "prenom" => $prenom));
      if(!$response) $error = "Une erreur s'est produite lors de la mise à jour des données du médecin.";
    }
  }
}

try {
  $medecin = new Medecin($id);
} catch (Exception $e) {
  Redirect::to('/medecins/view');
}
?>
<main role="main" class="container">
  <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    <h1 class="display-5">Editer un médecin</h1>
  </div>
  <div class="container">
    <form action="" method="post">
      <?php if ($error != null) { ?><div class="alert alert-danger"><?= $error ?></div><?php } ?>
      <div class="form-group">
        <label for="inputCivilite">Civilite</label>
        <select class="form-control" name="civilite" id="inputCivilite" required>
          <option <?php if($medecin->getInfo()->civilite == 'F') echo 'selected="selected"' ?> name="F">Femme</option>
          <option <?php if($medecin->getInfo()->civilite == 'M') echo 'selected="selected"' ?> name="M">Homme</option>
        </select>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="inputNom">Nom</label>
          <input type="text" name="nom" class="form-control" id="inputNom" placeholder="<?=$medecin->getInfo()->nom?>" value="<?=$medecin->getInfo()->nom?>" required>
        </div>
        <div class="form-group col-md-6">
          <label for="inputPrenom">Prénom</label>
          <input type="text" name="prenom" class="form-control" id="inputPrenom" placeholder="<?=$medecin->getInfo()->prenom?>" value="<?=$medecin->getInfo()->prenom?>" required>
        </div>
      </div>
      <input type="hidden" name="csrf" value="<?= Token::generate(); ?>">
      <button type="submit" name="update_medecin" class="btn btn-success">Valider les changements</button>
      <a href="<?=Config::get('root')?>/medecins/delete/<?=$medecin->getID()?>" class="btn btn-danger">Supprimer</a>
      <a href="<?=Config::get('root')?>/medecins/view" class="btn btn-primary">Retourner à la liste</a>
    </form>
  </div>
</main>
<?php require_once 'includes/templates/footer.php'; ?>