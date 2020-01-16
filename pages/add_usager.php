<?php
$page_title = "Ajouter un usager";
require_once 'includes/init.php';
require_once 'includes/templates/header.php';
require_once 'includes/templates/nav.php';

if (!$user->isLoggedIn()) Redirect::to('/login');

$error = null;

if (Input::exists()) {
  $token = Input::get('csrf');
  if (!Token::check($token)) require_once 'token_error.php';
  if (isset($_POST['add_usager'])) {
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

    if ($civilite == null || $nom == null || $prenom == null || $adresse == null || $ville == null || $cp == null || $date_naissance == null || $ville_naissance == null || $numero_sc == null || $medecin_ref == null) {
      $error = "Veuillez vérifier les champs";
    } else {

      if (!is_numeric($numero_sc) || strlen($numero_sc) != 15) {
        $error = "Numéro de sécurité sociale invalide.";
      } else {
        if (!is_numeric($cp) || strlen($cp) != 5) {
          $error = "Code postal invalide.";
        } else { 
          $response = Usager::add(array("civilite" => $civilite, "nom" => $nom, "prenom" => $prenom, "adresse" => $adresse, "ville" => $ville, "cp" => $cp, "date_naissance" => $date_naissance, "lieu_naissance" => $ville_naissance, "numero_sc" => $numero_sc, "id_m" => $medecin_ref));
          if(!$response) $error = "Une erreur s'est produite lors de l'ajout de l'usager.";
        }
      }
    }
  }
}

?>
<main role="main" class="container">
  <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    <h1 class="display-5">Ajouter un usager</h1>
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
      <div class="form-group">
        <label for="inputAdresse">Adresse</label>
        <input type="text" name="adresse" class="form-control" id="inputAddress" placeholder="5 Rue du Cabinet" required>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="inputVille">Ville</label>
          <input type="text" name="ville" class="form-control" id="inputVille" required>
        </div>
        <div class="form-group col-md-6">
          <label for="inputCP">Code Postal</label>
          <input type="text" name="cp" maxlength="5" class="form-control" id="inputCP" required>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="inputDateNaissance">Date de naissance</label>
          <input type="text" name="date_naissance" id="datepicker" class="form-control" required>
        </div>
        <div class="form-group col-md-6">
          <label for="inputVilleNaissance">Ville de naissance</label>
          <input type="text" name="ville_naissance" class="form-control" id="inputVilleNaissance" required>
        </div>
      </div>
      <div class="form-group">
        <label for="inputNSC">Numéro Sécurité Sociale</label>
        <input type="text" name="numero_sc" maxlength="15" class="form-control" id="inputNSC" required>
      </div>
      <div class="form-group">
        <label for="inputMed">Médecin référent</label>
        <?php $medecin = $db->query("SELECT * FROM medecin;")->results(); ?>
        <select class="form-control" name="medecin_ref" id="inputMed" required>
          <?php foreach ($medecin as $med) { ?>
            <option value="<?= $med->id ?>"><?= $med->nom . " " . $med->prenom ?></option>
          <?php } ?>
        </select>
      </div>
      <input type="hidden" name="csrf" value="<?= Token::generate(); ?>">
      <button type="submit" name="add_usager" class="btn btn-primary">Ajouter un usager</button>
    </form>
  </div>
</main>
<script type="text/javascript">
  $(function () {
      $('#datepicker').datepicker();
  });
</script>
<?php require_once 'includes/templates/footer.php'; ?>