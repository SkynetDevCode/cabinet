<?php
class User {
	private $_db, $_sessionName, $_isLoggedIn, $_cookieName, $_username, $_password, $_loggedin_user;

	public function __construct(){
		$this->_db = DB::getInstance();
		$this->_username = Config::get('user/username');
		$this->_password = Config::get('user/password');
		$this->_sessionName = Config::get('session/session_name');
		$this->_cookieName = Config::get('remember/cookie_name');

		if (Session::exists($this->_sessionName) || Cookie::exists($this->_cookieName)) {
			$this->_loggedin_user = Session::get($this->_sessionName);
			$this->_isLoggedIn = true;
		}
		else if(Cookie::exists($this->_cookieName)) {
			$hash = Hash::make($this->_username,Hash::salt(5));
			if(Cookie::get($this->_cookieName) == $hash) {	
				if(!Session::exists($this->_sessionName)) Session::put($this->_sessionName, $this->_username);
				$this->_loggedin_user = Session::get($this->_sessionName);
				$this->_isLoggedIn = true;
			}
		}
	}
	
	public function login($username = null, $password = null, $remember = false){

		if ($username == $this->_username && $password == $this->_password) {
			Session::put($this->_sessionName, $username);
			if ($remember) {
				$hash = Hash::make($username,Hash::salt(5));
				Cookie::put($this->_cookieName, $this->_hash, Config::get('remember/cookie_expiry'));
			}
			$this->_isLoggedIn = true;
			return true;
		}
		else return false;
	}

	public function userName(){
		return $this->_loggedin_user;
	}

	public function isLoggedIn(){
		return $this->_isLoggedIn;
	}

	public function logout(){
		Session::delete($this->_sessionName);
		Cookie::delete($this->_cookieName);
		session_unset();
		session_destroy();
	}
}
