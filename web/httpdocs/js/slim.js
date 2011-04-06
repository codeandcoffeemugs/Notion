/*
$(document).ready(function(){
  $('.getAlbum').click(function(){
    var fblog = FB.getSession();
    var at = fblog.access_token;
    console.log(fblog);
    var aid = $(this).attr('id');
    var url = "https://graph.facebook.com/" +aid+ "/albums?access_token=" +at+ "?callback=?"
    // alert(aid);
    //     alert(url);
     $.getJSON(url,function(data) {
        console.log(data);
      });
    return false;
  });
});
*/

$(document).ready(function(){
  $('.getAlbum').click(function(){
    var fblog = FB.getSession();
    var at = fblog.access_token;
    var aid = $(this).attr('id');
    FB.api('/' +aid+ '/albums', function(res) {
      console.log(res.data);
      $.each(res.data, function(i,fb) {
        var albumId = fb.id;
        FB.api('/' +albumId+ '/photos', function(json) {
          console.log(json.data);
          $.each(json.data, function(i,album) {
            var pic = album.picture;
            $('#everyPicture').append("<img src='" +pic+ "' />");
          });
        });
      });
    });
    //var url = "https://graph.facebook.com/" +aid+ "/albums?access_token=" +at+ "?callback=?"
    // alert(aid);
    //     alert(url);
     // $.getJSON(url,function(data) {
     //         console.log(data);
     //       });
    return false;
  });
});