<?php
define('VERBOSE_MODE', false);

$srcRoot = dirname(__DIR__);
$buildRoot = __DIR__;
$filename = 'nlp.phar';

if (file_exists($buildRoot . DIRECTORY_SEPARATOR . $filename)) {
    unlink($buildRoot . DIRECTORY_SEPARATOR . $filename);
}

$pharPath = $buildRoot . DIRECTORY_SEPARATOR. $filename;
$phar = new \Phar($pharPath, 0, $filename);
$phar->startBuffering();

$phar->addFromString('index.php', php_strip_whitespace($srcRoot . DIRECTORY_SEPARATOR . "index.php"));
//addFile($phar, $srcRoot . DIRECTORY_SEPARATOR . "index.php", $srcRoot);

addDir($phar, $srcRoot . DIRECTORY_SEPARATOR . "vendor", $srcRoot);
addDir($phar, $srcRoot . DIRECTORY_SEPARATOR . "config", $srcRoot);
addDir($phar, $srcRoot . DIRECTORY_SEPARATOR . "module", $srcRoot);

$stub = <<<EOF
<?php
Phar::mapPhar("$filename");
require "phar://$filename/index.php";
__HALT_COMPILER();

EOF;

$phar->setStub($stub);
$phar->stopBuffering();

if (file_exists($pharPath)) {
    echo "Phar created successfully in $pharPath\n";
    chmod($pharPath, 0755);
} else {
    echo "Error during the compile of the Phar file $pharPath\n";
    exit(2);
}

function addDir($phar, $sDir, $baseDir = null) {
    $oDir = new RecursiveIteratorIterator (
        new RecursiveDirectoryIterator ($sDir),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($oDir as $sFile) {
        if (preg_match ('/\\.php$/i', $sFile)) {
            addFile($phar, $sFile, $baseDir);
        }
    }
}

function addFile($phar, $sFile, $baseDir = null) {
    if(VERBOSE_MODE) echo "Adding file $sFile\n";
    if (null !== $baseDir) {
        $phar->addFromString(substr($sFile, strlen($baseDir) + 1), php_strip_whitespace($sFile));
    } else {
        $phar->addFromString($sFile, php_strip_whitespace($sFile));
    }
}