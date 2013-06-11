<?php
require '../MercadoLivre/meli.php';

$meli = new Meli('1438123847347400', '7lLpxSDF5LeyDUfnykeHMIoh0tajGuTw');

$params = array();

$result = $meli->get('/sites/MLB', $params);

echo '<pre>';
print_r($result);
echo '</pre>';
?>