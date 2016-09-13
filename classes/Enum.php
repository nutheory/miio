<?php

class Enum
{
  static $featured_status = array
  (
    'normal'    => 1,
    'queued'    => 2,
    'featured'  => 3
  );

  static $visibility = array
  (
    'public'    => 1,
    'private'   => 2
  );

  static $gender = array
  (
    'male'      => 1,
    'female'    => 2,
    'business'  => 3
  );

  static $ethnicity = array
  (
    'asian'           => 1,
    'black'           => 2,
    'eastindian'      => 3,
    'hispanic'        => 4,
    'middleeastern'   => 5,
    'nativeamerican'  => 6,
    'pacificislander' => 7,
    'white'           => 8,
    'other'           => 9
  );

  static $relationship = array
  (
    'single'      => 1,
    'involved'    => 2,
    'engaged'     => 3,
    'married'     => 4,
    'widowed'     => 5,
    'divorced'    => 6,
    'complicated' => 7,
    'open'        => 8,
    'other'       => 9,
    'na'          => 10
  );

  static $looking_for = array
  (
    'activity_partners' => 1,
    'chatting'          => 2,
    'dating'            => 3,
    'friends'           => 4,
    'networking'        => 5,
    'whatever'          => 6
  );

  static $source = array
  (
    'web'       => 1,
    'text'      => 2,
    'system'    => 101
  );

  static $sharing = array
  (
    'public'        => 1,
    'friends'       => 2,
    'private'       => 3,
    'public_group'  => 4,
    'private_group' => 5,
    'admin'         => 6
  );

  static $message_type = array
  (
    'text'          => 1,
    'photo'         => 2,
    'video'         => 3,
    'link'          => 4,
    'review'        => 5,
    'question'      => 6,
    'location'      => 7,
    'rss'           => 8,
    'share'         => 101,
    'reply'         => 102,
    'alert'         => 1000,
    'notification'  => 1001,
    'request'       => 1002,
    'admin'         => 1003
  );

  static $link_type = array
  (
    'url'         => 1,
    'image'       => 2,
    'embed'       => 3
  );

  static $follow_settings = array
  (
    'message'     =>1,
    'photo'       =>2,
    'video'       =>3,
    'link'        =>4,
    'review'      =>5,
    'question'    =>6,
    'location'    =>7,
    'rss'         =>8,
    'share'       =>101,
    'reply'       =>102
  );

  static $member_settings = array
  (
    'message'     =>1,
    'photo'       =>2,
    'video'       =>3,
    'link'        =>4,
    'review'      =>5,
    'question'    =>6,
    'location'    =>7,
    'admin'       =>999
  );

  static $userlist_display_opt = array
  (
    'short_list'  => 1,
    'long_list'   => 2,
    'phone_on'    => 3,
    'phone_off'   => 4,
    'mute_on'     => 5,
    'mute_off'    => 6
  );

  static $number_of = array
  (
    'friends'             => 1,
    'following'           => 2,
    'followers'           => 3,
    //'all_followed'        => 4,
    //'all_followers'       => 5,
    //'groups'              => 6,
    'public_groups'       => 7,
    'private_groups'      => 8,
    'admin_groups'        => 9,
    'owned_groups'        => 10,
    'follow_requests'     => 11,

    'members'             => 101,
    'admins'              => 102,
    'membership_requests' => 103
  );

}

?>