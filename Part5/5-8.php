<?php
    ini_set( 'session.use_only_cookies' , 1 ) ;
    ini_set( 'session.use_trans_sid' , 0 ) ;
    session_start() ;
    echo SID."\r\n" ;
?>
