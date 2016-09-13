<? global $LIST; ?>
<?
  require_once "views/partials/group.php";
?>

<div id="userlist">

  <? include "views/partials/paging.php"; ?>

  <div id="user_list">
    <? if ($LIST['total']==0) { ?>
      <div class="empty" id="no_users">No matching results</div>
    <? } else { ?>
      <?
        foreach ($LIST['groups'] as $g)
        {
          echo rendergroup($g,$LIST['type'],$LIST['display_type'],$LIST['filter']);
        }
      ?>
    <? } ?>
  </div>

  <? include "views/partials/paging.php"; ?>

</div>