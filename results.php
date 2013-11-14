<?php
	session_start();
	require_once('php-sdk/facebook.php');
	$facebook = new Facebook( array(
		  'appId' => '721823137830217', 
		  'secret' => 'd46d12ee0ee635d6fc3c64ab0e7016d7',
	 ));
	 $user = $facebook->getUser();
	 if ($user == 0) {
	     header('Location: index.php');
	 }
	 else {
	      if (!(isset($_SESSION['loggedin']))) {
             	header('Location: index.php');
		} 
	}
?>
<html>
<head>
	<title>Header</title>
</head>
<body>
<?php
	if ($user != 0) {
	try {
	   $user_profile = $facebook->api('/me','GET');
	   echo "<img src='" . "http://graph.facebook.com/" . $user . "/picture?type=large'>";
	   echo "<br></br>";
	   echo "Name: " . $user_profile['name']. "\n";
	   echo "<br></br>";
       echo "Facebook ID: " . $user . "\n";
	   echo "<br></br>";
	   $params = array( 'next' => 'http://ec2-54-245-17-195.us-west-2.compute.amazonaws.com/logout.php');
	   $logoutURL = $facebook->getLogoutUrl($params);
	   echo '<a href="' . $logoutURL . '">Log-Out</a>';
	}
    catch(Exception $e) {
		echo "Error occurred.";
		$facebook->destroySession();	
	}
	$friends_data = $_POST['id'];
	$query_string = $friends_data . '/feed';	 
	$user_post = $facebook->api('/me/feed','POST', array(
		'link' => 'http://www.google.com',
		'message' => 'So post',
		)
	)
	echo "ID: " . $friends_data;
	}
	else {
		echo "NO!";
	}
?>
</body>
</html>