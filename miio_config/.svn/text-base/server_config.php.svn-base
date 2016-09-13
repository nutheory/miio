<?php
// Server-specific configuration settings

if ($_SERVER['HTTP_HOST']=='localhost')
{
  /**************************** Development /  local ****************************/
  // cache
  $CACHE_SERVERS = array( array('server'=>'localhost','port'=>11211) );

  // db
  define("MIIO_DB_USER",'miio');
  define("MIIO_DB_PASSWORD",'GreySwandir!5');
  // other
  define("SMS_EMAIL_HOST","b.miio.com");

  define("HOST","localhost");
  define("LOCAL",true);
  define("BASE","/websites/miio/");
  define("CONFIG_PATH","/websites/miio/");
  define("LOC","http://".HOST."/miio/");
  define("COOKIE_HOST","");
  define("PHOTO_URL","http://localhost/miio/file_storage/");
  define("PROFILE_PHOTO_URL","http://localhost/miio/profile_photos/");
  define("AVATAR_URL","http://localhost/miio/avatars/");
  define("LOGINHOST","http://localhost/miio/");
  define("THUMB","utilities/ss.php?url=");

  // consider defining as classes:
  $ID_DB = array
  (
    array("host"=>"localhost", "database"=>"miio2_ids")
  );

  $USER_DB = array
  (
    array("host"=>"localhost", "database"=>"miio2_users_0"),
    array("host"=>"localhost", "database"=>"miio2_users_1")
  );

  $USER_INDEX = array
  (
    array("host"=>"localhost", "database"=>"miio2_index_users"),
    array("host"=>"localhost", "database"=>"miio2_index_users")
  );

  $GROUP_DB = array
  (
    array("host"=>"localhost", "database"=>"miio2_groups_0"),
    array("host"=>"localhost", "database"=>"miio2_groups_1")
  );

  $GROUP_INDEX = array
  (
    array("host"=>"localhost", "database"=>"miio2_index_groups"),
    array("host"=>"localhost", "database"=>"miio2_index_groups")
  );

  $POST_DB = array
  (
    array("host"=>"localhost", "database"=>"miio2_posts_0_0"),
    array("host"=>"localhost", "database"=>"miio2_posts_1_0"),
    array("host"=>"localhost", "database"=>"miio2_posts_0_1"),
    array("host"=>"localhost", "database"=>"miio2_posts_1_1")
  );

  $POST_INDEX = array
  (
    array("host"=>"localhost", "database"=>"miio2_index_posts"),
    array("host"=>"localhost", "database"=>"miio2_index_posts"),
    array("host"=>"localhost", "database"=>"miio2_index_posts"),
    array("host"=>"localhost", "database"=>"miio2_index_posts")
  );
}
else
{
  /**************************** beta.miio.com ****************************/
  date_default_timezone_set('America/Los_Angeles');
  // cache
  $CACHE_SERVERS = array
  (
    array('server'=>'192.168.10.2','port'=>11211)
    //array('server'=>'192.168.10.201','port'=>11211),
    //array('server'=>'192.168.10.202','port'=>11211),
    //array('server'=>'192.168.10.203','port'=>11211),
    //array('server'=>'192.168.10.204','port'=>11211)
  );
  // db
  define("MIIO_DB_USER",'miio');
  define("MIIO_DB_PASSWORD",'GreySwandir!5');
  // other
  define("SMS_EMAIL_HOST","b.miio.com");

  define("HOST","beta.miio.com");
  define("LOCAL",false);
  define("BASE","/home/beta.miio.com/");
  define("CONFIG_PATH","/home/beta.miio.com/");
  define("LOC","http://".HOST);
  define("COOKIE_HOST","");
  define("PHOTO_URL",LOC."/file_storage/");
  define("PROFILE_PHOTO_URL",LOC."/profile_photos/");
  define("AVATAR_URL",LOC."/avatars/");
  define("LOGINHOST",LOC);
  define("THUMB","utilities/ss.php?url=");

  $ID_DB = array
  (
    array("host"=>"192.168.10.2", "database"=>"miio2_ids")
  );

  $USER_DB = array
  (
    array("host"=>"192.168.10.2", "database"=>"miio2_users_0"),
    array("host"=>"192.168.10.2", "database"=>"miio2_users_1")
  );

  $USER_INDEX = array
  (
    array("host"=>"192.168.10.2", "database"=>"miio2_index_users"),
    array("host"=>"192.168.10.2", "database"=>"miio2_index_users")
  );

  $GROUP_DB = array
  (
    array("host"=>"192.168.10.2", "database"=>"miio2_groups_0"),
    array("host"=>"192.168.10.2", "database"=>"miio2_groups_1")
  );

  $GROUP_INDEX = array
  (
    array("host"=>"192.168.10.2", "database"=>"miio2_index_groups"),
    array("host"=>"192.168.10.2", "database"=>"miio2_index_groups")
  );

  $POST_DB = array
  (
    array("host"=>"192.168.10.2", "database"=>"miio2_posts_0_0"),
    array("host"=>"192.168.10.2", "database"=>"miio2_posts_1_0"),
    array("host"=>"192.168.10.2", "database"=>"miio2_posts_0_1"),
    array("host"=>"192.168.10.2", "database"=>"miio2_posts_1_1")
  );

  $POST_INDEX = array
  (
    array("host"=>"192.168.10.2", "database"=>"miio2_index_posts"),
    array("host"=>"192.168.10.2", "database"=>"miio2_index_posts"),
    array("host"=>"192.168.10.2", "database"=>"miio2_index_posts"),
    array("host"=>"192.168.10.2", "database"=>"miio2_index_posts")
  );
}


?>