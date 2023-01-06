<?php

function flash()
{
    if(!isset($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return '
    <div class="alert alert-'.$flash['type'] . ' alert-dismissable">' .
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="fa fa-times"></i> '. $flash['message'] .'</h4>
    </div>';
}


function asset(string $path) {
    return $_SERVER['REQUEST_SCHEME']. '://' . $_SERVER['HTTP_HOST'] . $path;
}
