<link href="css/signup_settings.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/signup_settings.js"></script>

<script type="text/javascript">
  var countries = [];
  <?
  for($i=0;$i<count($COUNTRIES);$i++)
  {
    echo "countries[$i] = \"".$COUNTRIES[$i]['name']."\";\n";
  }
  ?>
</script>

<div id="setup">

	<div id="step_bar" class="steps">
		<div id="step_1" class="active">
			<div class="back" id="step_start"></div>
			<div class="center">
				<h3>Step 1</h3>
				<p>Invite</p>
			</div>
			<div class="point"></div>
		</div>
		<div id="step_2" class="inactive">
			<div class="back"></div>
			<div class="center">
				<h3>Step 2</h3>
				<p>Find Members</p>
			</div>
			<div class="point"></div>
		</div>
		<div id="step_3" class="inactive">
			<div class="back"></div>
			<div class="center">
				<h3>Step 3</h3>
				<p>Profile Information</p>
			</div>
			<div class="point"></div>
		</div>
		<div id="step_4" class="inactive">
			<div class="back"></div>
			<div class="center">
				<h3>Step 4</h3>
				<p>Profile Photo</p>
			</div>
			<div class="point"></div>
		</div>
	</div>
	
	<div id="setup_content" style="display:none">
	  <div id="invite">
		  <iframe id="email_inviter" src="inviter/index.php?type=setup"></iframe>
	  </div>
	</div>

</div>

<script type="text/javascript">
  Signup.Init();
</script>