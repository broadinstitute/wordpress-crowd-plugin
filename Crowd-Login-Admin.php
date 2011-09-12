<html>
<head>
<style>
div.container{
	width: 950px;
}
div.crowd_style{
	padding: 5px;
	background: #EBEBEB;
	margin: 10px;
	width:450px;
	height:375px;
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
	height:375px;
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
</head>
<?php 
//Debug
$debug = "false";

//Where are we?
$this_page = $_SERVER['PHP_SELF'].'?page='.$_GET['page'];

//If this is a test, we will use this variable
$bool_test = 0;

//If admin options updated (uses hidden field)
if ($_POST['stage'] == 'process') {
	update_option('crowd_url', $_POST['crowd_url']);
	update_option('crowd_app_name', $_POST['crowd_app_name']);
	update_option('crowd_app_password', $_POST['crowd_app_password']);
	update_option('crowd_security_mode', $_POST['crowd_security_mode']);
	update_option('crowd_login_mode', $_POST['crowd_login_mode']);
	update_option('crowd_group', $_POST['crowd_group']);
	update_option('crowd_account_type', $_POST['crowd_account_type']);
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

?>
<body>
<div class="container">
<div class="banner"><h1>Crowd Login 0.1</h1></div>
<form style="display::inline;" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>&updated=true">
<div class="crowd_style">
<h2>Settings</h2>
<h3>These are rather important.</h3>
<div style="float: left; width: 235px;"><p><strong>Application Name:</strong><br />
<input name="crowd_app_name" type="text" value="<?php  echo $crowd_app_name; ?>" size="20" /><br />
*The application name given to you by your Crowd administrator. Example: crowd_app
</p></div>
<div style="float:left; width: 200px;"><p><strong>Application Password:</strong><br />
<input name="crowd_app_password" type="text" value="<?php  echo $crowd_app_password; ?>" size="15" /><br />
*The application password given to you by your Crowd administrator.
</p></div>
<p><strong>Crowd URL:</strong><br />
<input name="crowd_url" type="text" value="<?php  echo $crowd_url; ?>" size="35" /><br />
*Example: https://crowd.example.local:8443/crowd
</p>
<input type="hidden" name="stage" value="process" />
<input type="submit" name="button_submit" value="<?php _e('Update Options', 'crowd-login') ?> &raquo;" />
</div>
<div class="advanced">
<h2>Advanced</h2>
<h3>For the intrepid and daring among you.</h3>
<p style="margin-bottom:0px;"><strong>Login mode:</strong><br>
<input name="crowd_login_mode" type="radio" value="mode_normal" <?php if($crowd_login_mode=="mode_normal"){echo "checked";}?> > <label for="mode_normal">Authenticate Wordpress users against Crowd. I will create the accounts in Wordpress myself. (default)</label><br/>
<input name="crowd_login_mode" type="radio" value="mode_create_all" <?php if($crowd_login_mode=="mode_create_all"){echo "checked";}?> > <label for="mode_create_all">Create Wordpress accounts for anyone who successfully authenticates against Crowd.</label><br/>
<input name="crowd_login_mode" type="radio" value="mode_create_group" <?php if($crowd_login_mode=="mode_create_group"){echo "checked";}?>> <label for="mode_create_group">Create Wordpress accounts for users in the specified group:</label><input name="crowd_group" type="text" value="<?php  echo $crowd_group; ?>" size="12"/></p>
<p style="margin-left:15px; margin-top:0px;"><strong>For latter two options, create account as:</strong><br/>
<select name="crowd_account_type">
<option value="Administrator" <?php if($crowd_account_type=="Administrator"){echo 'selected="selected"';}?> >Administrator</option>
<option value="Editor" <?php if($crowd_account_type=="Editor"){echo 'selected="selected"';}?> >Editor</option>
<option value="Author" <?php if($crowd_account_type=="Author"){echo 'selected="selected"';}?> >Author</option>
<option value="Contributor" <?php if($crowd_account_type=="Contributor"){echo 'selected="selected"';}?> >Contributor</option>
<option value="Subscriber" <?php if($crowd_account_type=="Subscriber"){echo 'selected="selected"';}?> >Subscriber</option>
</select>
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
  <p>Username:<br />
<input name="test_username" type="text" size="35" />
 </p><p>Password:<br />
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
/*
<div class="information_pane">
<? echo "<iframe src =\"http://clifgriffin.com/plugins/simple-ldap-login/news.htm\" width=\"98%\" height=\"280px\" border=\"0\"><p>Oddly, your version of PHP doesn't allow file_get_contents to use URLs. But even more oddly, your browser doesn't allow frames! I think it's time for you to consider leaving 1998 in the past.</p></iframe>"; ?>
</div>
</div>
*/
//Echo settings
if($debug == "true")
{
echo "<p style=\"clear:both;\">Debug Info:<br/>";
echo "crowd_directory_type: ".get_option("crowd_directory_type")."/".$_POST['LDAP']."<br/>";
echo "crowd_login_mode: ".get_option("crowd_login_mode")."/".$_POST['mode']."<br/>";
echo "crowd_group: ".get_option("crowd_group")."/".$_POST['group_name']."<br/>";
echo "crowd_account_type: ".get_option("crowd_account_type")."/".$_POST['create_type']."<br/></p>";
}
?>
</body>
</html>