<?php
    if ( $_GET['redirect'] ) {
        header( "Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] ) ;
        exit ;
    }
?>