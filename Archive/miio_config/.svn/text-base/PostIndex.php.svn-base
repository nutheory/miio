<?

class PostIndex
{
  static function save($post)
  {
    $exclude = array(
      'and','but','etc','for','from','him','his','her',
      'she','the','that','this','yes','you',
      'http','https','com','net','org','bit'
    );
    
    global $DB, $Cache;
    $DB->connect(GENERAL_DB_MASTER,GENERAL_DB);
    // get words from posts
    $txt = preg_replace("/[^A-Za-z0-9_]/"," ",$post['text']);
    $words = explode(" ",$txt);
    
    foreach ($words as $rawword)
    {
      $rawword = trim(strtolower($rawword));
      if (strlen($rawword)>1 && !in_array($rawword,$exclude))
      {
        $word = addslashes($rawword);
        $sql = "SELECT word_id FROM word WHERE word='$word'";
        $w = $DB->query($sql);
        if (!$w)
        {
          // word does not exist
          $sql = "INSERT INTO word (word) VALUES ('$word')";
          $id = $DB->save($sql);
        }
        else $id = $w[0]['word_id'];
        
        // save to DB
        $sql = "INSERT INTO word_posts (word_id,post_id,created_at) VALUES ($id,".$post['id'].",'".$post['created_at']."')";
        $DB->rawquery($sql);
        
        // update cache
        $wordobj = array('post_id'=>$post['id'],'created_at'=>$post['created_at']);
        $cacheid = 'w_'.$rawword;
        $cached_word = $Cache->get($cacheid);
        if (!$cached_word)
        {
          $cached_word = array($wordobj);
          $Cache->add($cacheid,$cached_word);
        }
        else
        {
          $cached_word[] = $wordobj;
          $Cache->replace($cacheid,$cached_word);
        }
      }
    }
    
    //tags
    foreach ($post['tags'] as $tag)
    {
      $sql = "INSERT INTO tag_posts (tag_id,post_id,created_at) VALUES ($tag,".$post['id'].",'".$post['created_at']."')";
      $DB->rawquery($sql);
      $sql = "SELECT text FROM tags WHERE id=$tag";
      $t = $DB->query($sql);
      $ttext = $t[0]['text'];
      // update cache
      $tagobj = array('post_id'=>$post['id'],'created_at'=>$post['created_at']);
      $cacheid = 't_'.$ttext;
      $cached_tag = $Cache->get($cacheid);
      if (!$cached_tag)
      {
        $cached_tag = array($tagobj);
        $Cache->add($cacheid,$cached_tag);
      }
      else
      {
        $cached_tag[] = $tagobj;
        $Cache->replace($cacheid,$cached_tag);
      }
    }
  }
  
  static function wordcount($word,$start=0,$end=0)
  {
    global $Cache;
    $word = trim(strtolower($word));
    if ($end==0) $end = time()+1;
    $count = 0;
    $PostIndex = $Cache->get('PostIndex');
    if ($PostIndex[$word])
    {
      foreach ($PostIndex[$word] as $id=>$date)
      {
        if ($start<$date && $date<$end) $count++;
      }
    }
    return $count;
  }
  
  static function topWords($howmany,$start=0,$end=0)
  {
    global $Cache;
    $wordcount = array();
    if ($end==0) $end = time()+1;
    $PostIndex = $Cache->get('PostIndex');
    foreach($PostIndex as $word=>$list)
    {
      $count = 0;
      foreach ($list as $id=>$date)
      {
        if ($start<$date && $date<$end) $count++;
      }
      if ($count>0) $wordcount[$word] = $count;
    }
    arsort($wordcount);
    return array_slice($wordcount,0,$howmany);
  }
  
  static function findWord($word)
  {
    global $DB,$Cache;
    /*
    $cacheid = 'w_'.$word;
    // check cache
    $wd = $Cache->get($cacheid);
    if ($wd) return $wd;
    */
    // check database
    $w = addslashes($word);
    $DB->connect(GENERAL_DB_MASTER,GENERAL_DB);
    $sql = "SELECT word_id FROM word WHERE word='$w'";
    $id = $DB->query($sql);
    if (!$id) return false;
    
    // get list
    $sql = "SELECT post_id,created_at FROM word_posts WHERE word_id=".$id[0]['word_id'];
    $list = $DB->query($sql);
    $Cache->add($cacheid,$list);
    return $list;
  }
  
  static function findTag($tag)
  {
    global $DB,$Cache;
    /*
    $cacheid = 't_'.$tag;
    // check cache
    $wd = $Cache->get($cacheid);
    if ($wd) return $wd;
    */
    //echo"not in cache";
    // check database
    $w = addslashes($tag);
    $sql = "SELECT id FROM tags WHERE text='$w'";
    $id = $DB->query($sql);
    if (!$id) return false;
    
    // get list
    $sql = "SELECT post_id,created_at FROM tag_posts WHERE tag_id=".$id[0]['id'];
    $list = $DB->query($sql);
    $Cache->add($cacheid,$list);
    return $list;
  }
  
  static function find($word)
  {   
    $w = PostIndex::findWord($word);
    $t = PostIndex::findTag($word);
    return array('words'=>$w, 'tags'=>$t);
  }
  
  static function cacheAll()
  {
    global $DB, $Cache;
    $sql = "SELECT * FROM word";
    $allwords = $DB->query($sql);
    foreach ($allwords as $key=>$word)
    {
      $cacheid = 'w_'.$word['word'];
      $sql = "SELECT post_id,created_at FROM word_posts WHERE word_id=".$word['word_id'];
      $list = $DB->query($sql);
      if (count($list)>0)
      {
        $Cache->delete($cacheid);
        $Cache->add($cacheid,$list);
      }
    }
    $sql = "SELECT * FROM tags";
    $alltags = $DB->query($sql);
    foreach ($alltags as $key=>$tag)
    {
      $cacheid = 't_'.$tag['text'];
      $sql = "SELECT post_id,created_at FROM tag_posts WHERE tag_id=".$tag['id'];
      $list = $DB->query($sql);
      if (count($list)>0)
      {
        $Cache->delete($cacheid);
        $Cache->add($cacheid,$list);
      }
    }
  }
  
  static function indexAll()
  {
    global $DB, $Cache;
    // clear cache
    $sql = "SELECT DISTINCT word FROM word";
    $res = $DB->query($sql);
    foreach($res as $word)
    {
      $Cache->delete('w_'.$word['word']);
    }
    $sql = "SELECT DISTINCT text FROM tags";
    $res = $DB->query($sql);
    foreach($res as $tag)
    {
      $Cache->delete('t_'.$tag['text']);
    }
    // clear tables
    $sql = "TRUNCATE TABLE word_posts";
    $DB->rawquery($sql);
    $sql = "TRUNCATE TABLE word";
    $DB->rawquery($sql);
    $sql = "TRUNCATE TABLE tag_posts";
    $DB->rawquery($sql);
    echo "Rebuilding index...<br><br>"; flush();
    
    $sql = "SELECT id FROM posts WHERE 1=1";
    $list = $DB->query($sql);
    $counter = 0;
    foreach ($list as $p)
    {
      $post = Post::get($p['id']);
      if (!$post) continue;
      if
      (
        !$post['system'] && !$post['alert'] && $post['sharing']=='public' &&
        (
          $post['type']='text' || $post['type']='rss' || $post['type']='twitter' ||
          $post['type']='review' || $post['type']='question' || $post['type']='link' ||
          $post['type']='photo' || $post['type']='video' || $post['type']='location'
        )
      )
      {
        PostIndex::save($post);
        $counter++;
        if ($counter==1000)
        {
          echo $post['id'].".. "; flush(); $counter=0;
        }
      }
    }
  }
}