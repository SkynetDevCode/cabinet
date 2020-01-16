<?php
$page_title = "Supprimer une consultation";
require_once 'includes/init.php';
require_once 'includes/templates/header.php';
require_once 'includes/templates/nav.php';

if (!$user->isLoggedIn()) Redirect::to('/login');

global $match;
$id = $match['params']['id'];

if ($id == null) Redirect::to('/consultations/view');
try {
    $consult = new RDV($id);
    $usager = $consult->getUsager();
} catch (Exception $e) {
    Redirect::to('/consultations/view');
}

$error = null;

if (Input::exists()) {
    $token = Input::get('csrf');
    if (!Token::check($token)) require_once 'token_error.php';
    if (isset($_POST['del_consult'])) {
        if($consult->delete()) {

        }
        else $error = "Echec de la suppression";
    }
}

$formated_text = ($usager->getInfo()->civilite == 'F' ? "Mme" : "Mr")." ".$usager->getInfo()->prenom." ".$usager->getInfo()->nom." prévue le ".utf8_encode(strftime('%d %B %Y', strtotime($consult->getInfo()->date_r)))." à ".date('H:i',strtotime($consult->getInfo()->heure_r));
?>
<main role="main" class="container">
    <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
        <h1 class="display-5">Supprimer une consultation</h1>
    </div>
    <div class="container">
        <form action="" method="post">
            <?php if ($error != null) { ?><div class="alert alert-danger"><?= $error ?></div><?php } ?>
            <div class="card">
                <h5 class="card-header">Suppression d'une consultation</h5>
                <div class="card-body">
                    <h5 class="card-title">Êtes-vous sûr de vouloir supprimer la consultation de <?=$formated_text?> ?</h5>
                    <p class="card-text"><i>Cette opération est irréversible.</i></p>
                    <input type="hidden" name="csrf" value="<?= Token::generate(); ?>">
                    <button type="submit" name="del_consult" class="btn btn-danger">Supprimer la consultation</button>
                    <a href="<?= Config::get('root') ?>/consultations/edit/<?= $consult->getID() ?>" class="btn btn-info">Annuler</a>
                </div>
            </div>
        </form>
    </div>
</main>
<?php require_once 'includes/templates/footer.php'; ?>