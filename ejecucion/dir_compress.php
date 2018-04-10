<?php
    /**
     * The name of the source and destination folders must be specified relative to 
     * the script wich includes this one.
     */

    /**
     * We create a DirCompress class object with the right source and destination directories.
     */
    $objeto = new DirCompress("origen/", "destino/");
    /**
     * We establish the name of the compressed zip file, which is mandatory. 
     * If we don't include a .zip name extension, this will be added by the class, 
     * so this is optional. It will be .zip named anyway.
     */
    $objeto->setZipFileName("comprimido.zip");
    /**
     * We create the zip compressed file.
     */
    $objeto->createZip();

    echo $objeto->makeLink("Descargar");
?>