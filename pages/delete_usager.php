<?php
$page_title = "Supprimer un usager";
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
    if (isset($_POST['del_client'])) {
        if($usager->delete()) {

        }
        else $error = "Echec de la suppression";
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
        <h1 class="display-5">Supprimer un usager</h1>
    </div>
    <div class="container">
        <form action="" method="post">
            <?php if ($error != null) { ?><div class="alert alert-danger"><?= $error ?></div><?php } ?>
            <div class="card">
                <h5 class="card-header">Suppression d'un usager</h5>
                <div class="card-body">
                    <h5 class="card-title">Êtes-vous sûr de vouloir supprimer l'usager <?=$usager->getInfo()->nom.' '.$usager->getInfo()->prenom?> ?</h5>
                    <p class="card-text"><i>Cette opération est irréversible.</i></p>
                    <input type="hidden" name="csrf" value="<?= Token::generate(); ?>">
                    <button type="submit" name="del_client" class="btn btn-danger">Supprimer l'usager</button>
                    <a href="<?= Config::get('root') ?>/usagers/edit/<?= $usager->getID() ?>" class="btn btn-info">Annuler</a>
                </div>
            </div>
        </form>
    </div>
</main>
<?php require_once 'includes/templates/footer.php'; ?>