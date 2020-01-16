<?php
$page_title = "Ajouter un médecin";
require_once 'includes/init.php';
require_once 'includes/templates/header.php';
require_once 'includes/templates/nav.php';

if (!$user->isLoggedIn()) Redirect::to('/login');

$error = null;

if (Input::exists()) {
  $token = Input::get('csrf');
  if (!Token::check($token)) require_once 'token_error.php';
  if (isset($_POST['add_medecin'])) {
    $civilite = Input::get('civilite');
    $nom = Input::get('nom');
    $prenom = Input::get('prenom');

    if ($civilite == null || $nom == null || $prenom == null) {
      $error = "Veuillez vérifier les champs";
    } else {
      $response = Medecin::add(array("civilite" => $civilite, "nom" => $nom, "prenom" => $prenom));
      if(!$response) $error = "Une erreur s'est produite lors de l'ajout du médecin.";
    }
  }
}

?>
<main role="main" class="container">
  <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    <h1 class="display-5">Ajouter un médecin</h1>
  </div>

  <div class="container">
    <form action="" method="post">
      <?php if ($error != null) { ?><div class="alert alert-danger"><?= $error ?></div><?php } ?>
      <div class="form-group">
        <label for="inputCivilite">Civilite</label>
        <select class="form-control" name="civilite" id="inputCivilite" required>
          <option name="M">Homme</option>
          <option name="F">Femme</option>
        </select>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="inputNom">Nom</label>
          <input type="text" name="nom" class="form-control" id="inputNom" placeholder="Nom" required>
        </div>
        <div class="form-group col-md-6">
          <label for="inputPrenom">Prénom</label>
          <input type="text" name="prenom" class="form-control" id="inputPrenom" placeholder="Prénom" required>
        </div>
      </div>
      <input type="hidden" name="csrf" value="<?= Token::generate(); ?>">
      <button type="submit" name="add_medecin" class="btn btn-primary">Ajouter un médecin</button>
    </form>
  </div>
</main>
<?php require_once 'includes/templates/footer.php'; ?>