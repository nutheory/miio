Group.EditAlbums = {};

Group.EditAlbums.Init = function()
{
  DOM.Hide('right_col');
  DOM.SetClass('content_div','content_no_rcol');
  DOM.Hide('message_form');
  DOM.Hide('message_filter_container');
  DOM.Hide('user_filter_container');
  DOM.Hide('show_timeline_filters');
  DOM.Hide('hide_timeline_filters');
  DOM.Hide('message_filters');
}

Group.EditAlbums.Count = function(e,obj,divid)
{
  function disallow()
  {
    var st = obj.scrollTop;
    obj.value = obj.value.substr(0,DESCRIPTIONLENGTH);
    obj.scrollTop = st;
  }
  
  if (window.event) key = window.event.keyCode;
  else key = e.which;
  
  var counter = document.getElementById(divid);
  if (counter)
  {
    if (obj.value.length > DESCRIPTIONLENGTH) disallow();
    else counter.innerHTML = DESCRIPTIONLENGTH-obj.value.length;
  }
}

Group.EditAlbums.Edit = function(id)
{
  function changeheader()
  {
    DOM.Show('edit_head_'+id);
    DOM.Hide('view_head_'+id);
    DOM.Open('edit_'+id);
  }
  
  if (id=='new')
  {
    DOM.Hide('openedit_new');
    DOM.Open('edit_new');
  }
  else
  {
    DOM.Close('album_grid_'+id,null,changeheader);
  }
  return false;
}

Group.EditAlbums.Cancel = function(id,newphotos)
{
  function openedit()
  {
    DOM.Show('openedit_'+id);
  }
  
  function changeheader()
  {
    DOM.Hide('edit_head_'+id);
    DOM.Show('view_head_'+id);
    DOM.Open('album_grid_'+id);
  }
  
  function openphotos()
  {
    DOM.Show('viewing_album_'+id);
    DOM.Hide('editing_album_'+id);
    DOM.Open('album_photos_'+id);
  }
  
  if (newphotos) DOM.Close('new_photos_'+id,null,openphotos);
  else if (id=='new') DOM.Close('edit_new',null,openedit);
  else DOM.Close('edit_'+id,null,changeheader);
  return false;
}

Group.EditAlbums.ToggleAlbum = function(id,show)
{
  function done()
  {
    if (show)
    {
      DOM.Show('hide_album_'+id);
      DOM.Hide('show_album_'+id);
    }
    else
    {
      DOM.Show('show_album_'+id);
      DOM.Hide('hide_album_'+id);
      DOM.SetClass('albumcontainer_'+id,'album');
    }
  }
  
  if (show)
  {
    DOM.Open('album_grid_'+id,null,done);
  }
  else DOM.Close('album_grid_'+id,null,done);
  return false;
}

Group.EditAlbums.Add = function(id)
{
  function openform()
  {
    DOM.Hide('viewing_album_'+id);
    DOM.Show('editing_album_'+id);
    DOM.Open('new_photos_'+id);
  }
  
  DOM.Close('album_photos_'+id,null,openform)
  return false;
}

Group.EditAlbums.Enable = function(file,album,photo)
{
  if (file.value != "")
  {
    var nextinput = document.getElementById('image_'+album+'_'+photo);
    if (nextinput) nextinput.disabled = false;
    file.disabled = true;
  }
}

Group.EditAlbums.FormSubmit = function(album)
{ 
  var albumtitle = document.getElementById('title_'+album);
  if (albumtitle)
  {
    if (albumtitle.value.trim()=="")
    {
      alert("Please enter a title for this album.");
      return false;
    }
    if (album=='new' && document.getElementById('image_'+album+'_1').value == "")
    {
      alert("Please select at least one photo to upload to this album.");
      return false;
    }
    for (var p=1;p<10;p++)
    {
      var file = document.getElementById('image_'+album+'_'+p);
      if (file) file.disabled = false;
    }
    DOM.Show('user_loading');
    return true;
  }
  else
  {
    alert('Unable to read form');
    return false;
  }
}

Group.EditAlbums.UploadDone = function(response)
{
  DOM.Hide('user_loading');
  var resp = response.jsonParse();
  if (resp.errors.length>0)
  {
    var str = "There were errors uploading your pictures:\n\n";
    for (var e=0;e<resp.errors.length;e++)
    {
      str += resp.errors[e] + "\n";
    }
    alert(str);
  }
  //groups/editalbums/groupid?id="+resp.id;
  var page = new Ajax(Group.GetResults);
  var url = "groups/editalbums/"+Group.ID+"?id="+resp.id;
  var params = "isajax=1";
  page.sendPostRequest(url,params);
  /*
  DOM.Hide('user_loading');
  var content = document.getElementById('user_content');
  if (content) content.innerHTML = response;
  
  
  DOM.Show('form_response');
  DOM.Hide('group_album_form');
  scrollTo(0,0);
  */
}

Group.EditAlbums.UploadError = function(err)
{
  DOM.Hide('user_loading');
  if (err=='too_many') alert("You have exceeded the number of available albums. You must first delete one if you wish to create a new one.");
  else if (err=='bad_id') alert("The album id is invalid.");
  else alert ("Error:\n\n"+err);
}

Group.EditAlbums.DeletePhoto = function(album,photo,phototitle,pid)
{
  function ret(response)
  {
    if (response=='ok')
    {
      try
      {
        var p = pid.parentNode.id.split('_');
        var idx = p[2];
        var max_i = 0;
        if (idx<10)
        {
          for (var ix=idx;ix<10;ix++)
          {
            var i0 = album+'_'+ix;
            var i1 = album+'_'+(parseInt(ix)+1);
            if (DOM.GetValue('i_'+i1)>0)
            {
              DOM.SetHTML('t_'+i0,DOM.GetHTML('t_'+i1));
              DOM.SetHTML('p_'+i0,DOM.GetHTML('p_'+i1));
              DOM.SetHTML('d_'+i0,DOM.GetHTML('d_'+i1));
              DOM.SetValue('i_'+i0,DOM.GetValue('i_'+i1));
              max_i = ix;
              document.getElementById('pic_'+i0).src = document.getElementById('pic_'+i1).src;
              document.getElementById('image_'+i0).disabled = true;
            }
            else
            {
              DOM.SetHTML('t_'+i0,"&nbsp;");
              DOM.SetHTML('p_'+i0,ix);
              DOM.SetHTML('d_'+i0,"");
              DOM.SetValue('i_'+i0,0);
              DOM.Hide('pic_'+i0);
              DOM.Show('image_'+i0);
              document.getElementById('image_'+i0).disabled = true;
            }
          }
          if (DOM.GetValue('i_'+album+'_'+idx)<1) var imginput = idx;
          else var imginput = parseInt(max_i) + 1;
          document.getElementById('image_'+album+'_'+(imginput)).disabled = false;
          imginput++;
          if (imginput < 10) document.getElementById('image_'+album+'_'+imginput).disabled = true;
          DOM.Show('add_photo_link_'+album);
        }
      }
      catch(e)
      {
        
      }
    }
    else
    {
      alert('Error: '+response);
    }
  }
  
  if (confirm("Really delete '"+phototitle+"' from the album?"))
  {
    var del = new Ajax(ret);
    var url = "groups/delete_photo/"+Group.ID;
    var params = "isajax=1";
    params += "&album="+album;
    params += "&photo="+photo;
    del.sendPostRequest(url,params);
  }
  return false;
}

Group.EditAlbums.Update = function(album)
{
  function ret(response)
  {
    DOM.Hide('user_loading');
    if (response.substr(0,2)=='ok')
    {
      DOM.Hide('group_album_form');
      DOM.Show('form_response');
    }
    else
    {
      alert('Error: '+response);
    }
  }
  
  var albumtitle = document.getElementById('title_'+album);
  if (albumtitle)
  {
    if (albumtitle.value.trim()=="")
    {
      alert("Please enter a title for this album.");
      return false;
    }
    var update = new Ajax(ret);
    var url = "groups/update_album/"+Group.ID;
    var params = "isajax=1";
    params += "&album_id="+album;
    params += "&title="+albumtitle.value.trim();
    params += "&description="+DOM.GetValue('description_'+album);
    
    DOM.Show('user_loading');
    update.sendPostRequest(url,params);
  }
  else
  {
    alert('Unable to read form');
  }
  return false;
}

Group.EditAlbums.DeleteAlbum = function(album,albumtitle)
{
  function ret(response)
  {
    if (response=="ok")
    {
      DOM.Hide('group_album_form');
      DOM.Show('form_response');
    }
    else
    {
      alert("An unknown error occurred:\n\n"+response);
    }
  }
  
  if (confirm("Really delete '"+albumtitle+"' and all its photos?"))
  {
    var del = new Ajax(ret);
    var url = "groups/delete_album/"+Group.ID;
    var params = "isajax=1";
    params += "&album_id="+album;
    del.sendPostRequest(url,params);
  }
  return false;
}

Group.EditAlbums.ReturnToAlbums = function()
{
  DOM.Show('user_loading');
  location.reload();
  return false;
}
