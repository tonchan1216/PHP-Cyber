<?php
    // telnet�Ѥ� LF -> CR+LF �򤫤���ե��륿
    function lf2crlf( $string ) {
        return str_replace( "\n" , "\r\n" , $string ) ;
    }

    ob_start( 'lf2crlf' ) ;
    var_dump( $_POST ) ;
    var_dump( $_FILES ) ;
?>