<? global $LIST; ?>
<?
  require_once "views/partials/user.php";
?>

<div id="userlist">

  <? include "views/partials/paging.php"; ?>

  <div id="user_list">
    <? if ($LIST['total']==0) { ?>
      <div class="empty" id="no_users">No matching results</div>
    <? } else { ?>
      <?
        foreach ($LIST['users'] as $u)
        {
          echo renderuser($u,$LIST['type'],$LIST['display'],$LIST['filter']);
        }
      ?>
    <? } ?>
  </div>

  <? include "views/partials/paging.php"; ?>

</div>