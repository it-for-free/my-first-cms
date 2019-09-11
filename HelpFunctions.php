<?php

/**
 * Выводит все что угодно
 * 
 * @param type $array
 */
function trace($array, $file = null, $line = null)
{
    echo '<div id="trace">';
    echo '<h4>Файл : ' . $file . '</h4>';
    echo '<h4>Строка : ' . $line . '</h4>';
    echo '<h3><pre>' . print_r($array , 1) . '</pre></h3>';
    echo '</div>';
}

?>
