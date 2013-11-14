<?php
	session_start();
	require_once('php-sdk/facebook.php');
	$facebook = new Facebook(
		array(
			'appId' => '*******', 
			'secret' => '******',
		)
	);
	$user = $facebook->getUser();
	$accessToken = $facebook->getAccessToken();
	$con = mysqli_connect("localhost","root","*****","Users");
	if (!mysqli_connect_errno($con)) {
	   $result = mysql_query($con,"SELECT * FROM AccessTokens WHERE userID = '$user'");
	   if (mysql_num_rows($result) == 0) {
	      mysqli_query($con,"INSERT INTO AccessTokens (userID,accessToken) VALUES ('$user','$accessToken')");
	   }
	}				
	if ($user != 0 && isset($_SESSION['loggedin'])) {
	   header('Location: home.php');
	}
?>
<html>
<head>
	<title>Our Page</title>
</head>
<body>

<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
  FB.init({
    appId      : '721823137830217', // App ID
    channelUrl : 'ec2-54-245-17-195.us-west-2.compute.amazonaws.com/channel.html', // Channel File
    status     : true, // check login status
    cookie     : true, // enable cookies to allow the server to access the session
    xfbml      : true  // parse XFBML
  });

  // Here we subscribe to the auth.authResponseChange JavaScript event. This event is fired
  // for any authentication related change, such as login, logout or session refresh. This means that
  // whenever someone who was previously logged out tries to log in again, the correct case below 
  // will be handled. 
  FB.Event.subscribe('auth.authResponseChange', function(response) {
    // Here we specify what we do with the response anytime this event occurs. 
    if (response.status === 'connected') {
      // The response object is returned with a status field that lets the app know the current
      // login status of the person. In this case, we're handling the situation where they 
      // have logged in to the app.
      testAPI();
    } else if (response.status === 'not_authorized') {
      // In this case, the person is logged into Facebook, but not into the app, so we call
      // FB.login() to prompt them to do so. 
      // In real-life usage, you wouldn't want to immediately prompt someone to login 
      // like this, for two reasons:
      // (1) JavaScript created popup windows are blocked by most browsers unless they 
      // result from direct interaction from people using the app (such as a mouse click)
      // (2) it is a bad experience to be continually prompted to login upon page load.
      FB.login();
    } else {
      // In this case, the person is not logged into Facebook, so we call the login() 
      // function to prompt them to do so. Note that at this stage there is no indication
      // of whether they are logged into the app. If they aren't then they'll see the Login
      // dialog right after they log in to Facebook. 
      // The same caveats as above apply to the FB.login() call here.
      FB.login();
    }
  });
  };

  // Load the SDK asynchronously
  (function(d){
   var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
   if (d.getElementById(id)) {return;}
   js = d.createElement('script'); js.id = id; js.async = true;
   js.src = "//connect.facebook.net/en_US/all.js";
   ref.parentNode.insertBefore(js, ref);
  }(document));

  // Here we run a very simple test of the Graph API after login is successful. 
  // This testAPI() function is only called in those cases. 
  function testAPI() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {
      console.log('Good to see you, ' + response.name + '.');
    });
  }
  function fb_login(){
    FB.login(function(response) {

        if (response.authResponse) {
            console.log('Welcome!  Fetching your information.... ');
            //console.log(response); // dump complete info
            access_token = response.authResponse.accessToken; //get access token
            user_id = response.authResponse.userID; //get FB UID
            console.log('Your userID: ' + user_id);

            FB.api('/me', function(response) {
                user_email = response.email;
				window.location.replace("log.php");
                //get user email
          // you can store this data into your database             
            });

        } else {
            //user hit cancel button
            console.log('User cancelled login or did not fully authorize.');

        }
    }, {
        scope: 'publish_stream,email,friends_birthday'
    });
}
(function() {
    var e = document.createElement('script');
    e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
    e.async = true;
    document.getElementById('fb-root').appendChild(e);
}());
</script>
<?php
	if ($user == 0) {
	   echo "<a href='#' onclick='fb_login();'><img src='login_1.png' border='0' alt=''></a>";
	}
	else {
	     echo "Thanks for logging out.";
	     $user_profile = $facebook->api('/me','GET');
	     echo "Name: " . $user_profile['name'];
	}	  
?>
<!--
  Below we include the Login Button social plugin. This button uses the JavaScript SDK to
  present a graphical Login button that triggers the FB.login() function when clicked.

  Learn more about options for the login button plugin:
  /docs/reference/plugins/login/ -->

</body>
