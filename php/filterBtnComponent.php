<?php

function filterBtn($value, $label) {
    $btn = "
        <button class=\"filter-btn\" name='filter' value='$value'>$label</button>
    ";

    echo $btn;
}

?>