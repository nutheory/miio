<? global $CONTROLLER, $FEATURED_LIST, $LIST_PAGE, $TAGLINE_LIST, $LOGGEDIN, $PAGING_SIZE, $LOC, $ITEM_COUNT; ?>
<?

  $users = array();
  $first = ($LIST_PAGE - 1) * $ITEM_COUNT;
  if ($first > count($FEATURED_LIST)) $first = 0;
  $last = $first + $ITEM_COUNT;
  if ($last > count($FEATURED_LIST)) $last = count($FEATURED_LIST);
  for ($u=$first;$u<$last;$u++)
  {
    $users[] = $FEATURED_LIST[$u];
  }
  foreach($TAGLINE_LIST as $tl){
     if($tl['page_id'] == $LIST_PAGE) $tagline = $tl['tagline'];
  }

?>

<? if (!$LOGGEDIN){ ?>
<div id="tagline"><?= $tagline ?></div>	
<? } else { ?>
<h2 id="featured_header" class="members"><img src="images/memberTabs.png">Members <span class="membersnote">Browse members, or <a href="search/member">search</a> members</span></h2>
<? } ?>
	<table id="membertable">
		<tr>
			<td id="back_btn"><img src="images/back_btn.png" height=40 width=40 onclick="Featured.Paginate('<?= $CONTROLLER ?>',<?= $LIST_PAGE-1 ?>)" onmouseover="Featured.Rollover(this,'back_btn',true)" onmouseout="Featured.Rollover(this,'back_btn',false)"></td>
			<td class="top">
				<ul id="featuredlist">
					<? foreach ($users as $f) { ?>
						<li id="feat_<?= $f['userid'] ?>">
							<a href="members/profile/<?= $f['userid'] ?>">
								<img src=<?= "\"".$LOC."profile_photos/".$f['image_url']."\""; ?>>
							</a>
							<label>
								<? 
								   if ($f['show_name'] && ($f['first_name'] && $f['last_name'])) echo $f['first_name']." ".$f['last_name'];  
								   else if ($f['show_name'] && ($f['first_name'] || $f['last_name'])) echo $f['first_name'].$f['last_name'];
								   else echo $f['username']; 
								?>
							</label>
						</li>
					<? } ?>
				</ul>
			</td>
			<td id="next_btn"><img src="images/next_btn.png" height=40 width=40 onclick="Featured.Paginate('<?= $CONTROLLER ?>',<?= $LIST_PAGE+1 ?>)" onmouseover="Featured.Rollover(this,'next_btn',true)" onmouseout="Featured.Rollover(this,'next_btn',false)"></td>
		</tr>
	</table>
<? if (!$LOGGEDIN){ ?><div id="featured_footer"><a href="signup"><button class="norm_button">Signup</button></a></div><? } ?>