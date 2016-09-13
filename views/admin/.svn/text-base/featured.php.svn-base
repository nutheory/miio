<? global $User, $Profile, $LOC, $QUEUE_LIST, $FEATURE_LIST, $TAGLINE_LIST; ?>

<link href="css/admin.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/admin/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/admin/ui.core.js"></script>
<script type="text/javascript" src="js/admin/jquery-ui-1.7.2.full.min.js"></script>
<script type="text/javascript" src="js/admin/quickpager.jquery.js"></script>
<script type="text/javascript" src="js/admin/right.js"></script>
<script type="text/javascript" src="js/admin/admin.js"></script>

<div id="queue">
	<div id="qDropzone">
	<ul id="queuedlist" class="flists">
		<? foreach($QUEUE_LIST as $q) { ?>
		<li id="queue_<?= $q['userid'] ?>" name="<?= $q['userid'] ?>" class="contextMenu">
			<img src="<?= $LOC."avatars/".$q['image_url']; ?>" alt="<?= $q['first_name'].' '.$q['last_name']; ?>" title="<?= $q['first_name'].' '.$q['last_name']; ?>">
			<div>
				<span class="remove">Remove</span>
				<input type="text" class="newPosition">
				<button class="assign_number">Send</button>
			</div>
			<label><a href="members/profile/<?= $q['userid'] ?>" target="_blank"><?= $q['username']; ?></a></label>
		</li>
		<? } ?>
	</ul>
	</div>
</div>

<div id="members">
	<ul id="featuredlist" class="flists">
		<? $counter = 0 ?>
		<? foreach($FEATURE_LIST as $f) { ?>
			<? $counter++ ?>
		<li id="queue_<?= $f['userid'] ?>" name="<?= $f['userid'] ?>" class="contextMenu">
			<img src="<?= $LOC."avatars/".$f['image_url']; ?>" alt="<?= $f['first_name'].' '.$f['last_name']; ?>" title="<?= $f['first_name'].' '.$f['last_name']; ?>">
			<div>
				<span class="person_position"><?= $counter ?></span>
				<span class="remove">Remove</span>
				<span class="return">Return</span>
			</div>
			<label><a href="members/profile/<?= $f['userid'] ?>" target="_blank"><?= $f['username']; ?></a></label>
		</li>
		<? } ?>
	</ul>
</div>


<div id="featured_paging">
	<div id="paging"></div>
</div>

<div class="contextMenu" id="featuredMenu">
  <ul>
	<li id="back_to_queue"><img src="images/bin.png" />Back to Queue</li>
    <li id="delete"><img src="images/bin.png" />Delete</li>
  </ul>
</div>
