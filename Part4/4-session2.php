<?php
    if ( empty( $_COOKIE['PHPSESSID'] ) ) {
        // $_GET['PHPSESSID'] �̐������`�F�b�N�i���߂̃T���v���j
        if ( ! empty( $_GET['PHPSESSID'] ) && preg_match( '/^[0-9a-zA-Z,-]{21,40}$/' , $_GET['PHPSESSID'] ) ) {
            // $_SERVER['HTTP_REFERER'] �𗘗p�����Z�b�V�����Œ�΍�
            // host�����̃`�F�b�N�ł��Ȃ����
            if ( ! empty( $_SERVER['HTTP_REFERER'] ) ) {
                $url_parts = parse_url( $_SERVER['HTTP_REFERER'] ) ;
                if ( @$url_parts['host'] == $_SERVER['HTTP_HOST'] ) {
                    // GET�̃Z�b�V��������COOKIE�ɃR�s�[
                    $_COOKIE['PHPSESSID'] = @$_GET['PHPSESSID'] ;
                }
            }
        }
    }
    session_start() ;
?>
