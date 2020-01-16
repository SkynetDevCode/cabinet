<?php
$page_title = "Connexion";
require_once 'includes/init.php';
require_once 'includes/templates/header.php';

if($user->isLoggedIn()) Redirect::to('/home');

$error = null;

if(Input::exists()) {
    $token = Input::get('csrf');
    if(!Token::check($token)) require_once 'token_error.php';
    if(isset($_POST['login'])) {
        $username = Input::get('username');
        $password = Input::get('password');
        $rememberme = isset($_POST['remember-me']);
        if($user->login($username,$password,$rememberme)) {
            Redirect::to('/home');
        }
        else $error = "Identifiant ou mot de passe incorrect";
    }
}

?>
<link rel="stylesheet" href="assets/css/signin.css">
<form class="form-signin" method="post">
    <img class="mb-4" src="assets/img/cabinet.png" alt="" width="128" height="128">
    <h1 class="h3 mb-3 font-weight-normal">Cabinet Médical</h1>
    <h2 class="h4 mb-3 font-weight-normal">Connexion</h2>
    <?php if($error != null) { ?><div class="alert alert-danger"><?=$error?></div><?php } ?>
    <label for="inputID" class="sr-only">Identifiant</label>
    <input type="text" id="inputID" name="username" class="form-control" placeholder="Identifiant" required autofocus>
    <label for="inputPassword" class="sr-only">Mot de passe</label>
    <input type="password" id="inputPassword"  name="password" class="form-control" placeholder="Mot de passe" required>
    <div class="checkbox mb-3">
        <label>
            <input type="checkbox" name ="remember-me" value="remember-me">Rester connecté
        </label>
    </div>
    <button class="btn btn-lg btn-primary btn-block" name="login" type="submit">Se connecter</button>
    <p class="mt-5 mb-3 text-muted">&copy; Thibault SAINTURET & Lucas OESLICK - Projet Cabinet Médical (IUT Informatique de Rangueil).</p>
    <input type="hidden" name="csrf" value="<?=Token::generate(); ?>">
</form>
</body>
</html>