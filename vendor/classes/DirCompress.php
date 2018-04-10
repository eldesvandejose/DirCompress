<?php
    /**
     * This class is used for iterating a directory an store the whole hierarchy of subdirectories and all the files
     * in a zip compressed file.
     */
    class DirCompress {
        /**
         * All the properties of this class are private, since they shouldn't be accessed from outer environment.
         */

        /**
         * $SourceDir is the directory to be compressed. The whole content in this directory will be included in the compressed zip file
         * while keeping the full subdirectories hierarchy. 
         */
        private $SourceDir = "";
        /**
         * $DestDir is the name of the directory where the zip file will be stored.
         */
        private $DestDir = "";
        /**
         * $zipFile will be the zip compressed file with the whole hierarchy of subdirectories and files in the specified source directory. 
         */
        private $zipFile;
        /**
         * The name of the zip file to be stored with in the dstination directory.
         */
        private $zipFileName = "";

        /**
         * The constructor method of the class gets the names for the source and destination directories. 
         * They must be the relative paths from the script which uses this class. 
         * It checks the correct format of the name for both directories. If not correct (an empty name, i.e.) 
         * it doesn´t acomplish the process.
         * 
         * If the destination directory doesn't exist it will be created here, with 0777 permissions.
         * If it exists, the right permissions will be assigned to it.
         * 
         * The names of the source and destination directory are assigned to the matching properties of the object.
         * The zipFile property is created as an object of the ZipArchive class, which is included in PHP from 
         * the 5.2.0 version. In Linux Systems, PHP must be compiled with the --enable-zip option. 
         * In Windows systems, the php_zip.dll must be activated in the php.ini file.
         */
        public function __construct($SourceDir = "", $DestDir = ""){
            $SourceDir = trim($SourceDir);
            $DestDir = trim($DestDir);
            if (substr($SourceDir, strlen($SourceDir) - 1) != '/') $SourceDir .= '/';
            if (substr($DestDir, strlen($DestDir) - 1) != '/') $DestDir .= '/';
            if ($SourceDir > "" && $DestDir > "" && is_dir($SourceDir)) {
                if (!is_dir($DestDir)){
                    mkdir($DestDir, 0777);
                } else {
                    chmod($DestDir, 0777);
                }

                $this->SourceDir = $SourceDir;
                $this->DestDir = $DestDir;
                $this->zipFile = new ZipArchive();
            }
        }

        /**
         * The createZip() method is the one which creates the zip file (if possible), 
         * calls the sourceIterate() method for including the source files, and saves 
         * the zipFile in the destination directory, with the name indicated with the 
         * setZipFileName() method (if valid). See setters and getters.
         */
        public function createZip()
        {
            if ($this->SourceDir == "" || $this->DestDir == "" || $this->getZipFileName() == "") return;
            if ($this->zipFile->open($this->zipFileName, ZIPARCHIVE::CREATE) === true) {
                $this->sourceIterate($this->SourceDir);
                $this->zipFile->close();
                rename($this->zipFileName, $this->DestDir.$this->zipFileName);
            }
        }

        /**
         * This returns a download link for getting the zip file.
         */
        public function makeLink($text)
        {
            $link = '<a href="'.$this->DestDir.$this->zipFileName.'">'.$text.'</a>';
            return $link;
        }

        /**
         * The sourceIterate() method is a recursive one, which iterates the whole source directory structure 
         * and includes the files in the compressed zip, with the full names including the right path in the 
         * source directory.
         * 
         * It is private since it musn't be called from the outer environment.
         */
        private function sourceIterate($directory){
            $dirManager = opendir($directory);
            while ($itemName = readdir($dirManager))
            {
                if ($itemName == "." || $itemName == "..") continue;
                $fullItemName = $directory.$itemName;
                if (is_dir($fullItemName))
                {
                    $fullItemName .= '/';
                    $this->sourceIterate($fullItemName, $this->zipFile);
                } elseif (is_file($fullItemName)) {
                    $this->zipFile->addFile($fullItemName, $this->cleanSourceDirName($fullItemName));
                }
            }
            closedir($dirManager);
        }

        /**
         * The cleanSourceDirName() avoids the . or .. or / directories to be stored in the compressed file, 
         * so the sip only contains the source directory and the whole directories and files structure inside it. 
         */
        private function cleanSourceDirName($fullItemName){
            while (substr($fullItemName, 0, 1) == "." || substr($fullItemName, 0, 1) == "/" || substr($fullItemName, 0, 1) == "\\") {
                $fullItemName = substr($fullItemName, 1);
            }
            return $fullItemName;
        }

        /**
         * The setters and getters for the class properties. Not of them are used, but 
         * available for future improvments.
         */
        public function setSourceDir($directory)
        {
            $this->SourceDir = $directory;
        }

        public function getSourceDir()
        {
            return $this->SourceDir;
        }

        public function setDestDir($directory)
        {
            $this->DestDir = $directory;
        }

        public function getDestDir()
        {
            return $this->DestDir;
        }

        public function setZipFileName($zipFileName)
        {
            if (strlen($zipFileName) < 5) return;
            if (substr($zipFileName, strlen($zipFileName) - 4) != '.zip') $zipFileName .= '.zip';
            $this->zipFileName = $zipFileName;
        }

        public function getZipFileName()
        {
            return $this->zipFileName;
        }
    }
?>