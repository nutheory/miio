<?
/********************************************************************************
Unified Inviter Component
Default Configuration File

You may not reprint or redistribute this code without permission from Octazen Solutions.

Copyright 2009 Octazen Solutions. All Rights Reserved.
WWW: http://www.octazen.com
********************************************************************************/

global $_OZ_CONFIG;
$_OZ_CONFIG = array(

	//0=Icon selector
	//1=Jump straight to login form
	//2=Login form with icon selector at the bottom
	'selector_mode' => 0,
	
	'selector_group_icons' => FALSE,
	
	//Limit the number of icons to show by default for address book and social network.
	//A "Show All" or "Hide" link will be shown if there are more icons.
	'selector_ab_max_icons' => 100,
	'selector_sn_max_icons' => 100,
	
	//Select all contacts by default?
	'select_all_contacts' => TRUE,

	//true to ask user for their name/email, false otherwise. from_name and from_email is the default value to use if your_name and your_email is set to TRUE.
	'your_name' => FALSE,
	'your_email' => FALSE,
	'from_name' => '',
	'from_email' => '',
	
	//Use NULL/blank string for default mod implementation (only for mod/plugins)
	'mailer' => NULL,
	'invite_url' => NULL,
	'subject' => NULL,
	'text_body' => NULL,
	'html_body' => NULL,
	'fbml_body' => NULL,
	'text_message' => NULL,

	//True to allow personalized message, false otherwise
	'allow_personal_message' => TRUE,
	
	//Default personal message	
	'default_personal_message' => '',
	
	//True to prefer browser based authentication (Google AuthSub, Hotmai Windows Live Login, etc)
	'prefer_webauth' => TRUE,

	//True to enable manual invite
	'allow_manual_invite' => FALSE,

	//True to enable CSV/LDIF upload 
	'allow_upload' => TRUE,

	//True to enable link sharing through social bookmarks
	'allow_bookmark' => TRUE,
	
	//Desktop Importer presence: 0=No, 1=Use ActiveX if available, 2=Use Desktop EXE always
	'desktopimporter_present' => 0,
	
	//Your Desktop contacts importer decryption key
	'desktopimporter_decrypt_key' => "xxxxx",
	
	//Your Desktop contacts importer config string
	'desktopimporter_config_string' => "xxxxx",

	//Show/Hide Octazen branding
	'show_branding'=>FALSE,
	
	//Show/Hide Addressbook / Social network section
	'show_abi'=>TRUE,
	'show_sn'=>TRUE,
	
	//Output character set encoding of oz_render_inviter().
	'charset' => 'UTF-8',
	
	//Character set of entries in language file (ozinviter_lang.php)
	'charset.lang' => 'UTF-8',
	
	//Path to tmp directory where state is stored. If blank, defaults to '/tmp'.
	//Inviter creates a subdiretory named 'ozstate' within this tmp directory
	'tmpdir'=>'',

	//Maximum contacts and pages of contacts to fetch	
	'limit.max_contacts' => 10000,
	'limit.max_pages' => 30,
	'limit.max_selectable' => -1,
	'limit.max_selectable.is' => 50,
	
	//Optional limit override for each social network. Defaults below recommended based on batch size of social network
	//'limit.max_selectable.is_friendster' => 50,
	//'limit.max_selectable.is_hi5' => 150,
	//'limit.max_selectable.is_hyves' => 200,
	//'limit.max_selectable.is_meinvz' => 100,
	//'limit.max_selectable.is_myspace' => 200,
	//'limit.max_selectable.is_orkut' => 10,
	//'limit.max_selectable.is_twitter' => 50,
	//'limit.max_selectable.is_xing' => 20,
	
	//True to use individual textbox for manual invite, false to use 1 large textarea, with emails
	//separated by comma/semicolon/space
	'manual_multiple_textbox' => TRUE,
	
	//FBConnect API Key
	'facebook_connect.api_key' => NULL,
	'facebook_connect.receiver_path' => NULL,
	
	//Stats
	'stats.enabled' => FALSE,		//Set to TRUE to enable stats 
	'stats.login' => 'admin',			//Set your login here
	'stats.password' => '123456',		//Set your login password here
	'stats.dbfile' => NULL,			//Optional path to DB file. Defaults to ./inviter/tmp/stats.sqlite
	'stats.track_links' => TRUE,	//True to enable links to be redirected for tracking
	
);

?>