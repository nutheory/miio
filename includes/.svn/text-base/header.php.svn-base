<?

?>
<div id="top_bar">
  <div>
    <? if (LOGGEDIN && !$RPW) { ?>
      <span id="welcome">
        <?
          $time = microtime(true);
          if ($User->photo != "")
          {
            $photo = "profile_photos/$User->photo";
            echo "<a href='".LOC."members/profile/".$User->id."'><img src='$photo' height=".HEADER_PHOTO_SIZE." width=".HEADER_PHOTO_SIZE." id='user_photo' alt='photo'></a>";
          }
          else echo "<a href='<?= LOC ?>#profile'><img src='profile_photos/default.jpg' height=".HEADER_PHOTO_SIZE." width=".HEADER_PHOTO_SIZE." id='user_photo' alt='photo' style='display:none'></a>";
        ?>
        Welcome back, <a href='<?= LOC ?>members/profile/<?= $User->id ?>' id="header_username"><?= $User->username ?></a>
      </span>
    <? } ?>

    <span>
      <? if (LOGGEDIN && !$RPW) { ?>
        <? if ($CONFIRMED) { ?>
          <span class="selected" id="editprofile_selected" style="display:none;">Edit Profile</span>
          <a id="editprofile_unselected" href="<?= LOC ?>#profile/profileinfo">Edit Profile</a>
          &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
        <? } ?>
        <a href="forms/logout">Logout</a>
      <? } else { ?>
        &nbsp;&nbsp;&nbsp;<a href="user/login" id="loginlink">Login</a>&nbsp;&nbsp;&nbsp;
        |
        &nbsp;&nbsp;&nbsp;<a href="signup" id="signuplink">Sign up</a>
      <? } ?>
    </span>
  </div>
</div>

<div id="top_nav">

  <div class="links">
    <div id="headlink_search" <? if ($CONTROLLER=='search') echo 'class="active"'; ?>>
      <a class="header_search png" href="#" onclick="return SearchHeader.Dropdown();">Search</a>
      <a class="header_search_img" href="#" onclick="return SearchHeader.Dropdown();" id="search_down_link"><img class="search_carat" src="images/med_down_carat.png"></a>
      <a class="header_search_img" href="#" onclick="return SearchHeader.Dropdown();" id="search_up_link" style="display:none"><img class="search_carat" src="images/med_down_carat.png"></a>
      <div id="header_search_menu" style="display:none">
        <a href="search/all" id="menu_item_search_timeline">Public Timeline</a>
        <a href="search/member" id="menu_item_search_members">Members</a>
        <a href="search/group" id="menu_item_search_groups">Groups</a>
      </div>
    </div>

    <ul>
      <li class="logo"><a href="<?= LOC ?>"><img class="logo" src="images/logo_sm.png"></a></li>

      <? if ($CONTROLLER=='user' && !$RPW) { ?>
        <li class="active" id="headlink_dashboard"><a href="user">Dashboard</a></li>
      <? } else { ?>
        <li id="headlink_dashboard"><a href="user">Dashboard</a></li>
      <? } ?>

      <li class="divider">|</li>

      <? if ($CONTROLLER=='tabs' && !($PAGE=='categories' || $PAGE=='groups')) { ?>
        <li class="active" id="headlink_all"><a href="tabs/all">Public Timeline</a></li>
      <? } else { ?>
        <li id="headlink_all"><a href="tabs/all">Public Timeline</a></li>
      <? } ?>

      <li class="divider">|</li>

      <? if ($CONTROLLER=='members') { ?>
        <li class="active" id="headlink_members"><a href="members">Members</a></li>
      <? } else { ?>
        <li id="headlink_members"><a href="members">Members</a></li>
      <? } ?>

      <li class="divider">|</li>

      <? if ($CONTROLLER=='tabs' && $PAGE=='groups') { ?>
        <li class="active" id="headlink_groups"><a href="tabs/groups">Groups</a></li>
      <? } else { ?>
        <li id="headlink_groups"><a href="tabs/groups">Groups</a></li>
      <? } ?>

      <li class="divider">|</li>

      <? if ($CONTROLLER=='tabs' && $PAGE=='categories') { ?>
        <li class="active" id="headlink_categories"><a href="tabs/categories">Categories</a></li>
      <? } else { ?>
        <li id="headlink_categories"><a href="tabs/categories">Categories</a></li>
      <? } ?>

    </ul>
  </div>

</div>
<iframe id="upload_target" name="upload_target" src="" style="display:none;width:90%"></iframe>
<? Render("partials","utility_output"); ?>
