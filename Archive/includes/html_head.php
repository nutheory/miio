<?
/*#####*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Miio</title>
  <base href="<?= LOC ?>">

  <!-- CSS -->
  <link href="css/global.css" rel="stylesheet" type="text/css">
  <link href="css/main.css" rel="stylesheet" type="text/css">

  <? if (!LOGGEDIN || !$CONFIRMED) { ?>
    <link href="css/loggedout.css" rel="stylesheet" type="text/css">
  <? } ?>

  <!-- Javascript -->
  <script type="text/javascript" src="js/_browser.js"></script>
  <!--[if IE 7]>
    <script type="text/javascript" src="js/_ie.js"></script>
  <![endif]-->

  <script type="text/javascript">
    var HTTP_BASE = '<?= LOC ?>';
    var AVATAR_URL = '<?= AVATAR_URL ?>';
    var SERVERTIME = '<?= time() ?>';
    var USER_ID = '<?= $User ? $User->id : 0 ?>';
    var THUMB = '<?= THUMB ?>';
  </script>
  <script type="text/javascript" src="js/_lib.js"></script>
  <script type="text/javascript" src="js/application.js"></script>
  <? if ($User && $User->id!='0') { ?>
    <script type="text/javascript">
      var REFRESH_MODE = <?= ($User->refresh_rate==0)?"'m'":"'a'" ?>;
      var MESSAGE_REFRESH = <?= ($User->refresh_rate==0)?5000:$User->refresh_rate*1000 ?>;
    </script>
  <? } else { ?>
    <script type="text/javascript">
      var REFRESH_MODE = 'a';
      var MESSAGE_REFRESH = 5000;
    </script>
  <? } ?>

</head>

<body>