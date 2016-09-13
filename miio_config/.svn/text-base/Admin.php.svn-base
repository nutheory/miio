<?
class Admin
{
	function queue_save($userid, $username, $first_name, $last_name, $image_url)
	{
	    global $Cache, $DB;
		$cache_queued = 'Queued_userlist';
	    $DB->connect(GENERAL_DB_MASTER,GENERAL_DB);
		$sql = "INSERT INTO users_featured (
			userid,
			username,
			image_url,
			first_name,
			last_name,
			queue_added
			) VALUES (
			$userid,
			'".addslashes($username)."',
			'".addslashes($image_url)."',
			'".addslashes($first_name)."',
			'".addslashes($last_name)."',
			NOW()
			)";
		$r = $DB->save($sql);
		$Cache->delete($cache_queued);
		return true;
	}
	
	function update_featured_status($id, $status)
	{
		global $Cache, $User;
	    if ($User->id==0) return false;
	    $conn = User::connectToShard($id,true);
	    $sta = addslashes($status);
	    $sql = "UPDATE users SET featured_status='$sta' WHERE id=$id";
	    $conn->rawquery($sql);
	    $cacheid = 'User_'.$id;
	    $Cache->delete($cacheid);
	    return true;
	}
	
	
	function queue_get()
	{  
		global $Cache, $DB;
		
		$cache_queued = 'Queued_userlist';
		$queued = $Cache->get($cache_queued);
		if ($queued)
		{
			return $queued;
		} 
	    $DB->connect(GENERAL_DB_SLAVE,GENERAL_DB);
		$sql = "SELECT * FROM users_featured WHERE featured_position IS NULL ORDER BY queue_added DESC";
		$queues = $DB->query($sql);
		
		$Cache->set('Queued_userlist', $queues);
		return $queues;
	}
	
	function feature_get()
	{
		global $Cache, $DB;
		
		$cache_featured = 'Featured_userlist';
		$featured = $Cache->get($cache_featured);
		if ($featured)
		{
			return $featured;
		} 
	    $DB->connect(GENERAL_DB_SLAVE,GENERAL_DB);
		$sql = "SELECT * FROM users_featured WHERE featured_position IS NOT NULL ORDER BY featured_position ASC";
		$features = $DB->query($sql);
		
		$Cache->set('Featured_userlist', $features);
		return $features;
	}
	
	function tagline_all()
	{
		global $Cache, $DB;
		
		$cache_taglines = 'Tagline_userlist';
		$taglinelist = $Cache->get($cache_taglines);
		if ($taglinelist)
		{
			return $taglinelist;
		} 
	    $DB->connect(GENERAL_DB_SLAVE,GENERAL_DB);
		$sql = "SELECT * FROM featured_taglines ORDER BY page_id ASC";
		$taglines = $DB->query($sql);
		
		$Cache->set('Tagline_userlist', $taglines);
		return $taglines;
	}
	
	function feature_save($order)
	{
		global $Cache, $DB;
		$cache_queued = 'Queued_userlist';
		$cache_featured = 'Featured_userlist';
		$DB->connect(GENERAL_DB_SLAVE,GENERAL_DB);
		$sql = "UPDATE users_featured SET featured_position = CASE userid ";
		foreach ($order as $position => $id) {
		    $sql .= sprintf("WHEN %d THEN %d ", $id, $position);
			$ids .= $id.",";
		}
		$ids = rtrim($ids, ",");
		$sql .= "END WHERE userid IN ($ids)";
		$features = $DB->rawquery($sql);
        $cleanup = "DELETE FROM users_featured WHERE featured_position > ".MAX_FEATURED;
		$clean = $DB->rawquery($cleanup);
		$Cache->delete($cache_featured);
		$Cache->delete($cache_queued);
		return true;
	}
	
	function feature_to_queue()
	{
		global $Cache, $DB;
		$cache_featured = 'Featured_userlist';
		$cache_queued = 'Queued_userlist';
		$DB->connect(GENERAL_DB_SLAVE,GENERAL_DB);
		$sql = "UPDATE users_featured SET featured_position = NULL";
		$features = $DB->rawquery($sql);
		$Cache->delete($cache_featured);
		$Cache->delete($cache_queued);
		return true;
	}
	
	function remove_queue_featured($id)
	{
		global $Cache, $DB;
		$cache_featured = 'Featured_userlist';
		$cache_queued = 'Queued_userlist';
		$DB->connect(GENERAL_DB_SLAVE,GENERAL_DB);
		$sql = "DELETE FROM users_featured WHERE userid=$id";
		$DB->rawquery($sql);
		$Cache->delete($cache_featured);
		$Cache->delete($cache_queued);
		return true;
	}
	
	function tagline_save($tagline, $type)
	{
		global $Cache, $DB;
		$cache_taglines = 'Featured_taglinelist';
	    $DB->connect(GENERAL_DB_MASTER,GENERAL_DB);
		$sql = "INSERT INTO featured_taglines (
			tagline,
			page_type,
			date_added
			) VALUES (
			'".addslashes($tagline)."',
			'".addslashes($type)."',
			NOW()
			)";
		$id = $DB->save($sql);
		$Cache->delete($cache_taglines);
		
		return $id;
	}
	
	function tagline_get($id) // use this to return saved tagline
	{
		global $DB;

		$DB->connect(GENERAL_DB_MASTER,GENERAL_DB);
		$sql = "SELECT * FROM featured_taglines WHERE id=$id";
		$tagline = $DB->query($sql);
		
		return $tagline;
	}
	
	function get_tagline($pageid) // use this to return page tagline
	{
		global $Cache, $DB;

		$DB->connect(GENERAL_DB_MASTER,GENERAL_DB);
		$sql = "SELECT * FROM featured_taglines WHERE page_id=$pageid";
		$tagline = $DB->query($sql);

		return $tagline;
	}
	
	function tagline_order($order)
	{
		global $Cache, $DB;

		$DB->connect(GENERAL_DB_SLAVE,GENERAL_DB);
		$sql = "UPDATE featured_taglines SET page_id = CASE id ";
		foreach ($order as $page => $id) {
			$page = $page+1;
		    $sql .= sprintf("WHEN %d THEN %d ", $id, $page);
			$ids .= $id.",";
		}
		$ids = rtrim($ids, ",");
		$sql .= "END WHERE id IN ($ids)";
		$tags = $DB->rawquery($sql);
		$Cache->delete('Tagline_userlist');
		return true;
	}
	
	function remove_tagline($id)
	{
		global $Cache, $DB;
		$cache_taglines = 'Tagline_userlist';
		$DB->connect(GENERAL_DB_SLAVE,GENERAL_DB);
		$sql = "DELETE FROM featured_taglines WHERE id=$id";
		$DB->rawquery($sql);
		$Cache->delete('Tagline_userlist');
		return true;
	}
}
?>