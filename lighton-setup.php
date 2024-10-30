<?php                           

/*
LightOn
http://www.switch2mac.de/
Lighttpd Server Status WP-Plugin
*/

/*  Copyright 2009  David Krcek  (email : admin@switch2mac.de)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/       


if(!current_user_can('manage_lto')) {
	// var_dump(current_user_can('manage_lto'));
	die('Zugriff verweigert');
}


     
if(isset($_POST['server_options_save'])) {
  	$lto_options = get_option('lto_options');
    $lto_options["hostname"] = $_POST['lto_webserver'];
	$lto_options["statuscall"] = $_POST['lto_status'];
	$lto_options["servertyp"] = $_POST['lto_webserver_typ'];
	update_option("lto_options", $lto_options);
	echo '<div class="updated"><p>Server settings saved</strong></p></div>';
}

if(isset($_POST['template_options_save'])) {
    $lto_options = get_option('lto_options');
	$lto_options["tpl_cpu"] = $_POST['lto_tpl_akt_cpu'];
	$lto_options["tpl_graph"] = $_POST['lto_tpl_akt_graph'];
	$lto_options["tpl_sqlstat"] = $_POST['lto_tpl_akt_sqlstat'];
	$lto_options["tpl_web"] = $_POST['lto_tpl_akt_web'];
	$lto_options["tpl_dashboard"] = $_POST['lto_tpl_dasboard']; 
	$lto_options["tpl_sidebar_widget"] = $_POST['lto_tpl_sidebar_widget'];
	update_option("lto_options", $lto_options);
	echo '<div class="updated"><p>Template settings saved</strong></p></div>';
}

if(isset($_POST['template_options_reset'])) {
    $lto_options = get_option('lto_options');
    $lto_options["tpl_cpu"] = 'akt_cpu.php';
	$lto_options["tpl_graph"] = 'akt_graph.php';
	$lto_options["tpl_sqlstat"] = 'akt_sqlstat.php';
	$lto_options["tpl_web"] = 'akt_web.php';
	$lto_options["tpl_dashboard"] = 'dashboard.php'; 
	$lto_options["tpl_sidebar_widget"] = 'sidebar_widget.php';
	echo '<div class="updated"><p>Template settings reseted to standard values, please save the settings now</strong></p></div>';
}


if (!isset($lto_options)) $lto_options=get_option('lto_options');
$lto_avail_template=lto_getTemplates();


?>                         
<div class="wrap">
    	<h2>LightOn &rsaquo; Settings</h2>
	    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=lighton/lighton-setup.php">     
			<h3>Server</h3>
			<p><input type="submit" class="button-secondary" name="server_options_save" value="Save server settings" /></p>
			
			<table class="form-table">
				
				<tr style="background:#eee;" valign="middle">
					<th>Webservers address</th>
					<td><input name="lto_webserver" type="text" id="lto_webserver" value="<?php  echo $lto_options['hostname']; ?>"></td>
					<td>Hostname or TCP/IP address of your webserver - (DEFAULT http://127.0.0.1)</td>
				</tr>
				
				<tr style="background:#fff;" valign="middle">
					<th>Status page location</th>
					<td><input name="lto_status" type="text" id="lto_status" value="<?php  echo $lto_options['statuscall']; ?>"></td>
					<td>The location of your mod_status page - (DEFAULT Lighttpd: /server-status)</td>
				</tr>
				
				<tr style="background:#eee;" valign="middle">
					<th>Webserver type</th>
					<td><select name="lto_webserver_typ" size="1" id="lto_webserver_typ"> 
					<option value="1" <?php if ($lto_options['servertyp'] == 1) echo ' selected="selected"'; ?> >lighttpd</option>   
					<option value="2" <?php if ($lto_options['servertyp'] == 2) echo ' selected="selected"'; ?> >Apache2</option>
					</select></td>
					<td>Do you use Lighttp or Apache2 - (DEFAULT lighttpd))</td>
				</tr>
			</table>
			<p style="padding-top:15px"><input type="submit" class="button-secondary" name="server_options_save" value="Save server settings" /></p>
	  	</form>
	    <hr />
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=lighton/lighton-setup.php">     
			<h3>Templates</h3>	
            <p style="padding-top:15px">
				<input type="submit" class="button-secondary" name="template_options_save" value="Save Template changes" /> 
				&nbsp &nbsp
       		 	<input type="submit" class="button-secondary" name="template_options_reset" value="Reset Template to standard" />
			</p>
			
		  	<table class="form-table">
				
				<tr style="background:#eee;" valign="middle">
					<th>Dashboard template</th>
					<td><select name="lto_tpl_dasboard" size="1" id="lto_tpl_dasboard"> 
					<?php 
						foreach($lto_avail_template as $key=>$value) {
							echo "<option value='$value'"; 
							if ($lto_options['tpl_dashboard'] == $value) echo ' selected="selected"';  
							echo ">$value</option>";   
				    	}
					?>
					</select></td>
					<td>Select your dashboard template</td>
				</tr>
				
				<tr style="background:#fff;" valign="middle">
					<th>Sidebar widget template</th>
					<td><select name="lto_tpl_sidebar_widget" size="1" id="lto_tpl_sidebar_widget"> 
					<?php 
						foreach($lto_avail_template as $key=>$value) {
							echo "<option value='$value'"; 
							if ($lto_options['tpl_sidebar_widget'] == $value) echo ' selected="selected"';  
							echo ">$value</option>";   
				    	}
					?>
					</select></td>
					<td>Select your sidebar widget template</td>
				</tr>
				<tr style="background:#eee;" valign="middle">
					<th>Details CPU template</th>
					<td><select name="lto_tpl_akt_cpu" size="1" id="lto_tpl_akt_cpu"> 
					<?php 
						foreach($lto_avail_template as $key=>$value) {
							echo "<option value='$value'"; 
							if ($lto_options['tpl_cpu'] == $value) echo ' selected="selected"';  
							echo ">$value</option>";   
				    	}
					?>
					</select></td>
					<td>Select your CPU template on details page</td>
				</tr>
				<tr style="background:#fff;" valign="middle">
					<th>Details graph template</th>
					<td><select name="lto_tpl_akt_graph" size="1" id="lto_tpl_akt_graph"> 
					<?php 
						foreach($lto_avail_template as $key=>$value) {
							echo "<option value='$value'"; 
							if ($lto_options['tpl_graph'] == $value) echo ' selected="selected"';  
							echo ">$value</option>";   
				    	}
					?>
					</select></td>
					<td>Select your graph template on details page</td>
				</tr>	
				<tr style="background:#eee;" valign="middle">
						<th>Details SQL status template</th>
						<td><select name="lto_tpl_akt_sqlstat" size="1" id="lto_tpl_akt_sqlstat"> 
						<?php 
							foreach($lto_avail_template as $key=>$value) {
								echo "<option value='$value'"; 
								if ($lto_options['tpl_sqlstat'] == $value) echo ' selected="selected"';  
								echo ">$value</option>";   
					    	}
						?>
						</select></td>
						<td>Select your SQL status template on details page</td>
					</tr>	<tr style="background:#eee;" valign="middle">
							<th>Details webserver template</th>
							<td><select name="lto_tpl_akt_web" size="1" id="lto_tpl_akt_web"> 
							<?php 
								foreach($lto_avail_template as $key=>$value) {
									echo "<option value='$value'"; 
									if ($lto_options['tpl_web'] == $value) echo ' selected="selected"';  
									echo ">$value</option>";   
						    	}
							?>
							</select></td>
							<td>Select your webserver status template on details page</td>
						</tr>
			  	
			</table> 
			<p style="padding-top:15px">
		  		<input type="submit" class="button-secondary" name="template_options_save" value="Save Template changes" /> 
		  		&nbsp &nbsp
		  	 	<input type="submit" class="button-secondary" name="template_options_reset" value="Reset Template to standard" />
		  	</p>
		</form>
	 <hr />
</div>			 
<?php lightOn_footer();   ?>

          