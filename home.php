<?php
	session_start();
	require_once('php-sdk/facebook.php');
	$facebook = new Facebook(
		  array(
				'appId' => '721823137830217', 
				'secret' => 'd46d12ee0ee635d6fc3c64ab0e7016d7',
	));
	$user = $facebook->getUser();
	//echo "<h1>".$user."</h1>";
	//$facebook->setExtendedAccessToken();
	$accessToken = $facebook->getAccessToken();
	if ($user == 0) {
	   header('Location: index.php');
	}
	else {
	     if (!(isset($_SESSION['loggedin']))) {
             	 header('Location: index.php');
	     }
	     else {
	     	$con = mysqli_connect("localhost","root","raspberrypie","Users");
        	if (!mysqli_connect_errno()) {
		    $user1 = $facebook->getUser();
		    //echo "<h1>" . $user1 . "</h1>";
		    $query = "SELECT * FROM UserAccessTokens WHERE userID = '$user1'";
		    $stmt = mysqli_prepare($con,$query);
		    mysqli_stmt_execute($stmt);
		    mysqli_stmt_store_result($stmt);
		    //echo "<h1>" . mysqli_stmt_num_rows($stmt) . "</h1>";
           	if (mysqli_stmt_num_rows($stmt) == 0) {
              	mysqli_query($con,"INSERT INTO UserAccessTokens (userID,accessTokens) VALUES ('$user','$accessToken')");
           	}
       		}	
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
		$facebook = new Facebook(array('appId' => '721823137830217', 'secret' => 'd46d12ee0ee635d6fc3c64ab0e7016d7',));
		$me = $facebook->api('/me');
		$user = $facebook->getUser();
		$access_token = $facebook->getAccessToken();
		//$new_access_token = $facebook->getExtendedAccessToken();
		//echo "<h1>" . $new_access_token . "</h1>";
		//var_dump($facebook->setExtendedAccessToken($facebook->getAccessToken()));
		//echo "<h1>MATT</h1>";
		//echo "<h1>" . $access_token . "Matt</h1>";
		//echo "<h1>" . $access_token_1 . "Matt</h1>";
	   	$user_profile = $facebook->api('/me', array('access_token'=>$access_token));
	   	echo "<img src='http://graph.facebook.com/" . $user . "/picture?type=large'>";
	   	echo "<br></br>";
	   	echo "Name: " . $user_profile['name']. "\n";
	   	echo "<br></br>";
       	echo "Facebook ID: " . $user . "\n";
	   	echo "<br></br>";
	   	$params = array( 'next' => 'http://ec2-54-245-17-195.us-west-2.compute.amazonaws.com/logout.php');
	   	$logoutURL = $facebook->getLogoutUrl($params);
	   	echo "<a href='" . $logoutURL . "'>Log-Out</a>";
	}
	catch(Exception $e) {
		var_dump($e->getTrace());
		echo "Error occurred.";
		$params = array( 'next' => 'http://ec2-54-245-17-195.us-west-2.compute.amazonaws.com/logout.php');
        $logoutURL = $facebook->getLogoutUrl($params);
        echo "<a href='" . $logoutURL . "'>Log-Out</a>";	
	}
	//$user_profile1 = $facebook->api('/me/friends?fields=name,birthday','GET');
	//print_r($user_profile1);
	//$friends_data = $user_profile1['data'];
	//echo "<form action='search.php' method='post'>";
	//echo "<input type='text' name='name'>";
	//echo "<input type='submit'>";
	//echo "</form>";
	}
	else {
	     echo "Something went wrong.";
	}
?>
</body>
</html>