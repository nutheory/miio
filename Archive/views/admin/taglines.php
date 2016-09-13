<? global $User, $Profile, $LOC, $QUEUE_LIST, $FEATURE_LIST, $TAGLINE_LIST; ?>

<link href="css/admin.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/admin/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/admin/jquery-ui-1.7.2.custom.min.js"></script>
<script type="text/javascript" src="js/admin/quickpager.jquery.js"></script>
<script type="text/javascript" src="js/admin/right.js"></script>
<script type="text/javascript" src="js/admin/admin.js"></script>

<div id="taglines">
	<div id="taglineform">
		<h1>Enter a Tagline</h1>
		<div><input type="text" id="taglineInput"><button id="save_tag">Add</button></div>
	</div>
	<div class="tag_section">
		<h1>Queued Members Tags</h1>
		<ul id="qlist" class="tlists">
			<? foreach($TAGLINE_LIST as $q) { ?>
				<? if ($q['page_id'] == "")  { ?>
				<li id="tagline_<?= $q['id'] ?>" name="<?= $q['id'] ?>">
					<div id="pos" class="number">new</div>
					<p><?= $q['tagline'] ?></p>
					<div class="tag_options">
						<span class="tag_delete">Delete</span>
						<input type="text" id="tag_input">
						<butto id="tag_send">Send</button>
					</div>
				</li>
				<? } ?>
			<? } ?>
		</ul>
	</div>
	<div class="tag_section">
		<h1>Featured Members Tags</h1>
		<ul id="taglinelist" class="tlists">
			<? foreach($TAGLINE_LIST as $t) { ?>
				<? if ($t['page_id'] != "") { ?>
				<li id="tagline_<?= $t['id'] ?>" name="<?= $t['id'] ?>">
					<div id="pos" class="number"><?= $t['page_id']; ?></div>
					<p><?= $t['tagline'] ?></p>
					<div class="tag_options">
						<span class="tag_delete">Delete</span>
					</div>
				</li>
				<? } ?>
			<? } ?>
		</ul>
	</div>
</div>

<div class="contextMenu" id="deleteMenu">
  <ul>
    <li id="delete"><img src="images/bin.png" />Delete</li>
  </ul>
</div>