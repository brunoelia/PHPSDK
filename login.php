<?php
require 'MercadoLivre/meli.php';

$meli = new Meli('1438123847347400', '7lLpxSDF5LeyDUfnykeHMIoh0tajGuTw');

print_r($meli->authorize($_GET['code'], 'http://localhost/php-sdk-bruno/login.php'));

try {
    print_r($meli->refreshAccessToken());
} catch (Exception $e) {
  	echo "Exception: ",  $e->getMessage(), "\n";
}
?>