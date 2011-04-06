<?php
  class Slimui extends CI_Model {
    function getFriendsList($limit = 0, $offset = 0) {
      if (!($peeps = cache_get('peeps'))) {
  	    $this->load->library('facebook');
  		  $req = $this->facebook->api('/me/friends', array('limit' => $limit,'offset' => $offset));
  		  foreach($req['data'] as $r) {
  		    $peeps[] = $r;
  		  }
  	    cache('peeps', $peeps, '3 minutes');
  		}
    	  return $peeps;
    }
      
       
    function getAlbumsPics($friendId) {
      $this->load->library('facebook');
      $id = $friendId;
      //$data['friend'] = $this->facebook->api("{$friendId}");
      $albums = $this->facebook->api($id. "/albums"); // grabbing all albums
      $pictures = array();
      $i = 0;
      foreach($albums['data'] as $album) {
         $albumId = $album['id'];
         $pics = $this->facebook->api($albumId. "/photos");
         foreach($pics['data'] as $p) {
           $pictures[] = array($p['picture'] => $p['source']);
         }
       }
       return $pictures;
      }
                
    function allPictures($friendId) {
      $this->load->library('facebook');
      $data['session'] = $this->facebook->getSession();
      if(!($allPics = cache_get('allPics'))) { // if not in cache go get all these pictires
        $id = $friendId; // grabbing all of users friends
    	    $albums = $this->facebook->api($id. "/albums");  // grab each album of friend
    	    foreach($albums['data'] as $a) {
    	      $pictures = $this->facebook->api($a['id']. '/photos', array('limit' => 0, 'offset' => 0));
    	      print_r($pictures);
    	      exit;
    	      foreach($pictures['data'] as $pics) {
    	      $images[] = $pics['picture'];
    	      }
    	    }
      cache('allPics',$data['pictures'],'5 minutes');
    } else {
      $allPics = cache_get('allPics');
      foreach ($allPics as $photo) {
        echo "<img src='{$photo}' />";
      }
    }
  }
  }
?>