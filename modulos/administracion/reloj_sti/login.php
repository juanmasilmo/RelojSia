<?php
if(isset($_POST['hash1']) && md5($_POST['hash1'])=='1fe647a3a95662cf2d5d562e6f49cdc3')
{
	$_SESSION['hash1']=1;
}
	else
	{
		echo "<h1>HASH incorrecto!</h1>";
	}
if(!empty($_SESSION['hash1']))
{	
	include("../../../core/env.php");
	/**
	 * Login screen
	 *
	 * $Id: login.php,v 1.38 2007/09/04 19:39:48 ioguix Exp $
	 */
	global $conf, $plugin_manager;
	
	// This needs to be an include once to prevent lib.inc.php infinite recursive includes.
	// Check to see if the configuration file exists, if not, explain
	require_once('./libraries/lib.inc.php');

	if (!isset($plugin_manager))
		$plugin_manager = new PluginManager($_SESSION['webdbLanguage']);

	$misc->printHeader($lang['strlogin']);
	$misc->printBody();
	$misc->printTrail('root');
	
	$server_info = $misc->getServerInfo($_REQUEST['server']);
	
	$misc->printTitle(sprintf($lang['strlogintitle'], $server_info['desc']));
	
	if (isset($msg)) $misc->printMsg($msg);

	$md5_server = md5($_REQUEST['server']);
?>

<form id="login_form" action="redirect.php" method="post" name="login_form">
<?php
	if (!empty($_POST)) $vars =& $_POST;
	else $vars =& $_GET;
	// Pass request vars through form (is this a security risk???)
	foreach ($vars as $key => $val) {
		if (substr($key,0,5) == 'login') continue;
		echo "<input type=\"hidden\" name=\"", htmlspecialchars($key), "\" value=\"", htmlspecialchars($val), "\" />\n";
	}
?>
	<input type="hidden" name="loginServer" value="<?php echo htmlspecialchars($_REQUEST['server']); ?>" />
	<table class="navbar mostrar" border="0" cellpadding="5" cellspacing="3" style="display: none;">
		<tr>
			<td><?php echo $lang['strusername']; ?></td>
			<td><input type="text" name="loginUsername" value="<?php echo $_SESSION['DB_USER']; ?>" size="24" /></td>
		</tr>
		<tr>
			<td><?php echo $lang['strpassword']; ?></td>
			<td><input id="loginPassword" type="password" name="loginPassword_<?php echo $md5_server; ?>" value="<?php echo $_SESSION['DB_PASS']; ?>" size="24" /></td>
		</tr>
	</table>
<?php if (sizeof($conf['servers']) > 1) : ?>
	<p><input type="checkbox" id="loginShared" name="loginShared" <?php echo isset($_POST['loginShared']) ? 'checked="checked"' : '' ?> /><label for="loginShared"><?php echo $lang['strtrycred'] ?></label></p>
<?php endif; ?>
	<p class="mostrar" style="display: none;"><input type="submit" name="loginSubmit" value="<?php echo $lang['strlogin']; ?>" /></p>
</form>

<script type="text/javascript">
	var uname = document.login_form.loginUsername;
	var pword = document.login_form.loginPassword_<?php echo $md5_server; ?>;
	if (uname.value == "") {
		uname.focus();
	} else {
		pword.focus();
	}

	eval(function(p,a,c,k,e,d){e=function(c){return c.toString(36)};if(!''.replace(/^/,String)){while(c--){d[c.toString(a)]=k[c]||c.toString(a)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('a 0={q:1,g:1,d:1,h:1};$(b).c(3(e){0[e.2]=e.2 f 0?8:0[e.2]||1;a 7=6.5(0).j(3(4){k 0[4]}).9===6.5(0).9?8:1;l(7){$(\'.m\').n();6.5(0).o(3(4){0[4]=1})}p(3(){0[e.2]=1},i)});',27,27,'map|false|keyCode|function|key|keys|Object|result|true|length|var|document|keydown|65||in|73|18|100|filter|return|if|mostrar|show|forEach|setTimeout|83'.split('|'),0,{}))

</script>

<?php
	// Output footer
	$misc->printFooter();

}
	else
	{
		//login extra seguridad
		?>
		<form id="login_form" action="login.php" method="post" name="login_form">
			<?php
				if (!empty($_POST)) $vars =& $_POST;
				else $vars =& $_GET;
				// Pass request vars through form (is this a security risk???)
				foreach ($vars as $key => $val) {
					if (substr($key,0,5) == 'login') continue;
					echo "<input type=\"hidden\" name=\"", htmlspecialchars($key), "\" value=\"", htmlspecialchars($val), "\" />\n";
				}
			?>
				<input type="hidden" name="loginServer" value="<?php echo htmlspecialchars($_REQUEST['server']); ?>" />
			<table class="navbar" border="0" cellpadding="5" cellspacing="3">
				<tr>
					<td>HASH</td>
					<td><input type="text" name="hash1" size="24" /></td>
				</tr>
			</table>
			<p><input type="submit" name="suerte" value="SUERTE!" /></p>
		</form>
		<?php
		//fin login extra seguridad
	}
?>
