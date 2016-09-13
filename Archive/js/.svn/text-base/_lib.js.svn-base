// PROTOTYPE ENHANCEMENTS

String.prototype.ltrim = function()
{
  var LTrimRegEx = /^[ \n\r\t]+/;
  return this.replace(LTrimRegEx, "");
}

String.prototype.rtrim = function()
{
  var RTrimRegEx = /[ \n\r\t]+$/;
  return this.replace(RTrimRegEx, "");
}

String.prototype.trim = function()
{
  var myString = this.rtrim();
  myString = myString.ltrim();
  return myString;
}

String.prototype.jsonParse = function()
{
  try { return eval('(' + this + ')'); }
  catch(e) { return false; }
}

String.prototype.isNumeric = function()
{
  var nums = "0123456789.";
  for (var i=0; i<this.length; i++)
  {
    var ch = this.charAt(i);
    if (nums.indexOf(ch) == -1)
    {
      return false;
    }
  }
  return true;
}

String.prototype.isURL = function()
{
  return this.match(/^(ftp|http|https):\/\/([_a-zA-Z0-9-]+)(\.[_a-zA-Z0-9-]+)*(\.[_a-zA-Z0-9-]{2,4})/);
}

String.prototype.ucFirst = function()
{
  return this.substr(0,1).toUpperCase()+this.substr(1)
}

String.prototype.lcFirst = function()
{
  return this.substr(0,1).toLowerCase()+this.substr(1)
}

/******************************************************************************/
// MISC FUNCTIONS

function isIE()
{
  if (window.ActiveXObject) return true;
  else return false;
}

function InArray(arr,val)
{
  var l = arr.length;
  for (var i=0;i<l;i++)
  {
    if (arr[i]==val) return true;
  }
  return false;
}

function isset(obj)
{
  return (typeof(obj)!='undefined');
}

/******************************************************************************/
// DOM LIBRARY FUNCTIONS

var DOM = {};

DOM.slideSpeed = 40;

DOM.PosX = function(obj)
{
  var curleft = 0;
  if (obj)
  {
    if (obj.offsetParent)
    {
      while (obj.offsetParent)
      {
        curleft += obj.offsetLeft;
        obj = obj.offsetParent;
      }
    }
    else if (obj.x)
    {
      curleft += obj.x;
    }
  }
  return curleft; // adjustment for proper display
}

DOM.PosY = function(obj)
{
  var curtop = 0;
  if (obj)
  {
    if (obj.offsetParent)
    {
      while (obj.offsetParent)
      {
        curtop += obj.offsetTop;
        obj = obj.offsetParent;
      }
    }
    else if (obj.y)
    {
      curtop += obj.y;
    }
  }
  return curtop;  // adjustment for proper display
}

DOM.ScrollY = function()
{
  var scrolly = window.pageYOffset || document.documentElement.scrollTop || 0;
  return scrolly;
}

DOM.ScrollX = function()
{
  var scrollx = window.pageXOffset || document.documentElement.scrollLeft || 0;
  return scrollx;
}

DOM.BrowserHeight = function()
{
  var ht;
  if (self.innerHeight) ht = self.innerHeight;
  else if (document.documentElement && document.documentElement.clientHeight) ht = document.documentElement.clientHeight;
  else if (document.body) ht = document.body.clientHeight;
  return ht;
}

DOM.BrowserWidth = function()
{
  var wd;
  if (self.innerWidth) wd = self.innerWidth;
  else if (document.documentElement && document.documentElement.clientWidth) wd = document.documentElement.clientWidth;
  else if (document.body) wd = document.body.clientWidth;
  return wd;
}

DOM.Hide = function(id)
{
  var obj = document.getElementById(id);
  if (obj)
  {
    obj.style.display = "none";
  }
}

DOM.Show = function(id)
{
  var obj = document.getElementById(id);
  if (obj)
  {
    obj.style.display = "";
  }
}

DOM.Toggle = function(id)
{
  var obj = document.getElementById(id);
  if (obj)
  {
    obj.style.display = (obj.style.display=='none') ? "" : "none";
  }
}

DOM.Open = function(id,speed,doAfter,clearheight)
{
  function slideOpen()
  {
    currentHeight += speed;
    if (currentHeight >= ht)
    {
      if (clearheight) obj.style.height = 'auto';
      else obj.style.height = ht+'px';
      clearTimeout(Opener);
      
      if (doAfter) doAfter();
    }
    else
    {
      obj.style.height = currentHeight+'px';
      Opener = setTimeout(slideOpen,0);
    }
  }

  var obj = document.getElementById(id);
  if (obj)
  {
    var Opener;
    if (!speed) var speed = DOM.slideSpeed;
    obj.style.display = "";
    obj.style.overflow = "hidden";
    var ht = obj.scrollHeight;
    var currentHeight = 0;
    obj.style.height = "0";
    Opener = setTimeout(slideOpen,0);
  }
}

DOM.Close = function(id,speed,doAfter)
{
  function slideClosed()
  {
    currentHeight -= speed;
    if (currentHeight <= 0)
    {
      obj.style.height = 'auto';
      obj.style.display = "none";
      clearTimeout(Closer);
      if (doAfter) doAfter();
    }
    else
    {
      obj.style.height = currentHeight+'px';
      Closer = setTimeout(slideClosed,0);
    }
  }
  
  var obj = document.getElementById(id);
  if (obj)
  {
    obj.style.overflow = "hidden";
    var Closer;
    if (!speed) var speed = DOM.slideSpeed;
    var currentHeight = (parseInt(obj.style.height) > 0) ? parseInt(obj.style.height) : obj.scrollHeight;
    Closer = setTimeout(slideClosed,0);
  }
}

DOM.Swap = function(id1,id2)
{
  var obj1 = document.getElementById(id1);
  var obj2 = document.getElementById(id2);
  if (obj1 && obj2)
  {
    var disp = obj1.style.display;
    obj1.style.display = (disp=='none') ? "" : "none";
    obj2.style.display = (disp=='none') ? "none" : "";
  }
}

DOM.SetHeight = function(id,ht)
{
  var obj = document.getElementById(id);
  if (obj)
  {
    obj.style.height = ht+'px';
  }
}

DOM.SetWidth = function(id,wd)
{
  var obj = document.getElementById(id);
  if (obj)
  {
    obj.style.width = wd+'px';
  }
}

DOM.GetHeight = function(id)
{
  var obj = document.getElementById(id);
  if (obj)
  {
    return obj.offsetHeight;
  }
  else return false;
}

DOM.GetWidth = function(id)
{
  var obj = document.getElementById(id);
  if (obj)
  {
    return obj.offsetWidth; // width including borders. clientWidth would exclude border
  }
  else return false;
}

DOM.SetClass = function(id,classname)
{
  var obj = document.getElementById(id);
  if (obj)
  {
    obj.className = classname;
  }
}

DOM.GetClass = function(id,classname)
{
  var obj = document.getElementById(id);
  if (obj)
  {
    return obj.className;
  }
}

DOM.SetHTML = function(id,html)
{
  var obj = document.getElementById(id);
  if (obj)
  {
    obj.innerHTML = html;
  }
}

DOM.GetHTML = function(id)
{
  var obj = document.getElementById(id);
  if (obj)
  {
    return obj.innerHTML;
  }
}

DOM.SetValue = function(id,val)
{
  var obj = document.getElementById(id);
  if (obj)
  {
    obj.value = val;
  }
}

DOM.GetValue = function(id)
{
  var obj = document.getElementById(id);
  if (obj && obj.value)
  {
    return obj.value.trim();
  }
  else return false;
}

DOM.Blur = function(id)
{
  var obj = document.getElementById(id);
  obj.blur();
}

DOM.Exists = function(id)
{
  var obj = document.getElementById(id);
  if (obj) return true;
  else return false;
}

/******************************************************************************/
// FORMS LIBRARY FUNCTIONS

var Forms = {}

Forms.Limit = function(obj,maxchar)
{
  var st = obj.scrollTop;
  obj.value = obj.value.substr(0,maxchar);
  obj.scrollTop = st;
}

Forms.HandleReturn = function(e,obj,func)
{
  alert("Forms.HandleReturn has been replaced by Forms.Enter");
  if (window.event) key = window.event.keyCode;
  else key = e.which;

  if (key==13) return func(obj);
}

Forms.Enter = function(e,obj,func)
{
  if (window.event) key = window.event.keyCode;
  else key = e.which;

  if (key==13) return func(obj);
}

Forms.SetValue = function(id,val)
{
  var obj = document.getElementById(id);
  if (obj)
  {
    obj.value = val;
  }
}

Forms.GetValue = function(id)
{
  var obj = document.getElementById(id);
  if (obj && obj.value)
  {
    return obj.value.trim();
  }
  //else return false;
  else return "";
}

Forms.Enable = function(id)
{
  var obj = document.getElementById(id);
  if (obj)
  {
    obj.disabled = false;
  }
}

Forms.Disable = function(id)
{
  var obj = document.getElementById(id);
  if (obj)
  {
    obj.disabled = true;
  }
}

Forms.Check = function(id)
{
  var obj = document.getElementById(id);
  if (obj)
  {
    obj.checked = true;
  }
}

Forms.Uncheck = function(id)
{
  var obj = document.getElementById(id);
  if (obj)
  {
    obj.checked = false;
  }
}

Forms.Ischecked = function(id)
{
  var obj = document.getElementById(id);
  if (obj) return (obj.checked?1:0);
  else return 0;
}

/******************************************************************************/
// AJAX OBJECT

var AjaxCount = 0;

function Ajax(fn)
{
  var obj = this;
  this.whatToDo = fn;

  function createRequestObject()
  {
    var ro;
    if (window.XMLHttpRequest)
    {
      ro = new XMLHttpRequest();
    }
    else if (window.ActiveXObject)
    {
      ro = new ActiveXObject("Microsoft.XMLHTTP");
    }
    else ro = null;
    return ro;
  }
  
  this.http = createRequestObject();

  this.sendRequest = function(url,addtohistory)
  {
    //if (addtohistory)
    this.http.open('get',url);
    this.http.onreadystatechange = this.handleResponse;
    this.http.send(null);
  }

  this.sendPostRequest = function(url,params)
  {
    //location = 'http://'+location.hostname+location.pathname+"#"+AjaxCount; AjaxCount++;
    this.http.open('POST',url,true);
    this.http.onreadystatechange = this.handleResponse;
    this.http.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
    this.http.send(params);
  }

  this.handleResponse = function()
  {
    if (obj.http.readyState == 4)
    {
      if (obj.http.status == 200)
      {
        //alert('status 200')
        // process response
        //alert(obj.http.responseText);
        obj.whatToDo(obj.http.responseText);
      }
      else
      {
        if (obj.http.status > 0)
        {
          alert('ajax error: '+obj.http.status);
          // process error
          alert(obj.http.responseText);
        }
        else
        {
          // error 0: can't process response

        }
      }
    }
  }
}

/******************************************************************************/
/* AUTOFILL */

function AutoFill(sourcelist,isSelect)
{
  this.SelectedIndex = -1;
  this.list = null;
  this.textbox = null;
  this.sourceList = sourcelist;
  this.isSelect = isSelect;
  this.originalValue = "";
  this.emptyList = "";
  this.suggestOnBlank = true;
  this.allowBlank = false;

  this.Init = function(fillDiv,textbox)
  {
    this.list = document.getElementById(fillDiv);
    this.textbox = document.getElementById(textbox);
    this.originalValue = this.textbox.value.trim();
  }

  this.Suggest = function(e,obj)
  {
    if (window.event) var key=event.keyCode;
    var key = e.which;
    
    if (this.sourceList.length<1)
    {
      var html = this.emptyList;
      if (this.isSelect) obj.value = obj.value.substr(0,obj.value.length-1);
    }
    else
    {
      // if select, only allow valid options
      if (this.isSelect && (obj.value.trim()!=""))
      {
        var invalid = true;
        var str = obj.value;
        for (var i in this.sourceList)
        {
          if (this.sourceList[i].substr(0,str.length).toLowerCase()==str.toLowerCase())
          {
            invalid = false;
            break;
          }
        }
        if (invalid)
        {
          if ((key==9) || (key==13)) return false;
          else
          {
            obj.value = str.substr(0,str.length-1);
            return false;
          }
        }
      }
      
      if (key==40)
      {
        // move down
        var divs = this.list.getElementsByTagName('div');
        this.SelectedIndex += 1;
        if (this.SelectedIndex >= divs.length) this.SelectedIndex = divs.length-1;
        for (var i=0;i<divs.length;i++)
        {
          if (i==this.SelectedIndex)
          {
            divs[i].className = "selected";
          }
          else divs[i].className = "";
        }
        return;
      }
      else if (key==38)
      {
        // move up
        var divs = this.list.getElementsByTagName('div');
        this.SelectedIndex -= 1;
        if (this.SelectedIndex < -1) this.SelectedIndex = -1;
        for (var i=0;i<divs.length;i++)
        {
          if (i==this.SelectedIndex)
          {
            divs[i].className = "selected";
          }
          else divs[i].className = "";
        }
        return;
      }
      else if (key==13)
      {
        // enter current selection
        var divs = this.list.getElementsByTagName('div');
        if ((this.SelectedIndex < 0) || (this.SelectedIndex >= divs.length))
        {
          if (this.isSelect) return false;
          return;
        }
        obj.value = divs[this.SelectedIndex].innerHTML;
        this.list.innerHTML = "";
        this.list.style.display = "none";
        return;
      }
      // add handling for <esc> to close list
      
      var str = obj.value;
      this.SelectedIndex = -1;
      if (str=="" && (!this.isSelect || !this.suggestOnBlank))
      //if (!this.isSelect && (str==""))
      {
        this.list.style.display = "none";
        return;
      }
      var opts = [];
      var html = "";
      for (var i in this.sourceList)
      {
        if (this.sourceList[i].substr(0,str.length).toLowerCase()==str.toLowerCase())
        {
          opts[opts.length] = this.sourceList[i];
          var htm = "<div id='item"+i+"' onmouseover='AutoFill.Highlight(this,true);' onmouseout='AutoFill.Highlight(this,false);' onmousedown='AutoFill.SelectOption(this,"+this.id+");'>"+this.sourceList[i]+"</div>";
          html += htm;
        }
      }
    }
    if (html != "")
    {
      var listtop = DOM.PosY(obj) + DOM.GetHeight(obj.id);
      this.list.style.top = listtop+'px';
      this.list.style.left = DOM.PosX(obj) + 'px';
      this.list.style.display = "";
      this.list.innerHTML = html;
      this.list.style.height = "auto";
      // calculate height
      var wrapperbottom = DOM.GetHeight('wrapper')-2;
      var wrapper = document.getElementById('wrapper');
      if (wrapper.scrollTop>0) wrapperbottom += wrapper.scrollTop;
      var listbottom = DOM.GetHeight(this.list.id)+listtop;
      if (listbottom > wrapperbottom) this.list.style.height = (wrapperbottom - listtop)+'px';
    }
    else
    {
      this.list.style.display = "none";
      this.list.innerHTML = "";
    }
  }

  this.SelectOption = function(obj)
  {
    this.SelectedIndex = -1;
    this.textbox.value = obj.innerHTML;
    this.list.innerHTML = "";
    this.list.style.display = "none";
    this.textbox.blur();
  }

  this.Clear = function(obj)
  {
    if (this.isSelect)
    {
      if (!(obj.value.trim()=="" && this.allowBlank))
      {
        var invalid = true;
        var str = obj.value;
        for (var i in this.sourceList)
        {
          if (this.sourceList[i].toLowerCase()==str.toLowerCase())
          {
            invalid = false;
            break;
          }
        }
        if (invalid) obj.value = this.originalValue;
        else obj.value = this.sourceList[i];
      }
    }
    this.list.style.display = "none";
    this.list.innerHTML = "";
  }
  
  this.ProcessTab = function(e,obj)
  {
    if (window.event) var key=event.keyCode;
    var key = e.which;
    if ((key==9) || (key==13))
    {
      // enter current selection
      var divs = this.list.getElementsByTagName('div');
      if ((this.SelectedIndex < 0) || (this.SelectedIndex >= divs.length))
      {
        if (this.isSelect) return false;
        else return;
      }
      obj.value = divs[this.SelectedIndex].innerHTML;
      this.list.innerHTML = "";
      this.list.style.display = "none";
      return (key==9);
    }
  }

  AutoFill.Register(this);

}

AutoFill.Count = 0;
AutoFill.Instances = [];

AutoFill.Register = function(obj)
{
  AutoFill.Count++;
  obj.id = AutoFill.Count;
  AutoFill.Instances[AutoFill.Count] = obj;
}

AutoFill.Highlight = function(obj,highlight)
{
  obj.className = (highlight) ? "selected" : "";
}

AutoFill.SelectOption = function(obj,id)
{
  AutoFill.Instances[id].SelectOption(obj);
}


/******************************************************************************/
/* MultiFill */

function MultiFill(sourcelist,isSelect)
{
  this.SelectedIndex = -1;
  this.list = null;
  this.textbox = null;
  this.sourceList = sourcelist;
  this.isSelect = isSelect;
  this.originalValue = "";
  this.emptyList = "";

  this.Init = function(fillDiv,textbox)
  {
    this.list = document.getElementById(fillDiv);
    this.textbox = document.getElementById(textbox);
    this.originalValue = this.textbox.value.trim();
  }

  this.Suggest = function(e,obj,gotfocus)
  {
    if (e) var key = e.which;
    else if (window.event) var key=event.keyCode;
    
    if (gotfocus && obj.value.trim() != "") obj.value += ',';
    var str = obj.value.substr(obj.value.lastIndexOf(',')+1).trim();
    var prev = obj.value.substr(0,obj.value.lastIndexOf(',')+1);
    
    if (this.sourceList.length<1)
    {
      var html = this.emptyList;
      if (this.isSelect) obj.value = prev+str.substr(0,str.length-1);
    }
    else
    {
      // if select, only allow valid options
      if (this.isSelect && (obj.value.trim()!=""))
      {
        var invalid = true;
        
        for (var i in this.sourceList)
        {
          if (this.sourceList[i].substr(0,str.length).toLowerCase()==str.toLowerCase())
          {
            invalid = false;
            break;
          }
        }
        if (invalid)
        {
          if ((key==9) || (key==13)) return false;
          else
          {
            obj.value = prev+str.substr(0,str.length-1);
            return false;
          }
        }
      }
      
      if (key==40)
      {
        // move down
        var divs = this.list.getElementsByTagName('div');
        this.SelectedIndex += 1;
        if (this.SelectedIndex >= divs.length) this.SelectedIndex = divs.length-1;
        for (var i=0;i<divs.length;i++)
        {
          if (i==this.SelectedIndex)
          {
            divs[i].className = "selected";
          }
          else divs[i].className = "";
        }
        return;
      }
      else if (key==38)
      {
        // move up
        var divs = this.list.getElementsByTagName('div');
        this.SelectedIndex -= 1;
        if (this.SelectedIndex < -1) this.SelectedIndex = -1;
        for (var i=0;i<divs.length;i++)
        {
          if (i==this.SelectedIndex)
          {
            divs[i].className = "selected";
          }
          else divs[i].className = "";
        }
        return;
      }
      else if (key==13 || key==9)
      {
        /*
        // enter current selection
        var divs = this.list.getElementsByTagName('div');
        if ((this.SelectedIndex < 0) || (this.SelectedIndex >= divs.length))
        {
          if (this.isSelect) return false;
          return false;
        }
        obj.value = prev+divs[this.SelectedIndex].innerHTML;
        this.list.innerHTML = "";
        this.list.style.display = "none";
        return false;
        */
      }
      //if (gotfocus) return;
      // add handling for <esc> to close list
      this.SelectedIndex = 0;
      
      if (!this.isSelect && (str==""))
      {
        this.list.style.display = "none";
        return;
      }
      var opts = [];
      var html = "";
      var ct = 0;
      
      for (var i in this.sourceList)
      {
        if (this.sourceList[i].substr(0,str.length).toLowerCase()==str.toLowerCase())
        {
          opts[opts.length] = this.sourceList[i];
          if (ct==this.SelectedIndex)
          {
            var htm = "<div id='item"+i+"' class='selected' onmouseover='MultiFill.Highlight(this,true);' onmouseout='MultiFill.Highlight(this,false);' onmousedown='MultiFill.SelectOption(this,"+this.id+");'>"+this.sourceList[i]+"</div>";
          }
          else
          {
            var htm = "<div id='item"+i+"' onmouseover='MultiFill.Highlight(this,true);' onmouseout='MultiFill.Highlight(this,false);' onmousedown='MultiFill.SelectOption(this,"+this.id+");'>"+this.sourceList[i]+"</div>";
          }
          html += htm;
          ct++;
        }
      }
    }
    
    if (html != "")
    {
      var listtop = DOM.PosY(obj) + DOM.GetHeight(obj.id);
      this.list.style.top = listtop+'px';
      this.list.style.left = DOM.PosX(obj) + 'px';
      this.list.style.display = "";
      this.list.innerHTML = html;
      this.list.style.height = "auto";
      // calculate height
      
      var wrapperbottom = DOM.GetHeight('wrapper')-2;
      var wrapper = document.getElementById('wrapper');
      if (wrapper.scrollTop>0) wrapperbottom += wrapper.scrollTop;
      var listbottom = DOM.GetHeight(this.list.id)+listtop;
      if (listbottom > wrapperbottom) this.list.style.height = (wrapperbottom - listtop)+'px';
      
    }
    else
    {
      this.list.style.display = "none";
      this.list.innerHTML = "";
    }
  }

  this.SelectOption = function(obj)
  {
    var prev = this.textbox.value.substr(0,this.textbox.value.lastIndexOf(',')+1);
    this.SelectedIndex = -1;
    this.textbox.value = prev+obj.innerHTML + ',';
    this.list.innerHTML = "";
    this.list.style.display = "none";
    this.textbox.blur();
    this.textbox.focus();
  }

  this.Clear = function(obj)
  {
    var str = obj.value.trim();
    if (str.substr(str.length-1)==',') str = str.substr(0,str.length-1);
    obj.value = str;
    this.list.style.display = "none";
    this.list.innerHTML = "";
  }
  
  this.ProcessTab = function(e,obj)
  {
    if (window.event) var key=event.keyCode;
    var key = e.which;
    if ((key==9) || (key==13))
    {
      // enter current selection
      var str = obj.value.substr(obj.value.lastIndexOf(',')+1).trim();
      var prev = obj.value.substr(0,obj.value.lastIndexOf(',')+1);
      var divs = this.list.getElementsByTagName('div');
      if ((this.SelectedIndex < 0) || (this.SelectedIndex >= divs.length))
      {
        return false;
      }
      obj.value = prev+divs[this.SelectedIndex].innerHTML +',';
      this.list.innerHTML = "";
      this.list.style.display = "none";
      this.textbox.blur();
      this.textbox.focus();
      return false;
    }
  }

  MultiFill.Register(this);

}

MultiFill.Count = 0;
MultiFill.Instances = [];

MultiFill.Register = function(obj)
{
  MultiFill.Count++;
  obj.id = MultiFill.Count;
  MultiFill.Instances[MultiFill.Count] = obj;
}

MultiFill.Highlight = function(obj,highlight)
{
  obj.className = (highlight) ? "selected" : "";
}

MultiFill.SelectOption = function(obj,id)
{
  MultiFill.Instances[id].SelectOption(obj);
}