<? global $LIST, $CONTROLLER, $PAGE; ?>
<?
  include 'views/partials/message.php';
?>

<div id="messagelist">
  <div id="message_list">
    <? if (count($LIST['list'])==0) { ?>
      <div class="empty" id="no_message_text">No matching results</div>
      <? if ($CONTROLLER == 'user' && $PAGE == 'timeline') { ?>
        <? Render("partials","emptyaccount"); ?>
      <? } ?>
    <? } else { ?>
      <?
        foreach ($LIST['list'] as $id)
        {
          $message = Post::get($id);
          echo "<div id='messagecontainer_$id' class='messagecontainer'>";//echo $id;
          echo rendermessage($message,$LIST['filter']);
          echo "</div>";
        }
      ?>
    <? } ?>
  </div>

  <div class="more" id="more_link" <? if ($LIST['total']<=MESSAGES_PER_PAGE) echo 'style="display:none"'; ?>>
    <hr>
    <button id="more_button" onclick="return Messages.GetMore('<?= $CONTROLLER ?>',Messages.CurrentFilter);">More</button>
    <input type="hidden" id="viewtime" value="<?= $LIST['viewtime'] ?>">
    <input type="hidden" id="message_page" value="<?= $LIST['page'] ?>">
  </div>
</div>

<input type="hidden" id="total_messages" value="<?= $LIST['total'] ?>">
