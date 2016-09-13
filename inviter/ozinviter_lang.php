<?
/********************************************************************************
Unified Inviter Component
Language file (English)

You may not reprint or redistribute this code without permission from Octazen Solutions.

Copyright 2009 Octazen Solutions. All Rights Reserved.
WWW: http://www.octazen.com
********************************************************************************/

//Content here should be in utf-8 encoded form.
//If it's not in utf-8 encoded form, please specify charset.lang property in ozinviter_config.php

global $_OZINVITER_LANG;
$_OZINVITER_LANG = array(

	'SUBMIT' => 'Submit',
	'CANCEL' => 'Cancel',
	'START_AGAIN' => 'Start again',
	'CLICK_HERE' => 'Click here',
	'UPLOAD_FILE' => 'Upload Address Book',
	'MANUAL_INVITE' => 'Manual Invite',
	'SHARE_AS_LINK' => 'Share Invite Link',
	'PLEASEWAIT' => 'Please wait. This may take a while...',
	'SUBJECT' => 'Subject',
	'MESSAGE' => 'Message',
	'PERSONAL_MESSAGE' => 'Personal Message (optional)',
	'INVALID_EMAIL' => 'Please enter a valid email address',
	
	'YOUR_NAME' => 'Your name',
	'YOUR_EMAIL' => 'Your email',
	
	'ERROR_AUTH' => 'Wrong user name or password',
	'ERROR_CAPTCHA' => 'Captcha Challenge Needs to be answered',
	'ERROR_CAPTCHA_PRESENTED' => 'Captcha Challenge was presented.', //Unanswerable captcha
	'ERROR_200' => 'Success',
	'ERROR_400' => 'Operation failed',
	'ERROR_601' => 'You must be logged in first',
	'ERROR_602' => 'Error communicating with server',
	'ERROR_603' => 'Wrong user name or password',
	'ERROR_604' => 'Catcha was raised',
	'ERROR_606' => 'Some user input required. Cannot login.',
	'ERROR_607' => 'Unsupported webmail/social network.',
	'ERROR_608' => 'Unsupported operation',
	'ERROR_609' => 'Invalid file format',

	'ANY_WEBMAIL' => 'Any Webmail (Autodetect)',
	
//	'SELECTOR_AB' => 'Select where to import contacts from',
//	'SELECTOR_SN' => 'Or a social network where your friends hang out',
	'SELECTOR_AB' => 'Address Books',
	'SELECTOR_SN' => 'Social Networks',

	'SELECTOR_SHOW_ALL' => 'Show All',
	'SELECTOR_HIDE' => 'Hide',
	'SELECTOR_ANY' => '... or <b>Any Webmail</b> (autodetect)',
	
	'LOGIN_TITLE' => 'Login',
	'LOGIN_NETWORK' => 'Address Book',
	'LOGIN_INSTRUCTIONS' => '(We DO NOT store your login details)',
	'LOGIN_EMAIL' => 'Email',
	'LOGIN_ID' => 'Login ID',
	'LOGIN_PASSWORD' => 'Password',
	'LOGIN_FETCH_CONTACTS' => 'Fetch contacts',
	'LOGIN_HOTMAIL_INSTRUCTIONS' => 'To import contacts from Hotmail, you will first need to login to Windows Live. Click the button below to begin.',
	'LOGIN_HOTMAIL_BUTTON' => 'Login to Windows Live',
	'LOGIN_GMAIL_INSTRUCTIONS' => 'To import contacts from Gmail, you will first need to login to Gmail.<br/>Click the button below to begin.',
	'LOGIN_GMAIL_BUTTON' => 'Login to GMail',
	'LOGIN_GOOGLEAPPS_INSTRUCTIONS' => '<b>Google Apps user?</b><br/>Enter your Google Apps domain and click the button below to begin.',
	'LOGIN_GOOGLEAPPS_DOMAIN' => 'Google Domain',
	'LOGIN_GOOGLEAPPS_DOMAIN_EXAMPLE' => '(e.g. mydomain.com)',
	'LOGIN_GOOGLEAPPS_BUTTON' => 'Login to Google Apps',
	
	'DROPDOWN_COMMON_WEBMAILS' => 'Common Webmails',
	'DROPDOWN_OTHER_WEBMAILS' => 'Other Webmails',
	'DROPDOWN_SOCIAL_NETWORKS' => 'Social Networks',
	
	
	'CONTACTS_TITLE' => 'Select email contacts to invite',
	'CONTACTS_NO_CONTACTS' => 'You do not have any contacts to invite',
	'CONTACTS_SELECT_ALL_NONE' => 'Select All / None',
	'CONTACTS_SEND_AND_REPEAT' => 'Send invite and invite more',
	'CONTACTS_SEND_INVITATION' => 'Send invitations',
	'CONTACTS_SEND_INVITATION_APP' => 'Send invitations',
	'CONTACTS_SELECT_UP_TO' => 'Select up to %count% more friends',
	'CONTACTS_MAXIMUM_SELECTED' => 'Maximum contacts selected',

	'MEMBERS_TITLE' => 'Some of your contacts are already members!',
	'MEMBERS_SELECT_ALL_NONE' => 'Select All / None',
	'MEMBERS_ADD_AS_FRIEND' => 'Add as Friends',
	'MEMBERS_SKIP' => 'Skip &raquo;',

	
	'FINISHED_TITLE' => 'Invitations Sent!',
	'FINISHED_SENT_TO' => 'Invitation sent to the following friends : ',
	'FINISHED_SENT' => 'Invitations sent!',
	'FINISHED_HAVE_MORE_FRIENDS' => '',
	'FINISHED_INVITE_THEM' => 'Invite more friends!',
	
	'MANUAL_TITLE' => 'Manual Invite',
	'MANUAL_INSTRUCTIONS' => 'Type in your friends\' email addresses',
	'MANUAL_INSTRUCTIONS2' => 'Type in your friends\' email addresses, separated by comma',
	'MANUAL_ADD_ANOTHER' => '+ Add another email',
	'MANUAL_SEND_INVITATION' => 'Send Invitation',
	'MANUAL_EMAIL' => 'Email',
	'MANUAL_EMAILS' => 'Emails',
	
	'UPLOAD_TITLE' => 'Upload CSV/LDIF Address Book',
	'UPLOAD_FASTIMPORT' => 'Fast import from Outlook, Outlook Express, Windows Mail',
	'UPLOAD_LAUNCH_DESKTOP_IMPORTER' => 'Launch the Contacts Importer',
	'UPLOAD_NOT_WORKING' => 'Not working?',
	'UPLOAD_TRY_DOWNLOADING' => 'Try downloading the Contacts Importer.',
	'UPLOAD_DOWNLOAD_AND_RUN' => 'Download and run Contacts Importer',
	'UPLOAD_PASTE_HERE' => 'Right-click HERE and select "Paste" to submit the results',
	'UPLOAD_STEP1' => 'Step 1',
	'UPLOAD_STEP2' => 'Step 2',
	'UPLOAD_OR' => 'or',
	'UPLOAD_SELECT_FORMAT' => 'Select Address Book Format you wish to upload',
	'UPLOAD_SEE_INSTRUCTIONS' => 'See Instructions',
	'UPLOAD_OUTLOOK_CSV' => 'Outlook (CSV)',
	'UPLOAD_OUTLOOKEXPRESS_CSV' => 'Outlook Express (CSV)',
	'UPLOAD_OUTLOOK_CSV_INSTRUCTIONS' => '<ol><li>Launch Outlook</li><li>Switch to Contact view</li><li>Go to File menu, select &quot;Import/Export&quot;. Import and Export Wizard window appears.</li><li>Select &quot;Export to a file&quot;. Click Next.</li><li>Select &quot;Comma Separated Values&quot; (DOS or Windows). Click Next. </li><li>Select where to save the CSV file. Click Next.</li><li>Click Finish.</li></ol>',
	'UPLOAD_OUTLOOKEXPRESS_CSV_INSTRUCTIONS' => '<ol><li>Launch Outlook Express</li><li>Go to File &raquo; Export &raquo; Address Book menu. Address book export tool appears. </li><li>Select &quot;Text File (Comma Separated Values)&quot;. Click Export.</li><li>Select where to save the CSV file. Click Next.</li><li>Click Finish </li><div>* Note: Only CSV files with English header is supported for now</div></ol>',
	'UPLOAD_WINDOWSCONTACTS_CSV' => 'Windows Contacts (CSV)',
	'UPLOAD_WINDOWSCONTACTS_CSV_INSTRUCTIONS' => '<ol><li>Launch Windows Mail</li><li>Go to File &raquo; Export &raquo; Windows Contacts menu. </li><li>Select &quot;CSV (Comma Separated Values)&quot;. Click Export.</li><li>Select where to save the CSV file. Click Next.</li><li>Click Finish </li><div>* Note: Only CSV files with English header is supported for now</div></ol>',
	'UPLOAD_THUNDERBIRD_LDIF' => 'Mozilla Thunderbird (LDIF)',
	'UPLOAD_THUNDERBIRD_LDIF_INSTRUCTIONS' => '<ol><li>Launch Mozilla Thunderbird</li><li>Go to Tools &raquo; Address Book menu. Address book window appears.</li><li>Go to Tools &raquo; Export menu.</li><li>Select path to save file. LDIF is selected by default. Click save.</li></ol>',
	'UPLOAD_VCF' => 'Mac OS Address Book (or any vCard)',
	'UPLOAD_VCF_INSTRUCTIONS' => '<ol><li>Open Mac OS X Address Book</li><li>Select the Group named "All" and select all your contacts</li><li>From the "File" menu, select "Export" &raquo; "Export vCard" and save.</li><li>Select the saved file for upload.</li></ol>',
	'UPLOAD_SELECT_FILE' => 'Then select the file to be uploaded: ',
	'UPLOAD_UPLOAD' => 'Upload',
		  	
	'CAPTCHA_TITLE' => 'Captcha Challenge',
	'CAPTCHA_PLEASETYPE' => 'Please type the word that you see above to continue',
	'CAPTCHA_REMAINING' => 'Message(s) remaining to be sent : ',
	
	'BOOKMARK_TITLE' => 'Share Invite Link',
	'BOOKMARK_SELECT' => 'Select where to share the invite link...',
	'BOOKMARK_SENDTHISLINK' => 'Or send this link to your friends',
	
	'SHARE_FACEBOOK_INSTRUCTIONS' => 'Send an invitation to your friends on Facebook!',
	'SHARE_LINK' => 'Login to Facebook',
	
	'FACEBOOK_CONNECT_INSTRUCTIONS'=> 'Invite your Facebook friends! Click the button below to start ...',
	'FACEBOOK_CONNECT_ACTION_TEXT' => 'Invite specific friends to %appname%',
	'FACEBOOK_CONNECT_INVITE_BUTTON' => 'Go to %appname%',
	'FACEBOOK_CONNECT_CONNECTED_AS' => 'You\'re connected to Facebook as',
	'FACEBOOK_CONNECT_LOGOUT' => 'Logout from Facebook.',
	'FACEBOOK_CONNECT_SHARE_INSTRUCTIONS' => 'Or post this website link on your Facebook profile or send a message to your Facebook friends!',
	'FACEBOOK_CONNECT_YOU_CAN' => 'You can ',
	'FACEBOOK_CONNECT_PUBLISH' => 'Publish invites on your Wall',
	'FACEBOOK_CONNECT_OR' => ' or ...',
	'FACEBOOK_CONNECT_PUBLISH_TEXT' => 'You\'re invited to %appname% ! Go to %url%',
	
	''=>''
);

?>