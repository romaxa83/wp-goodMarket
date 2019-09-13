<?php
/**
 * метод для отладки
*/
function debug($arr)
{
    echo '<pre>' . print_r($arr,true) .'</pre>';
}

function dd($var) {
    echo '<pre>' . print_r($var,true) .'</pre>';die();
}

?>