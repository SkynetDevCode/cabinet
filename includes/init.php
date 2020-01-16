<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('default_charset','utf-8');
session_start();

setlocale(LC_ALL, 'fr_FR');
date_default_timezone_set('Europe/Paris');

$GLOBALS['config'] = array(
	'mysql'      => array(
	'host'         => 'localhost',
	'username'     => 'lanusr',
	'password'     => 'sdvcddebian42lanusr',
	'db'           => 'iut',
),
'remember'        => array(
  'cookie_name'   => 'mqesoxiw3v19374rsb',
  'cookie_expiry' => 604800
),
'session' => array(
  'session_name' => 'user',
  'token_name' => 'token',
),
'user' => array(
	'username' => 'iut',
	'password' => 'iutinfotlse',
),
'root' => '/cabinet'
);

require_once 'classes/class.autoloader.php';

$db = DB::getInstance();
$user = new User();