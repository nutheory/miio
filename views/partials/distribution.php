<? global $User, $Profile, $AVATAR_URL, $IS_GROUP, $IS_MEMBER, $IS_ADMIN, $CONTROLLER; ?>  

<div class="distribution">
  <? if ($IS_GROUP) { ?>
		<input type="hidden" name="message_distribution" id="message_distribution" value="group">
    <input type="hidden" name="group_distribution_list" id="group_distribution_list" value="<?= $Profile->username ?>">
		<label class='username'>Send to:</label>
    <div class="username" id="messageform_username"><?= $Profile->username ?></div>
  <? } else { ?>
	  <? if ($CONTROLLER!='user') { ?>
		  <label class='username'>Send to:</label>
      <div class="username" id="messageform_username"><?= $Profile->username ?></div>
		  <div class="privacy">
        <input type="checkbox" name="messageform_private" id="messageform_private" onclick="MessageForm.ChangeDirectDistribution(this.checked)">
        Private Message
      </div>
      <input type="hidden" name="message_distribution" id="message_distribution" value="direct">
      <input type="hidden" name="direct_distribution_list" id="direct_distribution_list" value="<?= $Profile->username ?>">
      <input type="hidden" name="private_distribution_list" id="private_distribution_list" value="<?= $Profile->username ?>">
	  <? } else { ?>
		  <label for="message_distribution">Message Distribution:</label>
      <select name="message_distribution" id="message_distribution" onchange="MessageForm.ChangeDistribution(this.value)">
        <option value="public" selected>Public to Everyone</option>
        <option value="direct">Public to...</option>
        <option value="friends">Private to Friends only</option>
        <option value="private">Private to...</option>
        <option value="group">Group</option>
      </select>

		  <div id="distribution_public" style="display:none"></div>
		  
      <div id="distribution_friends" style="display:none;">
        <div class="note">
          Friends are members that follow you and you follow them
        </div>
      </div>
      
      <div id="distribution_direct" style="display:none">
        <label>Send to:</label>
        <textarea name="direct_distribution_list" id="direct_distribution_list" onkeydown="return MessageForm.Direct.ProcessTab(event,this)" onkeyup="MessageForm.Direct.Suggest(event,this)" onfocus="MessageForm.Direct.Suggest(event,this,true)" onblur="MessageForm.Direct.Clear(this);" autocomplete="off"></textarea>
        <div id="send_to_direct" style="display:none" class="autofill"></div>
      </div>
      
      <div id="distribution_private" style="display:none">
        <label>Send to:</label>
        <textarea name="private_distribution_list" id="private_distribution_list" onkeydown="return MessageForm.Private.ProcessTab(event,this)" onkeyup="MessageForm.Private.Suggest(event,this)" onfocus="MessageForm.Private.Suggest(event,this,true)" onblur="MessageForm.Private.Clear(this);" autocomplete="off"></textarea>
        <div id="send_to_private" style="display:none" class="autofill"></div>
      </div>
      
      <div id="distribution_group" style="display:none;">
        <label>Group name:</label>
        <input type="text" name="group_distribution_list" id="group_distribution_list" onkeydown="return MessageForm.Group.ProcessTab(event,this)" onkeyup="MessageForm.Group.Suggest(event,this)" onfocus="MessageForm.Group.Suggest(event,this)" onblur="MessageForm.Group.Clear(this);" autocomplete="off">
        <div id="send_to_group" style="display:none" class="autofill"></div>
        <div class="note">
          You can only send this message to one group at a time.
        </div>
      </div>
	  <? } ?>
  <? } ?>
</div>