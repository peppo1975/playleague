<?php

//echo __DIR__;
// Remove any trailing slashes from the path
//$rootPath = rtrim($rootPath, '\\/');

// Get real path for our folder
//$rootPath = realpath('configuratore');
$g = getcwd();
$g = str_replace("/app/webroot/my_pages/zipUnzip","",$g);

echo $g."<br>";


// $rootPath = realpath('/var/www/vhosts/timmytag.it/midland2023.timmytag.it/midland2015cake2');
$rootPath = realpath($g);

// Initialize archive object
$zip = new ZipArchive();
$zip->open('playleague_' . date("Y-m-d_His") . '.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

// Create recursive directory iterator
/** @var SplFileInfo[] $files */
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootPath),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $file) {
    // Skip directories (they would be added automatically)
    if (!$file->isDir()) {
        // Get real and relative path for current file
        $filePath = $file->getRealPath();
        if (!isFile($filePath)) {
            continue;
        }
        $relativePath = substr($filePath, strlen($rootPath) + 1);

        // Add current file to archive
        $zip->addFile($filePath, $relativePath);
    }
}

// Zip archive will be created only after closing object
$zip->close();
echo "OK END";


function isFile($filePath)
{
    $r = explode(".", $filePath);
    $ext = end($r);
    $res = false;

    switch ($ext) {
        case "php":
        case "html":
        case "js":
        case "css":
        case "ctp":
            $res = true;
            break;
    }

    return $res;
}