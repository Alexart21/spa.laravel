<?php
//$targetFolder = $_SERVER['DOCUMENT_ROOT'].'/storage/app/public';
$targetFolder = __DIR__ . '/../storage/app/public';
$linkFolder =  __DIR__ . ' /../public/storage';
symlink($targetFolder,$linkFolder);
echo 'Symlink process successfully completed';
