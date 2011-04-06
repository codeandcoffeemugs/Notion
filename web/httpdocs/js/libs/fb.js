//alert('loaded');
  (function($) {
    var user_data_perms = [
      'user_about_me'
      // ,'user_activities'
      // ,'user_birthday'
      // ,'user_education_history'
      // ,'user_events'
      // ,'user_groups'
      // ,'user_hometown'
      // ,'user_interests'
      // ,'user_likes'
      // ,'user_location'
      // ,'user_notes'
      // ,'user_online_presence'
      // ,'user_photo_video_tags'
      ,'user_photos'
      // ,'user_relationships'
      // ,'user_relationship_details'
      // ,'user_religion_politics'
      // ,'user_status'
      // ,'user_videos'
      // ,'user_website'
      // ,'user_work_history'
      // ,'email'
      // ,'read_friendlists'
      // ,'read_insights'
      // ,'read_mailbox'
      // ,'read_requests'
      // ,'read_stream'
      // ,'xmpp_login'
      // ,'ads_management'
      // ,'user_checkins'
      // ,'user_address'
      // ,'user_mobile_phone'
    ];
  
    var friend_data_perms = [
      'friends_about_me'
      // ,'friends_activities'
      // ,'friends_birthday'
      // ,'friends_education_history'
      // ,'friends_events'
      // ,'friends_groups'
      // ,'friends_hometown'
      // ,'friends_interests'
      // ,'friends_likes'
      // ,'friends_location'
      // ,'friends_notes'
      // ,'friends_online_presence'
      // ,'friends_photo_video_tags'
      // ,'friends_photos'
      // ,'friends_relationships'
      // ,'friends_relationship_details'
      // ,'friends_religion_politics'
      // ,'friends_status'
      // ,'friends_videos'
      // ,'friends_website'
      // ,'friends_work_history'
      // ,'manage_friendlists'
      // ,'friends_checkins'
    ];

    var publishing_perms = [
      'publish_stream'
      // ,'create_event'
      // ,'rsvp_event'
      // ,'sms'
      ,'offline_access'
      // ,'publish_checkins'
    ];

    var page_perms = [
      'manage_pages'
    ];
    
    window.all_perms = ([].concat(user_data_perms, friend_data_perms, publishing_perms, page_perms)).join(',');
    
    window.fbAsyncInit = function() {
      FB.init({
        appId: '<?php echo $appId; ?>', 
        status: true, 
        cookie: true, 
        xfbml: true
      });
      login();
    };
    
    window.login = function() {
      FB.getLoginStatus(function(response) {
        if (!response.session) {
          FB.login(function(response) {
            if (response.session) {
              save_session(response.session, function() {
                $('.logout').show();
                $('.login').hide();
              });
            }
          }, { perms: all_perms });
        } else {
          save_session(response.session, function() {
            $('.login').hide();
            $('.logout').show();
          });
        }
      });
    };
    
    window.save_session = function(session, callback) {
      $.post("<?php echo site_url('options/facebook') ?>", { session: session }, callback);
    };
    
    window.logout = function() {
      FB.logout(function() { 
        save_session({}, function() {
          $('.logout').hide();
          $('.login').show();
        });
      });
    };
  
    (function() {
      var e = document.createElement('script'); 
      e.async = true;
      e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js'; 
      document.getElementById('fb-root').appendChild(e);
    }());
  })(jQuery);