<?php

/*
    Copyright (C) 2011,2012  Serge V. Baumer

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, version 3 of the License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

defined ('APPLICATION_PATH') || define ('APPLICATION_PATH',
	realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR
		.  '..' . DIRECTORY_SEPARATOR . 'application'));
set_include_path (implode (PATH_SEPARATOR,
	array(APPLICATION_PATH . DIRECTORY_SEPARATOR . '..'
			 . DIRECTORY_SEPARATOR . 'library', get_include_path())));
require_once implode(DIRECTORY_SEPARATOR, array(
	'Zend', 'Loader', 'Autoloader.php'));
Zend_Loader_Autoloader::getInstance();

defined ('APPLICATION_ENV') || define ('APPLICATION_ENV', 'development');

$application = new Zend_Application (
	APPLICATION_ENV,
	implode(DIRECTORY_SEPARATOR, array(
		APPLICATION_PATH, 'configs', 'application.ini'))
);

$bootstrap = $application->getBootstrap();
$bootstrap->bootstrap('db');
$dbAdapter = $bootstrap->getResource('db');
$db = $dbAdapter->getConnection();

foreach (array('schema', 'data') as $dirname) {
	$dir = realpath(implode (DIRECTORY_SEPARATOR,
			array(APPLICATION_PATH, '..', $dirname)));

	if (file_exists($dir) && is_dir($dir)) {
		foreach (glob($dirname . DIRECTORY_SEPARATOR . '*.sql') as $file) {
			try {
				$db->exec(file_get_contents ($file));
			} catch (Exception $e) {
				echo "Error in $file: " . PHP_EOL . $e->getMessage() . PHP_EOL;
				return false;
			}
		}

		echo ucfirst($dirname) . ' loaded.' . PHP_EOL;
	}
}

return true;
?>

