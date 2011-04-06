
<div id="main">
 <?php if($accessToken): ?>
  <?php 
   $req = $this->facebook->api('/me/friends',array('limit' => 25,'offset' => 0));
   //print_r($req);
   ?>

    <!-- List pictures of friends -->
    <?php foreach($req['data'] as $friend): ?>
      <div class='fbFriends'>
        <?php 
        $albums = $this->facebook->api($friend['id']. "/albums", array('access_token' => $accessToken)); // grabbing all albums
        ?>

        <?php foreach($albums['data'] as $al) {
                     if($al['name'] == 'Profile Pictures') {
                       $wall = $al['id'];
                       //echo $wall;
          }
        } ?>
        
        <?php if($wall): ?>
        <img src="https://graph.facebook.com/<?php echo $friend['id']; ?>/picture" /><a id=<?=$wall; ?> href="" class="getAlbum" ><h2><?php echo $friend['name']; $wall = "";  ?></h2></a>
        <?php else: ?>
          <img src="https://graph.facebook.com/<?php echo $friend['id']; ?>/picture" /><h2><?php echo $friend['name']; ?></h2>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
    <hr />
  <?php endif; ?>
  <div id="picsGoHere"></div>
 
  </div>
</div>