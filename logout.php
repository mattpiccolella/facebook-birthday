<?php
	require_once('php-sdk/facebook.php');
	$app_id = "721823137830217";
	echo "YES";
	$facebook = new Facebook(
		array(
			'appId' => '721823137830217', 
			'secret' => 'd46d12ee0ee635d6fc3c64ab0e7016d7',
		)
	);
	setcookie('fbs_'.$facebook->getAppId(), '', time()-100, '/', 'http://ec2-54-245-17-195.us-west-2.compute.amazonaws.com');
	session_destroy();
	header('Location: /');
?>