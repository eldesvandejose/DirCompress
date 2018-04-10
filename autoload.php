<?php
	/* Definimos dos constantes para referirnos al directorio raíz 
	y al carácter de separación de directorios de PHP. De este modo, 
	no es relevante cual sea la raíz de nuestro proyecto y, por otra 
	parte, como PHP puede variar su carácter de separación de 
	directorios, dependiendo del sistema operativo, y la versión 
	del lenguaje, evitamos confilctos. */
	define ('ROOT', dirname(__FILE__));
	define ('DS', DIRECTORY_SEPARATOR);
 
	/* La función spl_autoload_register() nos permite registrar una 
	función que se ocupará de la autocarga, y que podemos llamar 
	como deseemos. En este ejemplo, la lhe llamado autoload, porque 
	es un nombre relevante, indicador de lo que hace la función, 
	pero la podría haber dado cualquier otro nombre. */
	spl_autoload_register('autoload');
 
	/* A continuación creamos la función de autocarga con el nombre que 
	le hemos dado en spl_autoload_register(). Esta función recibe un 
	argumento que será el nombre de la clase a cargar (realemente, 
	el nombre del script que contenga dicha clase, sin la extensión php, 
	que se le deberá añadir "dentro" de la función.). La operativa de 
	esta función puede ser un poco arcana al principio, ya que es una 
	función a la que nosotros no invocaremos nunca. Será PHP, a través 
	del funcionamiento de spl_autoload_register(), quien se encargará 
	de invocarla, de forma transparente a nosotros. */
	function autoload($script)
	{
		if ($script == 'ZipArchive') return;
		/* Modificamos el nombre del script para añadirle, como prefijo, 
		el nombre del directorio donde están todos los scripts a importar 
		(vendor/classes, en este ejemplo) y el separador de directorios. Después le 
		añadimos el nombre del directorio raíz al principio, y la extensión 
		.php al final. */
		$script = ROOT.DS."vendor".DS."classes".DS.str_replace("\\", DS, $script).'.php';
		/* Hacemos que la función cargue el script con la clase o 
		contenidos que hemos creado. */
		include_once ($script);
	}
?>