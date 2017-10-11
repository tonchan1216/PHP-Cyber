<?php
session_start() ;
$sessid4remove =  @$_SESSION['old_session_id'] ;
$old_session_id = session_id() ;
if ( ! empty( $sessid4remove ) ) {
    foreach ( explode( ';' , session_save_path() ) as $path ) {
        if ( @unlink( $path . '/sess_' . $sessid4remove ) ) break ;
    }
}
if ( session_regenerate_id() ) {
    $_SESSION['old_session_id'] = $old_session_id ;
}
?>
