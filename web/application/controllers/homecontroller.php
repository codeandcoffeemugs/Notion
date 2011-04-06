<?php
class HomeController extends CI_Controller {
     
	function index() {
		$this->load->library('facebook');
    $data['session'] = $this->facebook->getSession();
    if (!$data['session']) {
      $data['appId'] = $this->facebook->getAppId();
      $this->load->view('home/login', $data);
    } else {
      $data['appId'] = $this->facebook->getAppId();
      $data['base'] = $this->config->item('base_url');
      $data['accessToken'] = $data['session']['access_token'];
      $data['peeps'] = $this->slimui->getFriendsList();
      $this->load->view('home/index', $data);
    }
	}
	
	
	function getAlbums($friendId) {
	  $data['pictureSrc'] = $this->slimui->getAlbumsPics($friendId);
	  //$this->load->view('home/albums', $data);
	}
	
	function allPictures() {
	  $this->load->library('facebook');
	  $data['session'] = $this->facebook->getSession();
	  if(!($value = cache_get('allPics'))) { // if not in cache go get all these pictires
	    $req = $this->facebook->api('/me/friends'); // grabbing all of users friends
  	  foreach($req['data'] as $friends) {
  	    //$friendsIds = $friends['id'];
  	    $albums = $this->facebook->api($friends['id']. "/albums");  // grab each album of each friend
  	    foreach($albums['data'] as $a) {
  	      $pictures = $this->facebook->api($a['id']. '/photos', array('limit' => 1, 'offset' => 0));
  	      foreach($pictures['data'] as $pics) {
  	      $data['pictures'][] = $pics['picture'];
  	      }
  	    }
  	  }
	  cache('allPics',$data['pictures'],'5 minutes');
  } else {
    $allPics = cache_get('allPics');
    foreach ($allPics as $photo) {
      echo "<img src='{$photo}' />";
    }
  }
	  
	  // echo "<pre>";
	  //     print_r($data['pictures']);
	  //     echo "</pre>";
	  // foreach($data['pictures'] as $pic) {
	  //       echo "<img src='{$pic}' />";
	  //     }
	}
	
	function login() {
	  $this->load->library('facebook');
	  print_r($this->facebook->getSession());
	}
	
	function albums() {
	  $this->load->library('facebook');
	  if ($this->facebook->getSession()) {
	    print_r($this->facebook->api('me'));
	  }
	  echo '<a href="/home/login">home</a>';
	}
	
}

/* End of file home.php */
/* Location: ./system/application/controllers/home.php */