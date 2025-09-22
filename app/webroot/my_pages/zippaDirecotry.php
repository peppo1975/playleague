<?php

//https://stackoverflow.com/questions/4914750/how-to-zip-a-whole-folder-using-php
// Get real path for our folder
//$rootPath = realpath('../../../midland2015cake2');
$rootPath = realpath('../../');
//echo $rootPath."<br>";
// Initialize archive object
$zip = new ZipArchive();
// $zip->open('../staging-folder.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
$zip->open('../staging-folder.zip');
// Create recursive directory iterator
/** @var SplFileInfo[] $files */
$files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath),
        RecursiveIteratorIterator::LEAVES_ONLY
);

//print_r($files);
//foreach ($files as $name => $file)
//{
//    $filePath = $file->getRealPath();
//    echo $filePath . "<br>";
//}

//exit();

$nomeDacercare = "ws";

echo "<h2>{$nomeDacercare}</h2>";

foreach ($files as $name => $file)
{
    // Skip directories (they would be added automatically)
    if (!$file->isDir())
    {
        // Get real and relative path for current file
        $filePath = $file->getRealPath();

        $relativePath = substr($filePath, strlen($rootPath) + 1);

        $nameFolder = substr($relativePath, 0, strlen($nomeDacercare));

//        if ($nameFolder == $nomeDacercare) :

        $fileType = substr($filePath, strlen($filePath) - 3);

        if ($fileType == "php" || $fileType == "css" || $fileType == ".js" || $fileType == "tml" || $fileType == "ctp") :

            echo $relativePath . " - {$fileType} -" . "<br>";

            // Add current file to archive
            $zip->addFile($filePath, $relativePath);

        endif;

//        endif;
    }
}

// Zip archive will be created only after closing object
$zip->close();

echo "<br>FINE";

// Get real path for our folder
//$rootPath = realpath('../staging-folder');

// // Initialize archive object
// $zip = new ZipArchive();
// $zip->open('../staging-folder.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

// // Create recursive directory iterator
// /** @var SplFileInfo[] $files */
// $files = new RecursiveIteratorIterator(
//     new RecursiveDirectoryIterator($rootPath),
//     RecursiveIteratorIterator::LEAVES_ONLY
// );



// foreach ($files as $name => $file) {
//     // Skip directories (they would be added automatically)
//     if (!$file->isDir()) {
//         // Get real and relative path for current file
//         $filePath = $file->getRealPath();

//         $fileType = substr($filePath, strlen($filePath) - 3);

//         if ($fileType == "php" || $fileType == "css" || $fileType == ".js" || $fileType == "tml" || $fileType == "tpl") :

//             $relativePath = substr($filePath, strlen($rootPath) + 1);

//             echo "{$relativePath}<br>";


//             // Add current file to archive
//            $zip->addFile($filePath, $relativePath);

//         endif;
//     }
// }


// // Zip archive will be created only after closing object
// $zip->close();
