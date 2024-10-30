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
	die('Zugriff verweigert');
}


function lto_printMySql() {
	global $wpdb;     
	$lto_options=get_option('lto_options');
	$lto_sql_hostname = mysql_get_host_info();
	$lto_sql_version = mysql_get_server_info();
	$lto_tmp = mysql_query('SHOW STATUS');
	$lto_status = array();
	while ($row = mysql_fetch_assoc($lto_tmp)) {
		$lto_sql_status[$row['Variable_name']]=$row['Value'];
	} 
	$lto_sql_uptime=secondsToWords($lto_sql_status['Uptime']);
	$lto_sql_list = mysql_query('SHOW FULL PROCESSLIST'); 
	include_once TEMPLATE_PATH.$lto_options["tpl_sqlstat"]; 
	
	;
}


function lto_printProcDetails($lt_result) {
		$lto_options=get_option('lto_options');
		$lt_proc_list=$lt_result[5];
		include_once TEMPLATE_PATH.$lto_options["tpl_cpu"];                
}


              
function lto_printReqDetails() {
        $lto_options=get_option('lto_options'); 
        if ($lto_options['servertyp']==1) $lt_result=lightOn_getLighty();
        if ($lto_options['servertyp']==2) $lt_result=lightOn_getApache();
		$lt_request=$lt_result['request'];
		include_once TEMPLATE_PATH.$lto_options["tpl_web"];
		
		                   
}		
	   
function lto_drawgraph() {
		$lto_options=get_option('lto_options');
		$lt_load = lightOn_getLoad();
		$lt_cpu = lightOn_getCpu();
		include_once TEMPLATE_PATH.$lto_options["tpl_graph"];
		return($lt_cpu);
	}




 ### AKTUELL Anzeige        
	$time_start = array_sum(explode(' ', microtime()));                     	
	print "<div class='wrap'>";
		print "<div><a name='top'></a></div>";
		print "<h3>LightOn &rsaquo; Graph</h3>";
		$lto_options = get_option('lto_options'); ### optionsarray auslesen
		$lt_cpu=lto_drawgraph();
		print "<h3>LightOn &rsaquo; Webserver Zugriffe</h3>";
		lto_printReqDetails();                                 
		print "<h3>LightOn &rsaquo; TOP CPU Prozesse</h3>"; 
		lto_printProcDetails($lt_cpu);
		print "<h3>LightOn &rsaquo; MySQL Status</h3>";
		lto_printMySql();
		lightOn_footer();
		$exectime = array_sum(explode(' ', microtime()))-$time_start;
		echo "<p>Execution Time: $exectime</p>";
	print "</div>";


?>