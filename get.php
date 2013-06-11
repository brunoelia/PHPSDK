<?php
require 'meli.php';

$meli = new Meli('1438123847347400', '7lLpxSDF5LeyDUfnykeHMIoh0tajGuTw');

$params = array('teste' => '1');

$result = $meli->get('/sites/MLB', $params);

print_r($result);

?>