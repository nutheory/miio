<?
 include "../config.php";
//
// require_once "../miio_config/server_config.php";
require_once "twitterhelper.php";

if($_REQUEST['oauth_token'] != NULL)
{
  $to = new TwitterHelper(TWITTER_KEY, TWITTER_SECRET, Session::Get('oauth_request_token'), Session::Get('oauth_request_secret'));
  $tok = $to->getAccessToken();
  $final_token = $tok['oauth_token'];
  $final_secret = $tok['oauth_token_secret'];

  $getId = new TwitterHelper(TWITTER_KEY, TWITTER_SECRET, $final_token, $final_secret);
    $uInfo = $getId->OAuthRequest('http://twitter.com/statuses/user_timeline.json?count=1', array(), 'GET');
    $tInfo = json_decode($uInfo, true);
  $twitter_id = $tInfo[0]['user']['id'];
  $twitter_sn = $tInfo[0]['user']['screen_name'];
  $twitter_name = $tInfo[0]['user']['name'];
  $twitter_desc = $tInfo[0]['user']['description'];
  $twitter_image = $tInfo[0]['user']['profile_image_url'];
  $twitter_url = $tInfo[0]['user']['url'];

}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Miio</title>
<base href="<?= $LOC ?>">
<script type="text/javascript" src="js/_lib.js"></script>
<script type="text/javascript" src="js/user.js"></script>
<script type="text/javascript" src="js/signup.js"></script>
<script type="text/javascript" src="js/user_settings.js"></script>

</head>

<body>
<script>
  if (opener.document.getElementById('content'))
  {
    var parentContent = "<ul>";
    <? if($tInfo[0]['user']['screen_name'] != ''){ ?>
      parentContent += "<li class='title'><div id='signedin'>Congratulations! You are signed in as:</div><a href='http://www.twitter.com/<?= $tInfo[0]['user']['screen_name'] ?>' target='_blank'><img src='<?= $tInfo[0]['user']['profile_image_url'] ?>' class='avatar' ></a><div class='name'><a href='http://www.twitter.com/<?= $tInfo[0]['user']['screen_name'] ?>' target='_blank'>@<?= $tInfo[0][user][screen_name] ?></a></div></li>";
      parentContent += "<li id='editp'><p>Please make the following selections and then press the \"Save Twitter Settings\" button below.</p></li>";
      parentContent += "<li class='opt'>";
      parentContent += "<div class='label'>Publish my Miio messages to Twitter</div>";
      parentContent += "<div class='radios'><input type='radio' name='twitter_push' id='twitter_push' value='1' checked='checked'>yes <input type='radio' name='twitter_push' value='0'>no</div>";
      parentContent += "</li>";
      parentContent += "<li class='opt'>";
      parentContent += "<div class='label'>Publish my Miio replies to Twitter</div>";
      parentContent += "<div class='radios'><input type='radio' name='twitter_reply' id='twitter_reply' value='1' checked='checked'>yes <input type='radio' name='twitter_reply' value='0'>no</div>";
      parentContent += "</li>";
      parentContent += "<li class='opt'>";
      parentContent += "<div class='label'>Publish my Miio shares to Twitter</div>";
      parentContent += "<div class='radios'><input type='radio' name='twitter_share' id='twitter_share' value='1' checked='checked'>yes <input type='radio' name='twitter_share' value='0'>no</div>";
      parentContent += "</li>";
      <? } else { ?>
    <?
      $to = new TwitterHelper(TWITTER_KEY, TWITTER_SECRET);
        $tok = $to->getRequestToken();
        $token = $tok['oauth_token'];
        Session::Set('oauth_request_token',$token);
        Session::Set('oauth_request_secret',$tok['oauth_token_secret']);
        $request_link = $to->getAuthorizeURL($token);
    ?>
      parentContent += "<li id='error'>Twitter failed in subsequent request for user data. Please try again.</li>";
      parentContent += "<li id='retry'><a href='<?= $request_link ?>' target='_blank'><img src='images/twitter_signin.png' class='exbutton' alt='Sign-in to Twitter'></li>";
    <? } ?>
      parentContent += "</ul>";
    opener.DOM.SetHTML('content',parentContent);
    opener.DOM.Show('tsfooter');
    User.Settings.Feed.SaveTwitterToken('<?= $final_token ?>', '<?= $final_secret ?>', '<?= $twitter_id ?>', '<?= $twitter_sn ?>');



  } else if (opener.document.getElementById('signup')){

    <? if($twitter_sn != '') { ?>
      function Name_Free(ok){
        if(ok=='true'){
          opener.DOM.SetValue('username','<?= $twitter_sn ?>');
          opener.DOM.Show('twit_yes');
          opener.DOM.Show('name_valid');
        }else{
          opener.DOM.Show('twit_not');
        }

        opener.DOM.Hide('add_account');
        opener.DOM.Show('got_account');
        opener.DOM.SetValue('twitter_token','<?= $final_token ?>');
        opener.DOM.SetValue('twitter_secret','<?= $final_secret ?>');
        opener.DOM.SetValue('twitter_id','<?= $twitter_id ?>');
        opener.DOM.SetValue('twitter_sn','<?= $twitter_sn ?>');
        <? if ($twitter_name != "") { ?> opener.DOM.SetValue('bio_name','<?= $twitter_name ?>');<? } ?>
        <? if ($twitter_desc != "") { ?> opener.DOM.SetValue('bio_desc','<?= $twitter_desc ?>');<? } ?>
        <? if ($twitter_image != "") { ?> opener.DOM.SetValue('bio_image','<?= $twitter_image ?>');<? } ?>
        <? if ($twitter_url != "") { ?> opener.DOM.SetValue('bio_url','<?= $twitter_url ?>');<? } ?>
        var hparentContent = "<img src='<?= $tInfo[0]['user']['profile_image_url'] ?>' class='avatar' ><h2>@<?= $tInfo[0][user][screen_name] ?></h2>";
        var fparentContent = "<ul>";
        fparentContent += "<li><input type='checkbox' name='twitter_push' id='twitter_push' value='1' checked='checked'><label>Post my Miio Messages on Twitter</label></li>";
        fparentContent += "<li><input type='checkbox' name='twitter_reply' id='twitter_reply' value='1' checked='checked'><label>Post my Miio Replies on Twitter</label></li>";
        fparentContent += "<li><input type='checkbox' name='twitter_share' id='twitter_share' value='1' checked='checked'><label>Post my Miio Shares on Twitter</label></li>";
        fparentContent += "</ul>";
        opener.DOM.SetHTML('twitterInfo',hparentContent);
        opener.DOM.SetHTML('twitterOptions',fparentContent);
        self.close();
      }

      Signup.TwitterNameCheck('<?= $twitter_sn ?>');
    <? } else { ?>

    <?
      $to = new TwitterHelper(TWITTER_KEY, TWITTER_SECRET);
        $tok = $to->getRequestToken();
        $token = $tok['oauth_token'];
        Session::Set('oauth_request_token',$token);
        Session::Set('oauth_request_secret',$tok['oauth_token_secret']);
        $request_link = $to->getAuthorizeURL($token);
    ?>
      parentContent += "<li id='error'>Twitter failed in subsequent request for user data. Please try again.</li>";
      parentContent += "<li id='retry'><a href='<?= $request_link ?>' target='_blank'><img src='images/twitter_signin.png' class='exbutton' alt='Sign-in to Twitter'></li>";
    <? } ?>
    //      parentContent += "</ul>";
    // opener.document.getElementById('twitterInfo').innerHTML = parentContent;
    // opener.document.getElementById('kill_receiver').value = 'kill';

  }

</script>
</body>
</html>