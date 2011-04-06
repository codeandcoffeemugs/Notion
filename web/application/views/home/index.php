<?php $this->load->view('home/header') ?>
    <?php echo $accessToken; ?>
    <div id="main">
       <div id="everyPicture">
        
       </div>
        <!-- List pictures of friends -->
        <div id="friendList">
        <?php foreach($peeps as $f): ?>
          <div class='fbFriends'>
            <a id="<?php echo $f['id']; ?>" href="<?php echo site_url("home/getAlbums/{$f['id']}"); ?>" class="getAlbum" ><img src="https://graph.facebook.com/<?php echo $f['id']; ?>/picture" /><h2><?php echo $f['name']; ?></h2></a>
          </div>
      <?php endforeach; ?>
        </div>
      </div>
    </div>
    
   <!--     <script src="/js/lazy.js"></script>
       <script>
         var instructions = <?php echo json_encode($album_ids) ?>;
       </script>
        -->
    <footer>
      
    </footer>
  </div> <!--! end of #container -->


  <!-- Javascript at the bottom for fast page loading -->

  <!-- Grab Google CDN's jQuery. fall back to local if necessary -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.js"></script>
  <script>!window.jQuery && document.write(unescape('%3Cscript src="js/libs/jquery-1.4.2.js"%3E%3C/script%3E'))</script>
  
  
  <!-- scripts concatenated and minified via ant build script-->
  <script src="js/plugins.js"></script>
  <script src="js/script.js"></script>
  <!-- end concatenated and minified scripts-->
  
  <script src="/js/slim.js"></script>
  
  <!--[if lt IE 7 ]>
    <script src="js/libs/dd_belatedpng.js"></script>
    <script> DD_belatedPNG.fix('img, .png_bg'); //fix any <img> or .png_bg background-images </script>
  <![endif]-->

  <!-- yui profiler and profileviewer - remove for production -->
  <script src="js/profiling/yahoo-profiling.min.js"></script>
  <script src="js/profiling/config.js"></script>
  <!-- end profiling code -->
  
  <!-- My login js script -->
  <script>
  var appId = '<?php echo $this->facebook->getAppId() ?>';
  </script>
  <script src="js/libs/fb.js"></script>
  <!-- end my login js script -->

  <!-- asynchronous google analytics: mathiasbynens.be/notes/async-analytics-snippet 
       change the UA-XXXXX-X to be your site's ID -->
  <script>
   // var _gaq = [['_setAccount', 'UA-XXXXX-X'], ['_trackPageview']];
   //    (function(d, t) {
   //     var g = d.createElement(t),
   //         s = d.getElementsByTagName(t)[0];
   //     g.async = true;
   //     g.src = ('https:' == location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
   //     s.parentNode.insertBefore(g, s);
   //    })(document, 'script');
  </script>
  
      <script type="text/javascript" charset="utf-8">
        // $('a').live('click',function(){
        //           $('.pics').remove();
        //           var albumId = $(this).attr('id');
        //           var accessToken = '<?php echo $accessToken; ?>';
        //           var url = 'https://graph.facebook.com/' +albumId+ '/photos?access_token=' +accessToken+ '&callback=?';
        //           $.getJSON(url, function(data){
        //                     var imgSrc = data.data[0].source;
        //                     $('#picsGoHere').append("<img class='pics' src='" +imgSrc+ "' />")
        //                     //alert(imgSrc);           
        //                               });
        //           //alert(url);
        //           return false;
        //         });
      </script>
      <script>
        if (!window.console) { window.console = { log: function() {}, error: function() {} }};
      
        window.fbAsyncInit = function() {
          FB.init({appId: '<?php echo $appId; ?>', status: true, cookie: true, xfbml: true});
          FB.getLoginStatus(function(response){
            if (!response.session) {
              $('.login').show();
              $('.logout').hide();
              login();
            } else {
              $('.login').hide();
              $('.logout').show();
            }
          });
        };
        
        window.login = function() {
          FB.login(function(response){
            if (response.session) {
              $('.login').hide();
              $('.logout').show();
            } else {
              $('.login').show();
              $('.logout').hide();
            }
          }, {perms: 'user_about_me, user_photos, friends_about_me, friends_photos, offline_access, read_stream'});
        };

        window.logout = function() {
          FB.logout();
          $('.login').show();
          $('.logout').hide();
          header('location: http://slimui.localhost');
        };
        (function() {
          var e = document.createElement('script'); e.async = true;
          e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
          document.getElementById('fb-root').appendChild(e);
        }(jQuery));
      </script>
</body>
</html>