<?
/*#####*/
?>
<div id="page_foot">
  <span class="links">
    <?
      if ($CONTROLLER == "pages" && $PAGE=="about") echo "<span class='active'>About Miio</span> |\n";
      else echo "<span><a href='pages/about'>About Miio</a></span> |\n";

      if (LOCAL) echo "<span><a href='http://localhost/miio_blog/'>Blog</a></span> |\n";
      else echo "<span><a href='http://blog.miio.com'>Blog</a></span> |\n";

      if ($CONTROLLER == "pages" && $PAGE=="contact") echo "<span class='active'>Contact Us</span> |\n";
      else echo "<span><a href='pages/contact'>Contact Us</a></span> |\n";

      if ($CONTROLLER == "pages" && $PAGE=="developers") echo "<span class='active'>Developers</span> |\n";
      else echo "<span><a href='pages/developers'>Developers</a></span> |\n";

      if ($CONTROLLER == "pages" && $PAGE=="terms") echo "<span class='active'>Terms of Use</span> |\n";
      else echo "<span><a href='pages/terms'>Terms of Use</a></span> |\n";

      if ($CONTROLLER == "pages" && $PAGE=="copyright") echo "<span class='active'>Copyright Policy</span> |\n";
      else echo "<span><a href='pages/copyright'>Copyright Policy</a></span> |\n";

      if ($CONTROLLER == "pages" && $PAGE=="conduct") echo "<span class='active'>Code of Conduct</span> |\n";
      else echo "<span><a href='pages/conduct'>Code of Conduct</a></span> |\n";

      if ($CONTROLLER == "pages" && $PAGE=="privacy") echo "<span class='active'>Privacy</span> |\n";
      else echo "<span><a href='pages/privacy'>Privacy</a></span> |\n";

      if (LOCAL) echo "<span><a href='http://localhost/miio_help/'>Help</a></span> |\n";
      else echo "<span><a href='http://help.miio.com/'>Help</a></span> |\n";

    if ($CONTROLLER == "pages" && $PAGE=="features") echo "<span class='active'>Features</span>";
      else echo "<span><a href='pages/features'>Features</a></span>";

      if ($User->is_super)
      {
        echo " |\n <span><a href='admin'>Admin</a></span>\n ";
        /*
        if ($CONTROLLER == "admin" && $PAGE=="featured") echo " |\n <span class='active'>Featured Users</span> |\n ";
        else echo " |\n <span><a href='admin/featured'>Featured Users</a></span> |\n ";

        if ($CONTROLLER == "admin" && $PAGE=="taglines") echo "<span class='active'>Taglines</span>";
        else echo "<span><a href='admin/taglines'>Taglines</a></span>";
        */
      }
    ?>
  </span>
  <span class="copyright">
  Copyright &copy; 2008-2010 - All Rights Reserved
  </span>
</div>

<div id="user_loading" style="display:none">
  <img src="images/loading.gif">Please wait...
</div>
