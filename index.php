<?php
require 'MercadoLivre/meli.php';

$meli = new Meli('1438123847347400', '7lLpxSDF5LeyDUfnykeHMIoh0tajGuTw');

if($_GET['code']) {
	
}
?>
<a href='<?php echo $meli->getAuthUrl('http://localhost/php-sdk-bruno/login.php'); ?>'>Login</a>