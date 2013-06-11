<?php
require '../MercadoLivre/meli.php';

$meli = new Meli('1438123847347400', '7lLpxSDF5LeyDUfnykeHMIoh0tajGuTw');


if(!$_GET['code'] == '') {

	$authorize = $meli->authorize($_GET['code'], 'http://localhost/PHPSDK/examples/login.php');


	if($authorize['httpCode'] == 200) {
		echo "Your access_token: ".$authorize['body']->access_token."<br />";
	}

	try {
	    $refresh_token = $meli->refreshAccessToken();
	    echo "Your refresh_token: ".$authorize['body']->refresh_token."<br />";
	} catch (Exception $e) {
	  	echo "Exception: ",  $e->getMessage(), "\n";
	}

} else {
 	?>
 	<a href='<?php echo $meli->getAuthUrl('http://localhost/PHPSDK/examples/login.php'); ?>'>Login</a>
 	<?php
}
?>
