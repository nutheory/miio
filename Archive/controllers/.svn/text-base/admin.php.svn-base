<?

function queue_add()
{
	global $User, $Profile, $PARAMS;
	if (!$User->is_super)
	{
		Render('admin', 'restricted');
		return;
	}
	$Profile = User::get($PARAMS);
	if (!$Profile->id)
	{
		echo "Invalid ID!";
		return;	
	}
	$ok = Admin::queue_save($Profile->id, $Profile->username, $Profile->first_name, $Profile->last_name, $Profile->photo);
	$status = Admin::update_featured_status($Profile->id, 'queued');
	if ($ok && $status) echo 'ok';	
}

function featured()
{
	global $User, $QUEUE_LIST, $FEATURE_LIST, $TAGLINE_LIST;
	if (!$User->is_super)
	{
		Render('admin', 'restricted');
		return;
	}
	$QUEUE_LIST = Admin::queue_get();
	$FEATURE_LIST = User::featured_getall();
	Render('admin','featured');
}

function taglines()
{
	global $User, $QUEUE_LIST, $FEATURE_LIST, $TAGLINE_LIST;
	if (!$User->is_super)
	{
		Render('admin', 'restricted');
		return;
	}
	$TAGLINE_LIST = Admin::tagline_all();
	Render('admin','taglines');
}

function featured_add()
{
	global $User;
	if (!$User->is_super)
	{
		Render('admin', 'restricted');
		return;
	}
	$order = $_POST['queue'];
	$id = $_POST['id'];
	$ok = Admin::feature_save($order);
	$update = Admin::update_featured_status($id, 'featured');
	if($ok && $update) echo 'ok';
	else echo "failed to save";
}


function return_queue()
{
	global $User;
	if (!$User->is_super)
	{
		Render('admin', 'restricted');
		return;
	}
	$order = $_POST['queue'];
	$id = $_POST['id'];
	$btq = Admin::feature_to_queue($id);
	$feat = Admin::feature_save($order);
	$update = Admin::update_featured_status($id, 'queued');
	if($btq && $feat && $update) echo "ok";
	else echo "failed to update";
}

function remove_queue_featured()
{
	global $User;
	if (!$User->is_super)
	{
		Render('admin', 'restricted');
		return;
	}
	$order = $_POST['queue'];
	$id = $_POST['id'];
	$rqf = Admin::remove_queue_featured($id);
	$feat = Admin::feature_save($order);
	$update = Admin::update_featured_status($id, 'normal');
	if ($rqf && $feat && $update) echo $id;
}

function tagline_add()
{
	global $User, $TAGLINE;
	if (!$User->is_super)
	{
		Render('admin', 'restricted');
		return;
	}
	$tagline = $_POST['tagline'];
	$tag = Admin::tagline_save($tagline, 'users');
	if ($tag) $thistag = Admin::tagline_get($tag);
	if ($thistag) echo json_encode($thistag);
}

function get_tagline()
{
	global $User;
	if (!$User->is_super)
	{
		Render('admin', 'restricted');
		return;
	}

	$tagline = $_POST['id'];
	$tag = Admin::get_tagline($tagline);
	if ($tag) echo json_encode($tag);
	else echo false;
}

function tagline_order()
{
	global $User;
	if (!$User->is_super)
	{
		Render('admin', 'restricted');
		return;
	}
	$order = $_POST['tagline'];
	$tag = Admin::tagline_order($order);
	if ($tag) echo 'ok';
}

function remove_tagline()
{
	global $User;
	if (!$User->is_super)
	{
		Render('admin', 'restricted');
		return;
	}
	$id = $_POST['id'];
	$order = $_POST['tagline'];
	$tagid = Admin::remove_tagline($id);
	$tagorder = Admin::tagline_order($order);
	if ($tagid && $tagorder) echo $id;
	else echo 'fuck';
}

function suspend()
{
  global $User;
	if (!$User->is_super)
	{
		Render('admin', 'restricted');
		return;
	}
	$user = User::get($_POST['id']);
	if ($user->suspend()) echo "ok";
	else echo "Unknown error";
}

?>