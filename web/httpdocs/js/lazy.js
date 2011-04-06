(function($) {
  $(function() {
    $.each(instructions, function(friend_id) {
      $.get('/photos/' + friend_id, function(response) {
        // render response (add more photos to the UI)
      });
    });
  });  
})(jQuery);