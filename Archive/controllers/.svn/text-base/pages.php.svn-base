<?
// pages controller
/*#####*/
function contact()
{/*#####*/
  global $FORM_ERR, $FORM_VALUES, $SUB;
  if (isset($_POST['submit']))
  {
    $email = trim($_POST['email']);
    $name = $_POST['name'];
    $username = trim($_POST['username']);
    $category = trim($_POST['category']);
    $subject = trim($_POST['subject']);

    $FORM_VALUES = $_POST;
    if ($email=='') $FORM_ERR = "no_email";
    else if (!Validate::email($email)) $FORM_ERR = "email_invalid";

    if (!$FORM_ERR)
    {
      if (Validate::username($username))
      {
        $usr = User::getByName($username);
        if (!$usr) $err = "Username is not a Miio user";
        else
        {
          if ($usr->is_group) $err = "Username is for a Miio Group";
          else if ($usr->email != $email) $err = "Username does not match email provided";
        }
      }
      else
      {
        $err = "Username is not a valid Miio format username";
      }
      $msg = "From: $name ($email)\nMiio username: $username";
      if ($err) $msg .= " ($err)";
      $msg .= "\nCategory: $category\nSuject: $subject\n\n$message";
      $headers = "From: Miio Contact Form <miio@miio.com>";
      $subj = "Contact form message";
      mail("r@miio.com,t@miio.com",$subj,$msg,$headers);
      //mail("richardlusk@gmail.com,random@patternsofchaos.net",$subj,$msg,$headers);
      $SUB = true;
    }
  }
  Render ('pages','contact');
}


function dmca()
{/*#####*/
  global $FORM_ERR, $SUB, $FORM_VALUES;
  if (isset($_POST['submit']))
  {
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $identify_copyrighted = trim($_POST['identify_copyrighted']);
    $identify_infringing = trim($_POST['identify_infringing']);
    $locate_infringing = trim($_POST['locate_infringing']);
    $how_does = trim($_POST['how_does']);
    $agree = trim($_POST['agree']);
    $signature = trim($_POST['signature']);

    $FORM_VALUES = $_POST;
    $FORM_ERR = array();

    if ($email=='') $FORM_ERR['no_email']=true;
    else if (!Validate::email($email)) $FORM_ERR['email_invalid']=true;
    if ($name=='') $FORM_ERR['name']=true;
    if ($address=='') $FORM_ERR['address']=true;
    if ($phone=='') $FORM_ERR['phone']=true;
    if ($identify_copyrighted=='') $FORM_ERR['id_copy']=true;
    if ($identify_infringing=='') $FORM_ERR['id_infringe']=true;
    if ($locate_infringing=='') $FORM_ERR['locate']=true;
    if ($signature=='' || $agree!='yes') $FORM_ERR['agree']=true;
    if (count($FORM_ERR)<1)
    {
      $msg = "Name: $name\nAddress: $address\nTelephone: $phone\nEmail: $email\n\n";
      $msg .= "Copyrighted work: $identify_copyrighted\n\n";
      $msg .= "Alleged infringer: $identify_infringing\n\n";
      $msg .= "Location: $locate_infringing\n\n";
      $msg .= "E-signature: $signature\n\n";
      $headers = "From: Miio DMCA Form <miio@miio.com>";
      $subj = "DMCA Notice";
      mail("r@miio.com,t@miio.com",$subj,$msg,$headers);
      //mail("richardlusk@gmail.com,random@patternsofchaos.net",$subj,$msg,$headers);
      $SUB = true;
    }
  }
  Render ('pages','dmca');
}

?>