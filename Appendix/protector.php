<?php

function protector_sanitize( $arr )
{
    if ( is_array( $arr ) ) {
        // �ѿ����������к�
        if ( ! empty( $arr['_SESSION'] ) || ! empty( $arr['_COOKIE'] ) || ! empty( $arr['_SERVER'] ) || ! empty( $arr['_ENV'] ) || ! empty( $arr['_FILES'] ) || ! empty( $arr['GLOBALS'] ) ) exit ;
        return array_map( 'protector_sanitize', $arr ) ;
    }

    // �̥�Х��ȹ����к�
    return str_replace( "\0", "", $arr ) ;
}

$_GET    = protector_sanitize( $_GET ) ;
$_POST   = protector_sanitize( $_POST ) ;
$_COOKIE = protector_sanitize( $_COOKIE ) ;

// ���å���󸰤����Ѥ���XSS, HTTP�쥹�ݥ�ʬ���к�
// POST �� URI�������ޤ줿���å���󸰤Ϥ�����̵�뤹��
ini_set( 'session.use_only_cookies' , 1 ) ;
// ���å������깶����򤱤뤿��ˤϡ�������겼�Σ��Ԥ򥳥��ȥ����Ȥ��롣
if ( ! empty( $_GET[ session_name() ] ) && empty( $_COOKIE[ session_name() ] ) && ! preg_match( '/[^0-9A-Za-z,-]/' , $_GET[ session_name() ] ) ) {
    $_COOKIE[ session_name() ] = $_GET[ session_name() ] ;
}

// PHP_SELF�����Ѥ���XSS�����HTTP�쥹�ݥ�ʬ�乶��ؤ��к�
$_SERVER['PHP_SELF'] = strtr( @$_SERVER['PHP_SELF'] , array('<'=>'%3C','>'=>'%3E',"'"=>'%27','"'=>'%22' , "\r" => '' , "\n" => '' ) ) ;
$_SERVER['PATH_INFO'] = strtr( @$_SERVER['PATH_INFO'] , array('<'=>'%3C','>'=>'%3E',"'"=>'%27','"'=>'%22' , "\r" => '' , "\n" => '' ) ) ;

?>
