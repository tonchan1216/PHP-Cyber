<?php
    if ( empty( $_COOKIE['PHPSESSID'] ) ) {
        // $_GET['PHPSESSID'] の整合性チェック（ゆるめのサンプル）
        if ( ! empty( $_GET['PHPSESSID'] ) && preg_match( '/^[0-9a-zA-Z,-]{21,40}$/' , $_GET['PHPSESSID'] ) ) {
            // $_SERVER['HTTP_REFERER'] を利用したセッション固定対策
            // hostだけのチェックでかなりゆるめ
            if ( ! empty( $_SERVER['HTTP_REFERER'] ) ) {
                $url_parts = parse_url( $_SERVER['HTTP_REFERER'] ) ;
                if ( @$url_parts['host'] == $_SERVER['HTTP_HOST'] ) {
                    // GETのセッション鍵をCOOKIEにコピー
                    $_COOKIE['PHPSESSID'] = @$_GET['PHPSESSID'] ;
                }
            }
        }
    }
    session_start() ;
?>
