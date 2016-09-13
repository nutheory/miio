<?

// GROUPS controller

/*********************************** PAGES  ***********************************/

function view()
{
  global $Group, $PARAMS;
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    Render('groups','notfound');
    return;
  }
  if ($Group->suspended)
  {
    Render('groups','suspended');
    return;
  }
  Render('groups','view');
}

function description()
{
  global $Group, $PARAMS;
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    Render('groups','notfound');
    return;
  }
  if ($Group->visibleTo($User->id))
  {
    Render('groups','description');
  }
}

function timeline()
{
  global $User, $Group, $PARAMS, $GET;
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    Render('groups','notfound');
    return;
  }
  if ($Group->visibleTo($User->id))
  {
    Show::Messages('group','timeline');
  }
}

function albums()
{
  global $Group, $PARAMS;
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    Render('groups','notfound');
    return;
  }
  if ($Group->visibleTo($User->id))
  {
    Render('groups','albums');
  }
}

function members()
{
  global $Group, $PARAMS;
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    Render('groups','notfound');
    return;
  }
  Show::Users('group','member');
}

function manage()
{
  global $Group, $PARAMS;
  if (!LOGGEDIN)
  {
    Render('user','login','ml');
    return;
  }
  else if (!CONFIRMED)
  {
    Render("signup","confirm","ml");
    return;
  }
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    Render('groups','notfound');
    return;
  }
  Render('groups','manage_membership');
}

function invitemembers()
{
  global $Group, $PARAMS;
  if (!LOGGEDIN)
  {
    Render('user','login','ml');
    return;
  }
  else if (!CONFIRMED)
  {
    Render("signup","confirm","ml");
    return;
  }
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    Render('groups','notfound');
    return;
  }
  Render('groups','invitemembers');
}

function report()
{
  global $Group, $PARAMS;
  if (!LOGGEDIN)
  {
    Render('user','login','ml');
    return;
  }
  else if (!CONFIRMED)
  {
    Render("signup","confirm","ml");
    return;
  }
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    Render('groups','notfound');
    return;
  }
  Render('groups','report_group');
}

function requests()
{
  global $Group, $Cache, $SESSION, $PARAMS, $GET;
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    Render('members','notfound');
    return;
  }
  if (!LOGGEDIN)
  {
    Render('user','login','ml');
    return;
  }
  else if (!CONFIRMED)
  {
    Render("signup","confirm","ml");
    return;
  }
  global $MESSAGES, $MESSAGE_FILTER;
  $lastcheck = $_POST['lastcheck'];
  $lastpost = $_POST['lastpost'];
  $now = time();
  $MESSAGE_FILTER = strtolower($_POST['filter']);
  $isfiltered = ($MESSAGE_FILTER != "");

  foreach ($Group->posts as $id)
  {
    $post = Post::get($id);
    if ($post['type']=='memberrequestreceived') $Messages[] = $id;
  }

  if ($_POST['response']=='status')
  {
    $updates = GetMessageUpdateStatus($Messages,$lastcheck,$lastpost);
    if (count($updates)>0)
    {
      $response = array();
      $response['lastcheck'] = $now;
      $response['updates'] = array_reverse($updates);
      if ($_POST['mode']) $response['mode'] = $_POST['mode'];
      if ($_POST['initial_load']) $response['init'] = $_POST['init'];
      echo json_encode($response);
    }
    else echo "ok_".$now;
  }
  else if ($_POST['initial_load']==1)
  {
    $MESSAGES = $Messages;
    Render("partials","messagelist");
  }
  else if ($_POST['lastmessage'])
  {
    $updates = GetNextGroupOfMessages($Messages,$_POST['lastmessage']);
    if (count($updates)>0)
    {
      echo json_encode($updates);
    }
    else echo "ok";
  }
  else echo "ok_".$now;
}

/******************************** ADMIN  PAGES ********************************/

function profile()
{
  global $User, $Group, $PARAMS;
  if (!LOGGEDIN)
  {
    Render('user','login','ml');
    return;
  }
  else if (!CONFIRMED)
  {
    Render("signup","confirm","ml");
    return;
  }
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    Render('groups','notfound');
    return;
  }
  Render('groups','profile');
}

function changename()
{
  global $User, $Group, $PARAMS;
  if (!LOGGEDIN)
  {
    Render('user','login','ml');
    return;
  }
  else if (!CONFIRMED)
  {
    Render("signup","confirm","ml");
    return;
  }
  else
  {
    $Group = Group::get($PARAMS);
    if (!$Group)
    {
      Render('groups','notfound');
      return;
    }
    Render('groups','changename');
  }
}

function editphoto()
{
  global $User, $Group, $PARAMS;
  if (!LOGGEDIN)
  {
    Render('user','login','ml');
    return;
  }
  else if (!CONFIRMED)
  {
    Render("signup","confirm","ml");
    return;
  }
  else
  {
    $Group = Group::get($PARAMS);
    if (!$Group)
    {
      Render('groups','notfound');
      return;
    }
    Render('groups','editphoto');
  }
}

function editalbums()
{
  global $User, $Group, $PARAMS;
  if (!LOGGEDIN)
  {
    Render('user','login','ml');
    return;
  }
  else if (!CONFIRMED)
  {
    Render("signup","confirm","ml");
    return;
  }
  else
  {
    $Group = Group::get($PARAMS);
    if (!$Group)
    {
      Render('groups','notfound');
      return;
    }
    Render('groups','editalbums');
  }
}

function managemembers()
{
  global $User, $Group, $PARAMS;
  if (!LOGGEDIN)
  {
    Render('user','login','ml');
    return;
  }
  else if (!CONFIRMED)
  {
    Render("signup","confirm","ml");
    return;
  }
  else
  {
    $Group = Group::get($PARAMS);
    if (!$Group)
    {
      Render('groups','notfound');
      return;
    }
    if (!$User->isAdminOf($PARAMS))
    {
      echo '<div class="not_admin">';
      echo "This page is reserved for Administrators of $Group->groupname only.";
      echo '</div>';
      return;
    }
    Show::Users('group','manage_members');
  }
}

function manageadmins()
{
  global $User, $Group, $PARAMS, $GET;
  if (!LOGGEDIN)
  {
    Render('user','login','ml');
    return;
  }
  else if (!CONFIRMED)
  {
    Render("signup","confirm","ml");
    return;
  }
  else
  {
    $Group = Group::get($PARAMS);
    if (!$Group)
    {
      Render('groups','notfound');
      return;
    }
    if (!$User->isOwnerOf($PARAMS))
    {
      echo '<div class="not_admin">';
      echo "This page is reserved for the Owner of $Group->groupname only.";
      echo '</div>';
      return;
    }
    Show::Users('group','admin_'.$GET['type']);
  }
}

function transferownership()
{
  global $User, $Group, $PARAMS, $GET;
  if (!LOGGEDIN)
  {
    Render('user','login','ml');
    return;
  }
  else if (!CONFIRMED)
  {
    Render("signup","confirm","ml");
    return;
  }
  else
  {
    $Group = Group::get($PARAMS);
    if (!$Group)
    {
      Render('groups','notfound');
      return;
    }
    if (!$User->isOwnerOf($PARAMS))
    {
      echo '<div class="not_admin">';
      echo "This page is reserved for the Owner of $Group->groupname only.";
      echo '</div>';
      return;
    }
    Show::Users('group','owner_'.$GET['type']);
  }
}

function disband()
{
  global $Group, $PARAMS;
  if (!LOGGEDIN)
  {
    Render('user','login','ml');
    return;
  }
  else if (!CONFIRMED)
  {
    Render("signup","confirm","ml");
    return;
  }
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    Render('groups','notfound');
    return;
  }
  Render('groups','disband');
}

/************************************ AJAX ************************************/

function get_groups()
{
  global $LOC, $GET, $PARAMS;
  if ($PARAMS=='newest' || $PARAMS=='popular')
  {
    // get array of ID=>photo
    $photos = array (
                      "1"=>"1.jpg",
                      "2"=>"2.jpg",
                      "3"=>"17.jpg",
                      "4"=>"19.jpg",
                      "5"=>"22.jpg",
                      "6"=>"23.jpg",
                      "7"=>"24.jpg",
                      "8"=>"25.jpg",
                      "9"=>"26.jpg",
                      "10"=>"27.jpg",
                      "11"=>"35.jpg",
                      "12"=>"34.jpg",
                      "13"=>"33.jpg",
                      "14"=>"32.jpg",
                      "15"=>"31.jpg",
                      "16"=>"30.jpg",
                      "17"=>"29.jpg",
                      "18"=>"28.jpg",
                      "19"=>"8.jpg",
                      "20"=>"7.jpg",
                      "21"=>"3.jpg",
                      "22"=>"1.jpg",
                      "23"=>"2.jpg",
                      "24"=>"17.jpg",
                      "25"=>"19.jpg",
                      "26"=>"22.jpg",
                      "27"=>"23.jpg",
                      "28"=>"24.jpg",
                      "29"=>"25.jpg",
                      "30"=>"27.jpg",
                      "31"=>"35.jpg",
                      "32"=>"34.jpg",
                      "33"=>"33.jpg",
                      "34"=>"32.jpg",
                      "35"=>"31.jpg",
                      "36"=>"30.jpg",
                      "37"=>"29.jpg",
                      "38"=>"28.jpg",
                      "39"=>"8.jpg",
                      "40"=>"27.jpg",
                      "41"=>"35.jpg",
                      "42"=>"34.jpg",
                      "43"=>"33.jpg",
                      "44"=>"32.jpg",
                      "45"=>"31.jpg",
                      "46"=>"30.jpg",
                      "47"=>"29.jpg",
                      "48"=>"28.jpg",
                      "49"=>"8.jpg",
                      "50"=>"27.jpg",
                      "51"=>"35.jpg",
                      "52"=>"34.jpg",
                      "53"=>"33.jpg",
                      "54"=>"32.jpg",
                      "55"=>"31.jpg",
                      "56"=>"30.jpg",
                      "57"=>"29.jpg",
                      "58"=>"28.jpg",
                      "59"=>"8.jpg",
                      "60"=>"27.jpg",
                      "61"=>"35.jpg",
                      "62"=>"34.jpg",
                      "63"=>"33.jpg",
                      "64"=>"32.jpg",
                      "65"=>"31.jpg",
                      "66"=>"30.jpg",
                      "67"=>"29.jpg",
                      "68"=>"28.jpg",
                      "69"=>"8.jpg",
                      "70"=>"27.jpg"
                    );
    shuffle($photos);
    $output = array();
    $output['photos'] = $photos;
    $output['base'] = $LOC . "filler/";
    echo json_encode($output);
  }
  else if ($PARAMS=='list')
  {
    global $USER_LIST, $LIST_PAGE, $USER_FILTER;
    if ($GET['page']) $LIST_PAGE = $GET['page'];
    else $LIST_PAGE = 1;
    $list = User::getFeaturedGroups();
    foreach ($list as $group) $USER_LIST[] = $group['id'];
    Render('partials','userlist');
  }
  else if ($PARAMS=='browse')
  {
    Render('partials','groupcategories');
  }
  else
  {
    echo "Unrecognized request: $PARAMS";
  }
}

function category()
{
  global $PARAMS, $GET, $USER_LIST, $LIST_PAGE, $USER_FILTER;
  if ($GET['page']) $LIST_PAGE = $GET['page'];
  else $LIST_PAGE = 1;
  $USER_FILTER = "";
  $list = User::getGroupsByCategory($PARAMS);
  foreach ($list as $group) $USER_LIST[] = $group['id'];
  Render('partials','userlist');
}

function update_membership()
{
  global $User, $Group, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    echo "Invalid group id";
    die;
  }

  if ($User->updateMemberSettings($PARAMS,$_POST)) echo "ok";
  else echo "Unknown error";
}

function send_invitation()
{
  global $User, $Group, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  if (!$User->isAdminOf($PARAMS))
  {
    echo "Not an admin";
    die;
  }
  $Group = Group::get($PARAMS);
  if (!$Group->id)
  {
    echo "Invalid group id";
    die;
  }
  $list = array();
  if ($_POST['invitelist']) $list=explode(',',$_POST['invitelist']);
  Notify::GroupInvitation($Group,$User,$_POST['invite'],$list);

  echo "ok";
}

function accept_invitation()
{
  global $Group, $User, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    echo "Group not found";
    die;
  }
  if ($Group->visibility=='private')
  {
    if ($User->invitedToJoin($Group->id))
    {
      $User->cancelMembershipRequest($PARAMS);
    }
    else if ($User->isMember($Group->id))
    {
      echo "You are already a member of this group";
      die;
    }
    else
    {
      echo "You are not invited to join this group";
      die;
    }
  }
  $ok = $User->joinGroup($PARAMS);
  if ($ok) echo "ok".$Group->groupname;
  else echo "Unknown error";
}

function decline_invitation()
{
  global $Group, $User, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    echo "Group not found";
    die;
  }
  if ($User->isMember($Group->id))
  {
    echo "You are already a member of this group.";
    die;
  }
  $User->cancelMembershipRequest($PARAMS);
  Notify::InvitationDeclined($User,$Group);
  echo "ok".$Group->groupname;
}

function accept_member()
{
  global $Group, $User, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  if (!$User->isAdminOf($PARAMS))
  {
    echo "You are not an Administrator";
    die;
  }
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    echo "Group not found";
    die;
  }
  $Member = User::get($_POST['user']);
  if (!$Member->membershipRequested($PARAMS))
  {
    echo "gone";
    die;
  }
  $Member->cancelMembershipRequest($PARAMS);
  $ok = $Member->joinGroup($PARAMS);
  if ($ok)
  {
    Notify::MembershipAccepted($User,$Member,$Group);
    echo "ok".$Group->groupname."_".$Member->username;
  }
  else echo "Unknown error";
}

function decline_member()
{
  global $Group, $User, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  if (!$User->isAdminOf($PARAMS))
  {
    echo "You are not an Administrator";
    die;
  }
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    echo "Group not found";
    die;
  }
  $Member = User::get($_POST['user']);
  $Member->cancelMembershipRequest($PARAMS);
  Notify::MembershipDeclined($User,$Member,$Group);
  echo "ok".$Group->groupname."_".$Member->username;
}

function decline_admin()
{
  global $Group, $User, $PARAMS;

  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    echo "Group not found";
    die;
  }
  if (!$Group->hasInvitedAdmin($User->id))
  {
    echo "gone";
    die;
  }
  $Group->cancelAdmin($User->id);
  Notify::AdminDeclined($User,$Group);
  echo "ok".$Group->groupname;
}

function accept_admin()
{
  global $Group, $User, $PARAMS;

  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    echo "Group not found";
    die;
  }
  if (!$Group->hasMember($User->id))
  {
    echo "You are not a member";
    die;
  }
  if (!$Group->hasInvitedAdmin($User->id))
  {
    echo "gone";
    die;
  }
  $ok = $Group->makeAdmin($User->id);
  if ($ok)
  {
    Notify::AdminAccepted($User,$Group);
    echo "ok".$Group->groupname;
  }
  else echo "Unknown error";
}

function decline_ownership()
{
  global $Group, $User, $PARAMS;

  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    echo "Group not found";
    die;
  }
  if (!$Group->hasInvitedOwner($User->id))
  {
    echo "gone";
    die;
  }
  $Group->cancelOwner($User->id,true);
  Notify::OwnerDeclined($User,$Group);
  echo "ok".$Group->groupname;
}

function accept_ownership()
{
  global $Group, $User, $PARAMS;

  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    echo "Group not found";
    die;
  }
  if (!$User->isMemberOf($PARAMS))
  {
    echo "You are not a member";
    die;
  }
  if (!$User->isAdminOf($PARAMS))
  {
    echo "You are not an Administrator";
    die;
  }
  if (!$Group->hasInvitedOwner($User->id))
  {
    echo "gone";
    die;
  }
  $ok = $Group->makeOwner($User->id);
  if ($ok) echo "ok".$Group->groupname;
  else echo "Unknown error";
}

function submit_report()
{
  global $User, $Group, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    echo "Invalid group id";
    die;
  }
  if ($User->reportGroup($PARAMS))
  {
    // send report message to appropriate place
    Notify::ReportedGroup($User,$Group,$_POST);
    echo "ok";
  }
  else echo "Unknown error";
}

function join_group()
{
  global $User, $Group, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    echo "Invalid group id";
    die;
  }
  if ($User->joinGroup($PARAMS)) echo "ok";
  else echo "Unknown error";
}

function leave_group()
{
  global $User, $Group, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    echo "Invalid group id";
    die;
  }
  if (!$User->isOwnerOf($Group->id))
  {
    if ($User->leaveGroup($PARAMS)) echo "ok";
    else echo "Unknown error";
  }
  else echo "Owners Can't leave groups unless they disband or transfer ownership";
}

function request_membership()
{
  global $User, $Group, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    echo "Invalid group id";
    die;
  }
  if ($User->requestMembership($PARAMS)) echo "ok";
  else echo "Unknown error";
}

/********************************* ADMIN AJAX *********************************/

function update_profile()
{
  global $User, $Group, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  if (!$User->isAdminOf($PARAMS))
  {
    echo "Not an admin";
    die;
  }
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    echo "Invalid group id";
    die;
  }
  $Group->description = trim($_POST['description']);
  $Group->name = trim($_POST['fullname']);
  $Group->show_name = ($_POST['showname']==1) ? 1 : 0;
  $Group->country = $_POST['country'];
  $Group->region = $_POST['state'];
  $Group->city = $_POST['city'];
  $url = trim($_POST['website']);
  $Group->website = (Validate::url($url)) ? Validate::protocol($url) : "";
  $Group->category = intval($_POST['category']);
  $Group->keywords = preg_split('/\s/',$_POST['tags'],null,PREG_SPLIT_NO_EMPTY);
  if ($_POST['makeprivate']==1)
  {
    $Group->visibility = Enum::$visibility['private'];
    $notify = true;
  }
  $ok = $Group->update();
  if ($notify) Notify::ChangePrivacy($Group);
  if ($ok) echo "ok";
  else echo "An unknown error occurred.";
}

function update_name()
{
  global $User, $Group, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  if (!$User->isOwnerOf($PARAMS))
  {
    echo "Not the owner";
    die;
  }
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    echo "Invalid group id";
    die;
  }

  $groupname = trim($_POST['name']);
  if ($Group->groupname==$groupname)
  {
    echo "ok";
    die;
  }
  if (User::exists('username',$groupname) || Group::exists('groupname',$groupname))
  {
    echo "taken";
    die;
  }
  if (!Validate::username($groupname))
  {
    echo "invalid";
    die;
  }
  $oldname = $Group->groupname;
  $Group->groupname=$groupname;
  $ok = $Group->update();
  Notify::ChangeName($Group,$oldname);
  if ($ok) echo "ok";
  else echo "An unknown error occurred.";
}

function update_profile_photo()
{
  global $User, $Group, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  if (!$User->isAdminOf($PARAMS))
  {
    echo "Not an admin";
    die;
  }
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    echo "Invalid group id";
    die;
  }
  Image::deleteProfilePhoto($Group->photo);
  $avatarurl = Image::saveFromTemp($_POST['photo'],'group_avatar',false,$Group->id);
  $photourl = Image::saveFromTemp($_POST['photo'],'group',true,$Group->id);
  $Group->photo = $photourl;
  $ok = $Group->update(false);
  if ($ok) echo "ok".$Group->getProfilePhoto();
  else echo "error";
}

function delete_profile_photo()
{
  global $User, $Group, $PARAMS, $LOC;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  if (!$User->isAdminOf($PARAMS))
  {
    echo "Not an admin";
    die;
  }
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    echo "Invalid group id";
    die;
  }
  Image::deleteProfilePhoto($Group->photo);
  $Group->photo = "";
  $ok = $Group->update(false);
  if ($ok) echo "ok";
  else echo "error";
}

function update_album()
{
  global $User, $Group, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  if (!$User->isAdminOf($PARAMS))
  {
    echo "Not an admin";
    die;
  }
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    echo "Invalid group id";
    die;
  }
  $album = $_POST['album_id'];
  if (!$Group->albums[$album])
  {
    echo "This is not your group's album.";
    die;
  }
  $ok = $Group->updateAlbum($album,$_POST['title'],$_POST['description']);
  if ($ok) echo "ok$album";
  else echo "unknown error";
}

function upload_album()
{
  global $User, $Group, $PARAMS, $LOC;
  if (!LOGGEDIN)
  {
    $error = "Not logged in";
  }
  else if (!$User->isAdminOf($PARAMS))
  {
    $error = "Not an admin";
  }
  else
  {
    $Group = Group::get($PARAMS);
    if (!$Group)
    {
      $error = "Invalid group id";
    }
    else
    {
      if ($_POST['album_id'] == 'new')
      {
        // new album
        if (count($Group->albums)>4)
        {
          $error = "too_many";
        }
        else
        {
          $album = $Group->createAlbum($_POST['title'],$_POST['description']);
        }
      }
      else if (is_numeric($_POST['album_id']))
      {
        // update existing album
        $album = $_POST['album_id'];
      }
      else
      {
        $error = "bad_id";
      }
    }
  }

  if ($error)
  {
    echo "<script type='text/javascript'>";
    echo "top.".$_POST['js_error']."('$error');";
    echo "</script>";
  }
  else
  {
    $response = array();
    $response['errors'] = array();
    $response['files'] = array();
    $response['id'] = $album;

    // upload new photos
    foreach ($_FILES as $ord=>$file)
    {
      if ($file['error']==0)
      {
        $photo = array();
        $photo['album'] = $album;
        $photo['order'] = $ord;
        $photo['original_filename'] = $file['name'];
        $photo['title'] = $photo['original_filename'];
        $time = split(' ',microtime());
        $name = $time[1] . $time[0];
        $result = Image::save($name,$file,PHOTO_MAX_WIDTH,PHOTO_MAX_HEIGHT,'album');
        if ($result['error'])
        {
          $response['errors'][] = $file['name'].": ".$result['error'];
        }
        else
        {
          $photo['saved_filename'] = $result['filename'];
          $Group->savePhoto($photo);
          $response['files'][] = $file['name'];
        }
      }
    }
    $r = json_encode($response);
    echo "<script type='text/javascript'>";
    echo "top.".$_POST['js_return']."('$r');";
    echo "</script>";
  }
}

function delete_photo()
{
  global $User, $Group, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  if (!$User->isAdminOf($PARAMS))
  {
    echo "Not an admin";
    die;
  }
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    echo "Invalid group id";
    die;
  }
  $album = $_POST['album'];
  if (!$Group->albums[$album])
  {
    echo "This is not your group's album.";
    die;
  }
  $photo = $_POST['photo'];
  if (!$Group->albums[$album]['photos'][$photo])
  {
    echo "Photo is not in this album.";
    die;
  }
  if ($Group->deletePhoto($album,$photo)) echo "ok";
  else echo "Unable to delete photo.";
}

function delete_album()
{
  global $User, $Group, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  if (!$User->isAdminOf($PARAMS))
  {
    echo "Not an admin";
    die;
  }
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    echo "Invalid group id";
    die;
  }
  $album = $_POST['album_id'];
  if (!$Group->albums[$album])
  {
    echo "This is not your group's album.";
    die;
  }
  if ($Group->deleteAlbum($album)) echo "ok";
  else echo "Unable to delete album.";
}

function remove_member()
{
  global $User, $Group, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  if (!$User->isAdminOf($PARAMS))
  {
    echo "Not an admin";
    die;
  }
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    echo "Invalid group id";
    die;
  }
  $Member = User::get($_POST['userid']);
  if ($Member->isOwnerOf($PARAMS)) echo "isowner";
  else if ($Member->isAdminOf($PARAMS)) echo "isadmin";
  else if ($Member->leaveGroup($PARAMS,true)) echo "ok".$Member->username;
  else echo "Unable to remove member.";
}

function invite_admin()
{
  global $User, $Group, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  if (!$User->isOwnerOf($PARAMS))
  {
    echo "Not the owner";
    die;
  }
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    echo "Invalid group id";
    die;
  }
  if (!ValidID($_POST['userid']))
  {
    echo "Invalid user id";
    die;
  }
  $userid = $_POST['userid'];
  if ($Group->owner==$userid)
  {
    echo "isowner";
    die;
  }
  if ($Group->hasAdmin($userid)) echo "isadmin";
  else if ($Group->inviteAdmin($userid)) echo "ok";
  else echo "Unable to invite member.";
}

function cancel_admin_invite()
{
  global $User, $Group, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  if (!$User->isOwnerOf($PARAMS))
  {
    echo "Not the owner";
    die;
  }
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    echo "Invalid group id";
    die;
  }
  if (!ValidID($_POST['userid']))
  {
    echo "Invalid user id";
    die;
  }
  $userid = $_POST['userid'];
  if ($Group->owner==$userid)
  {
    echo "isowner";
    die;
  }
  if ($Group->hasAdmin($userid)) echo "isadmin";
  else if ($Group->cancelAdmin($userid))
  {
    Notify::AdminCanceled($userid,$Group);
    echo "ok";
  }
  else echo "Unable to cancel invitation.";
}

function remove_admin()
{
  global $User, $Group, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  if (!$User->isOwnerOf($PARAMS))
  {
    echo "Not the owner";
    die;
  }
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    echo "Invalid group id";
    die;
  }
  if (!ValidID($_POST['userid']))
  {
    echo "Invalid user id";
    die;
  }
  $userid = $_POST['userid'];
  if ($Group->owner==$userid)
  {
    echo "isowner";
    die;
  }
  if ($Group->removeAdmin($userid))
  {
    Notify::AdminRemoved($userid,$Group);
    echo "ok";
  }
  else echo "Unable to remove as Admin.";
}

function invite_owner()
{
  global $User, $Group, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  if (!$User->isOwnerOf($PARAMS))
  {
    echo "Not the owner";
    die;
  }
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    echo "Invalid group id";
    die;
  }
  $Member = User::get($_POST['userid']);
  if (!$Member->isMemberOf($PARAMS)) echo "notmember";
  else if (!$Member->isAdminOf($PARAMS)) echo "notadmin";
  else if ($Group->inviteOwner($Member->id)) echo "ok".$Member->username;
  else echo "Unable to invite member.";
}

function cancel_owner_invite()
{
  global $User, $Group, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    echo "Invalid group id";
    die;
  }
  if ($Group->last_owner==$User->id)
  {
    echo "lastowner";
    die;
  }
  else if (!$User->isOwnerOf($PARAMS))
  {
    echo "Not the owner";
    die;
  }
  $Member = User::get($_POST['userid']);
  if ($Group->cancelOwner($Member->id))
  {
    Notify::OwnerCanceled($Member,$Group);
    echo "ok".$Member->username;
  }
  else echo "Unable to cancel invitation.";
}

function delete_group()
{
  global $Group, $User, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  if (!$User->isOwnerOf($PARAMS))
  {
    echo "Not the owner";
    die;
  }
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    echo "Group not found";
    die;
  }
  if ($Group->removeGroup()) echo "ok";
  else echo "Unknown error";
}
