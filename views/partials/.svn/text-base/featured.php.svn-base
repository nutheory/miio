<? global $CONTROLLER, $FEATURED_LIST, $LIST_PAGE, $TAGLINE_LIST, $LOGGEDIN, $PAGING_SIZE, $LOC; ?>
<?
  $users = array();
  $first = ($LIST_PAGE - 1) * $PAGING_SIZE;
  if ($first > count($FEATURED_LIST)) $first = 0;
  $last = $first +  $PAGING_SIZE;
  if ($last > count($FEATURED_LIST)) $last = count($FEATURED_LIST);
  for ($u=$first;$u<$last;$u++)
  {
     $users[] = $FEATURED_LIST[$u];
  }
  foreach($TAGLINE_LIST as $tl)
  {
     if($tl['page_id'] == $LIST_PAGE) $tagline = $tl['tagline'];
  }

?>
<?= $PAGING_SIZE ?><?= $LIST_PAGE ?><?= $FEATURED_LIST ?><?= $LOC ?>
<script type="text/javascript" src="js/featured.js"></script>
<? if (!$LOGGEDIN){ ?>
	<div id="tagline"><?= $tagline ?></div>	
<? } else { ?>
	<h2 class="members"><img src="images/memberTabs.png">Members <span class="membersnote">Browse members, or search members</span></h2>
<? } ?>
<table id="membertable">
	<tr>
		<td id="back_btn"><img src="images/back_btn.png" height=40 width=40 onclick="Featured.Paginate('<?= $CONTROLLER ?>',<?= $LIST_PAGE-1 ?>)"></td>
		<td class="top">
			<ul id="featuredlist">
				<? foreach ($users as $f) { ?>
					<li id="feat_<?= $f['userid'] ?>"><a href="members/profile/<?= $f['userid'] ?>"><img src=<?= "\"".$LOC."profile_photos/".$f['image_url']."\""; ?>
					<? if ($f['first_name'] && $f['last_name']) echo " alt=\"".$f['username']." | ".$f['first_name']." ".$f['last_name']."\" ";
					   else if ($f['first_name'] || $f['last_name']) echo "alt=\"".$f['username']." | ".$f['first_name'].$f['last_name']."\" ";
					   else echo "alt=\"".$f['username']."\" "; ?>
					<? if ($f['first_name'] && $f['last_name']) echo "title=\"".$f['username']." | ".$f['first_name']." ".$f['last_name']."\"";
					   else if ($f['first_name'] || $f['last_name']) echo "title=\"".$f['username']." | ".$f['first_name'].$f['last_name']."\"";
					   else echo "title=\"".$f['username']."\">"; ?></a></li>
				<? } ?>
			</ul>
		</td>
		<td id="next_btn"><img src="images/next_btn.png" height=40 width=40 onclick="Featured.Paginate('<?= $CONTROLLER ?>',<?= $LIST_PAGE+1 ?>)"></td>
	</tr>
</table>
<? if (!$LOGGEDIN){ ?><div id="featured_footer"><a href="signup"><img src="images/q_mark.png"></a></div><? } ?>
</div>
<script type="text/javascript">
  Featured.Init('<?= $CONTROLLER ?>');
</script>