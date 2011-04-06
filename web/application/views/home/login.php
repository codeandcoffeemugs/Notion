<!DOCTYPE html>
<html>
<head>
	<meta charset=utf-8 />
	<title></title>
	<link rel="stylesheet" type="text/css" media="screen" href="" />
	<!--[if IE]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body>
  <header>
    
      <h1>Slim UI</h1>
      <div id="fb-root"></div>
      <div>
        <!-- <button onclick="login();" class="login">Login</button>
                <button onclick="logout();" style="display:none;" class="logout">Logout</button> -->
        
        <fb:login-button size="xlarge" onlick="login()" class="login">
           Login with Facebook
        </fb:login-button>
      </div>
      
      

  </header>
  <!-- Javascript at the bottom for fast page loading -->

  <!-- Grab Google CDN's jQuery. fall back to local if necessary -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.js"></script>
  <script>!window.jQuery && document.write(unescape('%3Cscript src="js/libs/jquery-1.4.2.js"%3E%3C/script%3E'))</script>
	<script>
    if (!window.console) { window.console = { log: function() {}, error: function() {} }};
  
    window.fbAsyncInit = function() {
      FB.init({appId: '<?php echo $appId; ?>', status: true, cookie: true, xfbml: true});
      // FB.getLoginStatus(function(response){
      //         if (!response.session) {
      //           login();
      //         } else {
      //           header('location: http://slimui.localhost');
      //         }
      //       });
    };
    
    window.login = function() {
      FB.login(function(response){
        if (response.session) {
          header('location: http://slimui.localhost');
        } else {
          $('.login').show();
        }
      }, {perms: 'user_about_me, user_photos, friends_about_me, friends_photos, offline_access, read_stream'});
    };

    (function() {
      var e = document.createElement('script'); e.async = true;
      e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
      document.getElementById('fb-root').appendChild(e);
    }(jQuery));
  </script>
</body>
</html>