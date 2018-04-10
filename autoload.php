<?php
	define ('ROOT', dirname(__FILE__));
	define ('DS', DIRECTORY_SEPARATOR);
 
	spl_autoload_register('autoload');
 
	function autoload($script)
	{
		if ($script == 'ZipArchive') return;
		$script = ROOT.DS."vendor".DS."classes".DS.str_replace("\\", DS, $script).'.php';
		include_once ($script);
	}
?>
