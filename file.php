<?php 
    session_start();
    $tmp = $_SESSION['attachment_name'];

    $filename = $tmp;
    $file = 'uploads/'.$tmp;
    
    header('Content-type: application/pdf');
    header('Content-Disposition: inline; filename="' . $filename . '"');
    header('Content-Transfer-Encoding: binary');
    header('Content-Length: ' . filesize($file));
    header('Accept-Ranges: bytes');
    @readfile($file);
    

 ?>