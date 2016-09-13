<?php

class Places
{
  static function get_cities($country_name,$state_name)
  {
    return false;
    global $DB;
    $DB->connect(GENERAL_DB_SLAVE,GENERAL_DB);
    $country = Places::get_country_code($country_name);
    $state = Places::get_state_code($country,$state_name);
    $sql = "SELECT * FROM cities WHERE country='$country' AND region='$state' ORDER BY city_name";
    return $DB->query($sql);
  }

  static function get_countries()
  {
    return false;
    global $DB;
    $DB->connect(GENERAL_DB_SLAVE,GENERAL_DB);
    $sql = "SELECT * FROM country_codes ORDER BY name";
    return $DB->query($sql);
  }

  static function get_country_name($country)
  {
    return false;
    global $DB;
    $DB->connect(GENERAL_DB_SLAVE,GENERAL_DB);
    $sql = "SELECT name FROM country_codes WHERE code='$country'";
    $name = $DB->query($sql);
    return $name[0]['name'];
  }

  static function get_country_code($country)
  {
    return false;
    $country = addslashes($country);
    global $DB;
    $DB->connect(GENERAL_DB_SLAVE,GENERAL_DB);
    $sql = "SELECT code FROM country_codes WHERE name='$country'";
    $name = $DB->query($sql);
    return $name[0]['code'];
  }

  static function get_sms_code($country)
  {
    return false;
    global $DB;
    $DB->connect(GENERAL_DB_SLAVE,GENERAL_DB);
    $sql = "SELECT calling_code FROM country_codes WHERE code='$country'";
    $name = $DB->query($sql);
    return $name[0]['calling_code'];
  }

  static function get_state_name($country,$region)
  {
    return false;
    global $DB;
    $DB->connect(GENERAL_DB_SLAVE,GENERAL_DB);
    $sql = "SELECT name FROM region_codes WHERE country='$country' AND region='$region'";
    $name = $DB->query($sql);
    return $name[0]['name'];
  }

  static function get_state_code($country,$region)
  {
    return false;
    $region = addslashes($region);
    global $DB;
    $DB->connect(GENERAL_DB_SLAVE,GENERAL_DB);
    $sql = "SELECT region FROM region_codes WHERE country='$country' AND name='$region'";
    $name = $DB->query($sql);
    return $name[0]['region'];
  }

  static function get_city_name($country,$region,$city)
  {
    return false;
    global $DB;
    $DB->connect(GENERAL_DB_SLAVE,GENERAL_DB);
    $sql = "SELECT city_name FROM cities WHERE country='$country' AND region='$region' AND city='$city'";
    $name = $DB->query($sql);
    return $name[0]['city_name'];
  }

  static function get_city_code($country,$region,$city)
  {
    return false;
    $city = addslashes($city);
    global $DB;
    $DB->connect(GENERAL_DB_SLAVE,GENERAL_DB);
    $sql = "SELECT city FROM cities WHERE country='$country' AND region='$region' AND city_name='$city'";
    $name = $DB->query($sql);
    return $name[0]['city'];
  }

  static function get_states($country_name)
  {
    return false;
    global $DB;
    $DB->connect(GENERAL_DB_SLAVE,GENERAL_DB);
    $country = Places::get_country_code($country_name);
    $sql = "SELECT * FROM region_codes WHERE country='$country' ORDER BY name";
    return $DB->query($sql);
  }
}

?>