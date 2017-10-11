<?php
    error_reporting( E_ALL ) ;

    echo $_GET['message'] ;
    foreach( $_POST['messages'] as $message ) {
        echo $message ;
    }
    vardump( $_GET ) ;
?>
