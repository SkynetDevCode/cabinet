<link rel="stylesheet" href="<?=Config::get('root')?>/assets/css/navbar_corrections.css">
<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
  <a class="navbar-brand mr-auto mr-lg-0" href="#">Cabinet Médical</a>
  <button class="navbar-toggler p-0 border-0" type="button" data-toggle="offcanvas">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
    <ul class="navbar-nav ml-auto">
    <?php if($user->isLoggedIn()) { ?>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="<?=Config::get('root')?>/usagers/view" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Usagers</a>
        <div class="dropdown-menu" aria-labelledby="dropdown01">
          <a class="dropdown-item" href="<?=Config::get('root')?>/usagers/view">Visualiser</a>
          <a class="dropdown-item" href="<?=Config::get('root')?>/usagers/add">Ajouter</a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="<?=Config::get('root')?>/users/view" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Médecins</a>
        <div class="dropdown-menu" aria-labelledby="dropdown01">
          <a class="dropdown-item" href="<?=Config::get('root')?>/medecins/view">Visualiser</a>
          <a class="dropdown-item" href="<?=Config::get('root')?>/medecins/add">Ajouter</a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="<?=Config::get('root')?>/consultations/view" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Consulations</a>
        <div class="dropdown-menu" aria-labelledby="dropdown01">
          <a class="dropdown-item" href="<?=Config::get('root')?>/consultations/view">Visualiser</a>
          <a class="dropdown-item" href="<?=Config::get('root')?>/consultations/add">Ajouter</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?=Config::get('root')?>/stats">Satistiques</a>
      </li>
      <?php } ?>
      <?php if($user->isLoggedIn()) { ?>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?=ucfirst($user->userName())?></a>
        <div class="dropdown-menu" aria-labelledby="dropdown01">
          <a class="dropdown-item" href="<?=Config::get('root')?>/logout">Déconnexion</a>
        </div>
      </li>
      <?php } else { ?>
      <li class="nav-item dropdown">
        <a class="nav-link" href="<?=Config::get('root')?>/login">Connexion</a>
      </li>
      <?php } ?>
    </ul>
  </div>
</nav>