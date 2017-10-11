<?php
    // 一次配列変数を整形出力する関数
    function display_array( $arr , $array_name ) {
        echo "\r\n$array_name:\r\n" ;
        foreach( $arr as $key => $val ) {
            echo " [$key] => $val\r\n" ;
        }
    }

    display_array( $_GET , 'GET' ) ;
    display_array( $_POST , 'POST' ) ;
    display_array( $_COOKIE , 'COOKIE' ) ;
    display_array( $_SERVER , 'SERVER' ) ;
?>