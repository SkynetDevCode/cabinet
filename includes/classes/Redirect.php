<?php
class Redirect {
  public static function to($location = null, $args=''){
    global $us_url_root;
    if ($location) {
      if ($args) $location .= $args; // allows 'login.php?err=Error+Message' or the like
      if (!headers_sent()){
        header('Location: '.Config::get('root').$location);
        exit();
      } else {
        echo '<script type="text/javascript">';
        echo 'window.location.href="'.Config::get('root').$location.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.Config::get('root').$location.'" />';
        echo '</noscript>'; exit;
      }
    }
  }
}
