<? global $SHARERS, $User, $Profile, $AVATAR_URL, $LOGGEDIN; ?>

<div id="sharer_list">
  <? foreach ($SHARERS as $id=>$shares) { ?>
    <? $Sharer = User::get($id); ?>
    <div class="sharer">
      <?
        if ($Sharer->photo == "") $avatar = $AVATAR_URL.'default.jpg';
        else $avatar = $AVATAR_URL.$Sharer->photo;
        $avatar .= "?x=" . floor(time()/DAY_IN_SEC);
      ?>
      <a href="members/profile/<?= $Sharer->id ?>" class="avatar"><img src="<?= $avatar ?>"></a>
      <div class="sharer_info">
        
        <a href="members/profile/<?= $Sharer->id ?>" class="sharer_name"><?= $Sharer->username ?></a>
        <span>shared these messages:</span>
      </div>
      
      <div class="shared_messages">
        <? foreach($shares as $post_id) { ?>
          <div class="message">
            <? $message = Post::get($post_id); ?>
            &quot;<?= $message['text'] ?>&quot;
            <span class="posted">
              <? $date = getdate($message['created_at']); $hour = Hour24to12($date['hours']); ?>
              Posted on
              <?= $date['mday'] ?> <?= $date['month'] ?>, <?= $date['year'] ?>
              at
              <?= $hour['hour'] ?>:<?= $date['minutes'] ?> <?= $hour['ampm'] ?>
            </span>
          </div>
        <? } ?>
      </div>
      <div class="clear"></div>
    </div>
  <? } ?>
</div>