<div id="user_filter_container" class="user_filter" style="display:none">
  <div class="clearfilter">
    <img src="images/search_glass.png" id="filter_user_filter" alt="filter" title="Filter by name">
    <img src="images/clear_filter.png" id="clear_user_filter" alt="clear filter" title="Clear filter" style="display:none" onclick="Users.ClearFilter()">
  </div>
  <input type="text" name="user_filter" id="user_filter" onkeyup="Users.Filter(this.value);" onfocus="Users.FilterFocus(this)" onblur="Users.FilterBlur(this)" value="Filter">
  
  
  <span>View</span>
  &nbsp;
  <span id='short_list' class='active'>
    <img class='off' src="images/buttons/filter_short_list.png" alt="short list" title="Short list" onclick="Users.Display('short_list')">
    <img class='on' src="images/buttons/filter_short_list_sel.png" alt="short list" title="Short list" onclick="Users.Display('short_list')">
  </span>
  
  <span id='long_list'>
    <img class='off' src="images/buttons/filter_long_list.png" alt="long list" title="Long list" onclick="Users.Display('long_list')">
    <img class='on' src="images/buttons/filter_long_list_sel.png" alt="long list" title="Long list" onclick="Users.Display('long_list')">
  </span>
  
  <span id='phone_on'>
    <img class='off' src="images/buttons/filter_phone_on.png" alt="phone on" title="Phone on" onclick="Users.Display('phone_on')">
    <img class='on' src="images/buttons/filter_phone_on_sel.png" alt="phone on" title="Phone on" onclick="Users.Display('phone_on')">
  </span>
  
  <span id='phone_off'>
    <img class='off' src="images/buttons/filter_phone_off.png" alt="phone off" title="Phone off" onclick="Users.Display('phone_off')">
    <img class='on' src="images/buttons/filter_phone_off_sel.png" alt="phone off" title="Phone off" onclick="Users.Display('phone_off')">
  </span>
  
  <span id='mute_on'>
    <img class='off' src="images/buttons/filter_mute_on.png" alt="mute on" title="Mute on" onclick="Users.Display('mute_on')">
    <img class='on' src="images/buttons/filter_mute_on_sel.png" alt="mute on" title="Mute on" onclick="Users.Display('mute_on')">
  </span>
  
  <span id='mute_off'>
    <img class='off' src="images/buttons/filter_mute_off.png" alt="mute off" title="Mute off" onclick="Users.Display('mute_off')">
    <img class='on' src="images/buttons/filter_mute_off_sel.png" alt="mute off" title="Mute off" onclick="Users.Display('mute_off')">
  </span>
  
</div>