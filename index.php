<?php
require 'meli.php';

$meli = new Meli('1438123847347400', '7lLpxSDF5LeyDUfnykeHMIoh0tajGuTw');
?>
<a href='<?php echo $meli->auth_url('http://localhost/php-sdk-bruno/login.php'); ?>'>Login</a>