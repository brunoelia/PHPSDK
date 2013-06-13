<?php
session_start('teste');

require '../MercadoLivre/meli.php';

$meli = new Meli('1438123847347400', '7lLpxSDF5LeyDUfnykeHMIoh0tajGuTw', $_SESSION['access_token']);


$body = array(
	'site_id' => 'MLB',
	'title' => ' subwoofer eros e-15 sds 2.7k 1350w rms 15 polegadas 4 ohms',
	'subtitle' => 'gdhdghf',
	'category_id' => 'MLB39287',
	'buying_mode' => 'buy_it_now',
	'listing_type_id' => 'bronze',
	'start_time' => '2013-06-13T17:38:00-03:00',
	'warranty' => 'dasdasdasd',
	'currency_id' => 'BRL',
	'available_quantity' => 5,
	'price' => 714.89,
	'condition' => 'new',
	'video_id' => ''
);

$params = array('access_token' => $_SESSION['access_token']);
	
$reponse = $meli->post('/items', $body, $params);

print_r($response);