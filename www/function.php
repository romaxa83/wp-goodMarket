<?php

/**
 * метод для отладки
 */
function debug($arr) {
    echo '<pre>' . print_r($arr, true) . '</pre>';
}

function dd($var) {
    echo '<pre>' . print_r($var, true) . '</pre>';
    die();
}

function recursiveChmod($path, $filePerm = 0644, $dirPerm = 0755) {
    if (!file_exists($path)) {
        return false;
    }
    if (is_file($path)) {
        chmod($path, $filePerm);
    } elseif (is_dir($path)) {
        $foldersAndFiles = scandir($path);
        $entries = array_slice($foldersAndFiles, 2);
        foreach ($entries as $entry) {
            recursiveChmod($path . "/" . $entry, $filePerm, $dirPerm);
        }
        chmod($path, $dirPerm);
    }
    return true;
}

?>
