<style>
  div.container{
    width: 950px;
  }
  div.crowd_style{
    padding: 5px;
    background: #EBEBEB;
    margin: 10px;
    width:450px;
    height:575px;
    font-family: Calibri,Helvetica,Arial,sans-serif;
    float: left; 
  }
  div.crowd_style_test{
    padding: 5px;
    margin: 0px 10px 10px 10px;
    background: #EBEBEB;
    width: 450px;
    height: 280px;
    font-family: Calibri,Helvetica,Arial,sans-serif;
    float: left;
  }
  div.information_pane{
    height:280px;
    width: 450px;
    padding: 5px;
    background: #EBEBEB;
    margin: 0px 10px 10px 0;
    font-family: Calibri,Helvetica,Arial,sans-serif;
    float:left;
  }
  div.advanced{
    padding: 5px;
    background: #EBEBEB;
    margin: 10px 10px 10px 0px;
    width:450px;
    height:575px;
    font-family: Calibri,Helvetica,Arial,sans-serif;
    float: left; 
  }
  div.banner{
    padding 5px;
    margin-left: 10px;
    margin-top: 15px;
    font-family: Calibri,Helvetica,Arial,sans-serif;
  }
  h1{
    margin: 0;
  }
  h2{
    margin: 0;
  }
  h3{
    margin: 0;
  }
  h4{
    margin: 0;
  }
  p{
    margin-bottom: 0;
  }
</style>
<?php 
//Debug
$debug = "false";

//Where are we?
$this_page = $_SERVER['PHP_SELF'].'?page='.$_GET['page'];

//If this is a test, we will use this variable
$bool_test = 0;

//If admin options updated (uses hidden field)
if ($_POST['stage'] == 'process') {

  $roles = array("Administrator", "Editor", "Author", "Contributor", "Subscriber");
  $roles_and_values = array();
  foreach ($roles as $role) {
    $roleValue = $_POST["cl-mapping-crowd-group-$role"];
    $roles_and_values[$role] = $roleValue;
  }
  $crowd_login_mode = $_POST['crowd_login_mode'];
  if ("mode_map_group" === $crowd_login_mode) {
    update_option("crowd_wordpress_role_mappings", $roles_and_values);
  }
  if ("mode_create_group" === $crowd_login_mode) {
    update_option('crowd_group', $_POST['crowd_group']);
  }
  if (in_array($crowd_login_mode, array("mode_create_group", "mode_create_all"))) {
    update_option('crowd_account_type', $_POST['crowd_account_type']);
  }
	update_option('crowd_url', $_POST['crowd_url']);
	update_option('crowd_app_name', $_POST['crowd_app_name']);
	update_option('crowd_app_password', $_POST['crowd_app_password']);
	update_option('crowd_security_mode', $_POST['crowd_security_mode']);
	update_option('crowd_login_mode', $crowd_login_mode);
} elseif ($_POST['stage'] == 'test') {
	//Test credentials
	global $bool_test;
	
	//Temporarily change security mode for test. Store old setting.
	$temp_holder = get_option("crowd_security_mode");
	update_option("crowd_security_mode", "security_high");
	
	$test_user = wp_authenticate($_POST['test_username'],$_POST['test_password']);
	
	//Restore security mode setting.
	update_option("crowd_security_mode", $temp_holder);
	
	if ($test_user->ID > 0) {
		$bool_test = 1;
	} else {
		$bool_test = 2;
	}
}

//Load settings, etc
$crowd_url = get_option('crowd_url');
$crowd_app_name = get_option('crowd_app_name');
$crowd_app_password = get_option('crowd_app_password');
$crowd_security_mode = get_option('crowd_security_mode');
$crowd_login_mode = get_option('crowd_login_mode');
$crowd_group = get_option('crowd_group');
$crowd_account_type = get_option('crowd_account_type');
$crowd_wordpress_role_mappings = get_option("crowd_wordpress_role_mappings");

?>
<script>
  var crowdAccountType = "<?php echo $crowd_account_type; ?>";
  var crowdGroup = "<?php echo $crowd_group; ?>";
  var crowdWordpressRoleMappings =
<?php
  $mappings = array();
  foreach ($crowd_wordpress_role_mappings as $role => $group) {
    array_push($mappings, '"' . $role . '":"' . $group . '"');
  }
  $result = '{';
  for ($i = 0; $i < count($crowd_wordpress_role_mappings); $i++) {
    if ($i != 0) {
      $result .= ",";
    }
    $result .= $mappings[$i];
  }
  $result .= '};';
  echo $result;
?>
</script>
<div class="container">
  <div class="banner"><h1>Crowd Login 0.1</h1></div>
  <form style="display::inline;" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>&updated=true">
    <div class="crowd_style">
      <h2>Settings</h2>
      <h3>These are rather important.</h3>
      <div style="float: left; width: 235px;">
        <p>
          <strong>Application Name:</strong><br />
          <input name="crowd_app_name" type="text" value="<?php  echo $crowd_app_name; ?>" size="20" /><br />
          *The application name given to you by your Crowd administrator. Example: crowd_app
        </p>
      </div>
      <div style="float:left; width: 200px;">
        <p>
          <strong>Application Password:</strong><br />
          <input name="crowd_app_password" type="text" value="<?php  echo $crowd_app_password; ?>" size="15" /><br />
          *The application password given to you by your Crowd administrator.
        </p>
      </div>
      <p>
        <strong>Crowd URL:</strong><br />
        <input name="crowd_url" type="text" value="<?php  echo $crowd_url; ?>" size="35" /><br />
        *Example: https://crowd.example.local:8443/crowd
      </p>
      <input type="hidden" name="stage" value="process" />
      <input type="submit" name="button_submit" value="<?php _e('Update Options', 'crowd-login') ?> &raquo;" />
    </div>
    <div class="advanced">
      <h2>Advanced</h2>
      <h3>For the intrepid and daring among you.</h3>
      <p style="margin-bottom:0px;"><strong>Login mode:</strong>
        <div id="cl-login-mode">
          <div>
            <input class="cl-mode" id="cl-mode-normal" name="crowd_login_mode" type="radio" value="mode_normal" <?php if($crowd_login_mode=="mode_normal"){echo 'checked="checked"';}?> />
            <label for="cl-mode-normal">Authenticate Wordpress users against Crowd. I will create the accounts in Wordpress myself. (default)</label>
          </div>
          <div>
            <input class="cl-mode" id="cl-mode-create-all" name="crowd_login_mode" type="radio" value="mode_create_all" <?php if($crowd_login_mode=="mode_create_all"){echo 'checked="checked"';}?> />
            <label for="cl-mode-create-all">Create Wordpress accounts for anyone who successfully authenticates against Crowd.</label>
          </div>
          <div>
            <input class="cl-mode" id="cl-mode-create-group" name="crowd_login_mode" type="radio" value="mode_create_group" <?php if($crowd_login_mode=="mode_create_group"){echo 'checked="checked"';}?> />
            <label for="cl-mode-create-group">Create Wordpress accounts for users in the specified group:</label>
          </div>
          <div>
            <input class="cl-mode" id="cl-mode-map-group" name="crowd_login_mode" type="radio" value="mode_map_group" <?php if($crowd_login_mode == "mode_map_group"){ echo 'checked="checked"'; }?> />
            <label for="cl-mode-map-group">Create Wordpress accounts for user in specified groups, assign them role selected per group</label>
          </div>
        </div>
      </p>
      <p>
        <strong>Security mode:</strong><br>
        <input name="crowd_security_mode" type="radio" id="security_low" value="security_low" <?php if($crowd_security_mode=="security_low"){echo "checked";}?> > <label for="security_low"><strong>Low.</strong> Default mode. First attempts to login with Crowd accounts, failing that, it attempts to login using the local Wordpress password. If you intend to use a mixture of local and Crowd accounts, leave this mode enabled.</label><br/>
        <input name="crowd_security_mode" type="radio" id="security_high" value="security_high" <?php if($crowd_security_mode=="security_high"){echo "checked";}?> > <label for="security_high"><strong>High.</strong> Restrict login to only Crowd accounts. If a Wordpress username fails to authenticate against Crowd, login will fail. More secure.</label><br/>
      </p>
    </div>
  </form>
  <div class="crowd_style_test">
    <h2>Test Settings</h2>
    <h3>Use this form as a limited test for those settings you saved.* This <em>will</em> test user creation and group membership. If settings don't work, use another browser to try actually logging in. (unless you'd rather get locked out)</h3>
    <h4>*You did save them, right?</h4>
    <form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
      <p>
        Username:<br />
        <input name="test_username" type="text" size="35" />
      </p>
      <p>Password:<br />
        <input name="test_password" type="password" size="35" />
      </p>
      <input type="hidden" name="stage" value="test" />
      <input type="submit" name="button_submit" value="<?php _e('Test Settings', 'simple-ldap-login') ?> &raquo;" />
    </form>
    <p>
      <h4>Test Results:</h4>
      <?php
      if($bool_test == 0) {
        echo "Nothing to report yet, Mr. Fahrenheit.";
      }
      if($bool_test == 1) {
        echo "Congratulations! The test succeeded. This account is able to login.";
      }
      elseif($bool_test == 2) {
        echo "Failure. Your settings do not seem to work yet or the credentials are either wrong or have insufficient group membership.";
      }
      ?>
    </p>
  </div>

  <?php
  //Echo settings
  if($debug == "true") {
    echo "<p style=\"clear:both;\">Debug Info:<br/>";
    echo "crowd_directory_type: ".get_option("crowd_directory_type")."/".$_POST['LDAP']."<br/>";
    echo "crowd_login_mode: ".get_option("crowd_login_mode")."/".$_POST['mode']."<br/>";
    echo "crowd_group: ".get_option("crowd_group")."/".$_POST['group_name']."<br/>";
    echo "crowd_account_type: ".get_option("crowd_account_type")."/".$_POST['create_type']."<br/></p>";
  }
  ?>
</div>
