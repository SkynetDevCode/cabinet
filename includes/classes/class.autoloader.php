<?php
class Autoloader {
	protected static $fileExt = '.php';
	protected static $pathTop = __DIR__;
	protected static $fileIterator = null;

	public static function loader($className) {

		//$directory = new RecursiveDirectoryIterator(static::$pathTop, RecursiveDirectoryIterator::SKIP_DOTS);

		if (is_null(static::$fileIterator)) {

			//static::$fileIterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::LEAVES_ONLY);
			static::$fileIterator =  new RecursiveIteratorIterator(
					 new RecursiveDirectoryIterator(static::$pathTop, RecursiveDirectoryIterator::SKIP_DOTS),
					   RecursiveIteratorIterator::SELF_FIRST,
					   RecursiveIteratorIterator::CATCH_GET_CHILD // Ignore "Permission denied"
				         );
		}

		$filename = $className . static::$fileExt;

		foreach (static::$fileIterator as $file) {

			if (strtolower($file->getFilename()) === strtolower($filename)) {

				if ($file->isReadable()) {

					include_once $file->getPathname();

				}
				break;

			}

		}

	}


	public static function setFileExt($fileExt) {
		static::$fileExt = $fileExt;
	}

	public static function setPath($path) {
		static::$pathTop = $path;
	}

}

Autoloader::setFileExt('.php');
spl_autoload_register('Autoloader::loader', true, true);
