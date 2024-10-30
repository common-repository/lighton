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






# LightOn Menü
function lightOn_menu() {
	if (function_exists('add_menu_page')) {
		add_menu_page('LightOn','LightOn', 'manage_lto', 'lighton/lighton-main.php', '', plugins_url('lighton/img/lighton_small.png'));			
	}                                                                                                                  
	if (function_exists('add_submenu_page')) {
		add_submenu_page('lighton/lighton-main.php', 'LightOn Details', 'Details', 'manage_lto', 'lighton/lighton-main.php');
		add_submenu_page('lighton/lighton-main.php', 'LightOn Settings', 'Settings', 'manage_lto', 'lighton/lighton-setup.php');
	}
}

### Footer erstellen
function lightOn_footer() {
	$lto_version=LIGHTON_VERSION;
	include_once TEMPLATE_PATH.'footer.php';
}
      
### Default Werte setzen 
function lto_init_options() {
	global $wpdb;
    $lto_options=get_option('lto_options');
	if (!isset($lto_options['tpl_cpu'])) {
		delete_option('lto_options');
		$lto_options='';
	}
	
	if(empty($lto_options)) {
		$lto_options = array(
			"hostname" => "127.0.0.1",
			"statuscall" => "/server-status",
			"servertyp" => "1",
			"tpl_cpu" => 'akt_cpu.php',
			"tpl_graph" => 'akt_graph.php',
			"tpl_sqlstat" => 'akt_sqlstat.php',
			"tpl_web" => 'akt_web.php',
			"tpl_dashboard" => 'dashboard.php', 
			"tpl_sidebar_widget" => 'sidebar_widget.php'
			);
		add_option( 'lto_options', $lto_options );
   	}
    
	$role = get_role('administrator');     
	if(!$role->has_cap('manage_lto')) $role->add_cap('manage_lto');
}

function lto_getTemplates() {
	$lto_templates = array();
	if ($handle = opendir(TEMPLATE_PATH)) {
	    while (false !== ($file = readdir($handle))) {
	        if (preg_match("/.\.php$/",$file)) {
	            array_push($lto_templates, $file);
	        }
	    }
	    closedir($handle);
	}
	return $lto_templates;
}

### CPU Load
function lightOn_getLoad() {
  	$lt_load=array('N/A','N/A','N/A');
   	switch (strtolower(PHP_OS)) {
		case 'darwin':
			$lt_output = exec("uptime");
			$lt_tmp = explode(': ', $lt_output);
			$lt_loadinfo = explode(' ', $lt_tmp[1]);
			$lt_load=array();  
			array_push($lt_load,$lt_loadinfo[0],$lt_loadinfo[1],$lt_loadinfo[2]);
	        break;
		case 'linux':
			$fd = fopen("/proc/loadavg","r");
    	   	if ($fd) {
		   		$lt_loadinfo = explode("\n",fgets($fd, 1024));
				fclose($fd);	    
				$lt_load=array();
				$lt_load = explode(" ",$lt_loadinfo[0],-2);
     		}
        default:
	}
    return($lt_load);
}


### CPU Prozesse und Systemauslastung 
function lightOn_getCpu() {
   	$lt_cpu=array('N/A','N/A','N/A','N/A','N/A','N/A');
    switch (strtolower(PHP_OS)) {
		case 'darwin':
			exec('TERM=xterm top -i 1 -l 3 -n 0|grep CPU', $top, $error );                             
			if($error) break;
			
			list(,,$lt_usr,,$lt_sys,,$lt_idle)=split(' ',$top[2]);
			$lt_usr=preg_replace("/%/",'',$lt_usr);
			$lt_sys=preg_replace("/%/",'',$lt_sys);
			$lt_idle=preg_replace("/%/",'',$lt_idle);
			$lt_cpu=array();
			array_push($lt_cpu, '', $lt_sys, $lt_usr, '', $lt_idle);
			break;  
		case 'linux':
			$fd = fopen("/proc/stat","r");
    		if ($fd) {
				$lt_statinfo = explode("\n",fgets($fd, 1024));
				fclose($fd);
				foreach($lt_statinfo as $line) {
					$info = explode(" ",$line);
					if($info[0]=="cpu") {
						array_shift($info);  
						if(!$info[0]) array_shift($info);     			
						$lt_sum = $info[0] + $info[1] + $info[2] + $info[3];			
						$lt_user_proc = ( $info[0] + $info[1] ) / $lt_sum * 100;
						$lt_nice_proc = $info[1]  / $lt_sum * 100;;
						$lt_system_proc = $info[2]  / $lt_sum * 100;;
						$lt_idle_proc = $info[3] / $lt_sum * 100;
						$lt_cpu=array();                          
						array_push($lt_cpu, $lt_sum, $lt_system_proc, $lt_user_proc, $lt_nice_proc, $lt_idle_proc);                  
					}	
    			}
     		}
			break;
	    default:
			break;
    }                                                            
    exec('TERM=xterm ps acxo pid,comm,pcpu,time,pmem,ppid,stat,ruid,vsz,user',$ps,$error);
	if($error) return($lt_cpu);  
	$lt_proc=array();
	array_shift($ps);

	foreach($ps as $pline) {     
		$lt_proc_list=$line_array=array();                                  
		$line_array=explode("|",preg_replace("/\s+/","|",$pline));
		if ($line_array[0]) {
		    $i=0;$j=10;
		} else { 
		   	$i=1;$j=11;
		}   
		$k=7-(11-$j);
		switch (substr($line_array[$k],0,1)) {
			case 'S':
			case 'I':
				$line_array[$k]='SLEEP';
				break;
			case 'R':
				$line_array[$k]='RUNNING';
				break;                 
			case 'T':
				$line_array[$k]='STOPPED';
				break;                    
		   	case 'Z':	
				$line_array[$k]='ZOMBIE';
				break;                 
			case 'U':
			case 'D':
				$line_array[$k]='SLEEP/WAIT IO';
				break;
			
			default:
				$line_array[$k]='OTHER'.' '.$line_array[$k];
				break;
		}
		for($i; $i<$j;$i++) {
		 
			array_push($lt_proc_list,$line_array[$i]);
			
			
		}
		   
	   
		if ($lt_proc_list[2]>0) array_push($lt_proc,$lt_proc_list);
	}
	array_push($lt_cpu,$lt_proc);
	return($lt_cpu);
}

### Apache mod_status
function lightOn_getApache() {	
   $lto_options=get_option('lto_options');    
   $lt_result["hostname"]=$lt_result["started"]=$lt_result["uptime"]="N/A";	
   $abs['Requests']="N/A";
   $abs['Traffic']="N/A";
   $avg=$sli=$abs;

	$url="http://".$lto_options['hostname'].$lto_options['statuscall']; 
    $html=file_get_contents($url);
    
	

	if (!$html) {
		$lt_result['absolute']=$abs;
		$lt_result['average']= $avg;
		$lt_result['sliding']=$sli;
		$lt_result['request']=$lt_request;
		return($lt_result);
	}
    /*** a new dom object ***/ 
    $dom = new domDocument; 

    /*** load the html into the object ***/ 
    $dom->loadHTML($html); 

    /*** discard white space ***/ 
    $dom->preserveWhiteSpace = false; 

    /*** the table by its tag name ***/ 
    
    $lt_result["hostname"]=preg_replace("/^(.*\s)(\d+\.\d+\.\d+\.\d+|[^.]+\.[^.]+\.[^.]+)$/","$2",$dom->getElementsByTagName('h1')->item(0)->nodeValue);

	$rows = $dom->getElementsByTagName('dt'); 
    
    foreach ($rows as $row) {                                           
	
   		if (preg_match("/Restart Time/",$row->nodeValue)) {
	    	$lt_result["started"]=strftime("%x %H:%M", strtotime(preg_replace("/^(.+ Time\:)(.+)$/","$2",$row->nodeValue)));
		}
		if (preg_match("/Server uptime/",$row->nodeValue)) {
	    	$uptime=preg_replace("/^(.+ uptime\:)(.+)\s\d+\s.+$/","$2",$row->nodeValue);
			$uptime = preg_replace("/hours/","h",$uptime);
			$uptime = preg_replace("/minutes/","m",$uptime); 
			$uptime = preg_replace("/day|days/","d",$uptime); 
			$lt_result["uptime"] = $uptime;
		}
		
		if (preg_match("/Total accesses/",$row->nodeValue)) {      	
			$abs['Requests']=preg_replace("/^(.+\:)(.+)(-.+)/","$2",$row->nodeValue);
			$abs['Traffic']=preg_replace("/(.+):(.+)$/","$2",$row->nodeValue);			
  		}
		
		if (preg_match("/requests\/sec/",$row->nodeValue)) {      	
			$avg['Requests']=preg_replace("/^(\.\d+|d+)(\s.{3})(.+)(\/.{1})(.+)(\-.+\-.+)/","$1$2$4",$row->nodeValue);
			$avg['Traffic']=preg_replace("/(.+\s-\s)(\d+)(\s.+\/.{3})(.+)$/","$2$3",$row->nodeValue);			
  		}

		if (preg_match("/Server Generation/",$row->nodeValue)) {      	
			$lt_result['generation']=preg_replace("/(.+\:\s)(\d+)/","$2",$row->nodeValue);
		}                                                      
		
		if (preg_match("/workers/",$row->nodeValue)) {
			$lt_result['prc_run']=preg_replace("/(\d+)(\s.+)/","$1",$row->nodeValue);			
			$lt_result['prc_idle']=preg_replace("/(.+\,\s)(\d+)(\s.+)/","$2",$row->nodeValue);			
		}
   	}
   

   	$tables = $dom->getElementsByTagName('table'); 
   	$rows = $tables->item(0)->getElementsByTagName('tr');
   	$lt_request=array(); 	                     
	
   
   /*	"_" Waiting for Connection, "S" Starting up, "R" Reading Request,
	   "W" Sending Reply, "K" KeepAlive (read), "D" DNS Lookup, "L" Logging,
	   "G" Gracefully finishing, "." Open slot with no current process */
	
	foreach ($rows as $row) {  
   		if (!empty($row->getElementsByTagName('td')->item(0)->nodeValue)) {
	    	$lt_req=array(); 
		   	for($count = 0; $count < 13; $count++) {
				
				if ($count == 3) {
				 		if (preg_match("/_/",$row->getElementsByTagName('td')->item($count)->nodeValue)) {
					    	$lt_req[3]="Waiting";
							continue;
						}
						if (preg_match("/S/",$row->getElementsByTagName('td')->item($count)->nodeValue)) {
					    	$lt_req[3]="Starting";
							continue;
						}
						if (preg_match("/R/",$row->getElementsByTagName('td')->item($count)->nodeValue)) {
					    	$lt_req[3]="Reading";
							continue;
						}                 
						if (preg_match("/W/",$row->getElementsByTagName('td')->item($count)->nodeValue)) {
					    	$lt_req[3]="Sending";
							continue;
						}
						if (preg_match("/K/",$row->getElementsByTagName('td')->item($count)->nodeValue)) {
					    	$lt_req[3]="KeepAlive";
							continue;
						}
						if (preg_match("/D/",$row->getElementsByTagName('td')->item($count)->nodeValue)) {
					    	$lt_req[3]="DNSLook";
							continue;
						}
						if (preg_match("/L/",$row->getElementsByTagName('td')->item($count)->nodeValue)) {
					    	$lt_req[3]="Logging";
							continue;
						}               
						if (preg_match("/G/",$row->getElementsByTagName('td')->item($count)->nodeValue)) {
					    	$lt_req[3]="Finished";
							continue;
						}
						if (preg_match("/G/",$row->getElementsByTagName('td')->item($count)->nodeValue)) {
					    	$lt_req[3]="Closing";
							continue;
						}
						if (preg_match("/\./",$row->getElementsByTagName('td')->item($count)->nodeValue)) {
					    	$lt_req[3]="Bored";
							continue;
						}
				}
				if ($count == 12) {
					array_push($lt_req, preg_replace("/(GET\s)(.+)(HTTP.+)/","$2",$row->getElementsByTagName('td')->item($count)->nodeValue));
					continue;
				}				                                                                         	
				array_push($lt_req, preg_replace("/\(.*\)|::ffff:/","",$row->getElementsByTagName('td')->item($count)->nodeValue));				
			}
           	!empty($lt_req)?array_push($lt_request, $lt_req ):false;
		}
   	}
	
	
	
	$lt_result['absolute']=$abs;
	$lt_result['average']= $avg;
	$lt_result['sliding']=$sli;
	$lt_result['request']=$lt_request;
	return($lt_result);
}

### Lighttpd mod_status
function lightOn_getLighty() {	
   $lto_options=get_option('lto_options');    
   $lt_result["hostname"]=$lt_result["started"]=$lt_result["uptime"]="N/A";	
   $abs['Requests']="N/A";
   $abs['Traffic']="N/A";
   $avg=$sli=$abs;

	$url="http://".$lto_options['hostname'].$lto_options['statuscall']; 
    $html=file_get_contents($url);
    
	if (!$html) {
		$lt_result['absolute']=$abs;
		$lt_result['average']= $avg;
		$lt_result['sliding']=$sli;
		$lt_result['request']=$lt_request;
		return($lt_result);
	}
    /*** a new dom object ***/ 
    $dom = new domDocument; 

    /*** load the html into the object ***/ 
    $dom->loadHTML($html); 

    /*** discard white space ***/ 
    $dom->preserveWhiteSpace = false; 

    /*** the table by its tag name ***/ 
    $tables = $dom->getElementsByTagName('table'); 
    /*** get all rows from the table ***/ 
    $rows = $tables->item(0)->getElementsByTagName('tr'); 
    
    /*** loop over the table rows ***/ 
   	
    foreach ($rows as $row) {                                           
		if (!empty($row->getElementsByTagName('th')->item(0)->nodeValue)) {
			$element = $row->getElementsByTagName('th')->item(0)->nodeValue;
		}

	if (preg_match("/absolute/",$element) && !empty($row->getElementsByTagName('td')->item(0)->nodeValue)) {
		$abs[$row->getElementsByTagName('td')->item(0)->nodeValue] = $row->getElementsByTagName('td')->item(1)->nodeValue;
	 	continue;
	}
	if (preg_match("/average/",$element) && !empty($row->getElementsByTagName('td')->item(0)->nodeValue)) {
		if (preg_match("/start/",$element)) {
			$avg[$row->getElementsByTagName('td')->item(0)->nodeValue] = $row->getElementsByTagName('td')->item(1)->nodeValue;
			continue;
		}	
	}
	if (preg_match("/sliding/",$element) && !empty($row->getElementsByTagName('td')->item(0)->nodeValue)) {
		$sli[$row->getElementsByTagName('td')->item(0)->nodeValue] = $row->getElementsByTagName('td')->item(1)->nodeValue;
		continue;
		}
	
	if (preg_match("/Hostname/",$row->getElementsByTagName('td')->item(0)->nodeValue)) {
		$lt_result["hostname"] = preg_replace("/\(\)/","",$row->getElementsByTagName('td')->item(1)->nodeValue);
	}

	if (preg_match("/Uptime/",$row->getElementsByTagName('td')->item(0)->nodeValue)) {
		$uptime = preg_replace("/\d\d s$/","",$row->getElementsByTagName('td')->item(1)->nodeValue);
		$uptime = preg_replace("/hours/","h",$uptime);
		$uptime = preg_replace("/min/","m",$uptime); 
		$uptime = preg_replace("/day|days/","d",$uptime); 
		$lt_result["uptime"] = $uptime;
	}	

	if (preg_match("/Started/",$row->getElementsByTagName('td')->item(0)->nodeValue)) {
		$lt_result["started"] = strftime("%x %H:%M", strtotime($row->getElementsByTagName('td')->item(1)->nodeValue));
	}
	
	}
	$tables = $dom->getElementsByTagName('table'); 
    /*** get all rows from the table ***/ 
    $rows = $tables->item(1)->getElementsByTagName('tr');
    $lt_request=array(); 	
	foreach ($rows as $row) {  
		if (!empty($row->getElementsByTagName('td')->item(0)->nodeValue)) {
		   $lt_req=array(); 
		   for($count = 0; $count < 8; $count++) {
				array_push($lt_req, preg_replace("/\(.*\)|::ffff:/","",$row->getElementsByTagName('td')->item($count)->nodeValue));
           }
           !empty($lt_req)?array_push($lt_request, $lt_req ):false;
		}
    }    
	$lt_result['absolute']=$abs;
	$lt_result['average']= $avg;
	$lt_result['sliding']=$sli;
	$lt_result['request']=$lt_request;
	return($lt_result);
}

### Widget für die lightOn dashboard 
function lightOn_dashboard_setup() {
	wp_add_dashboard_widget( 'lightOn_dashboard', 'LightOn', 'lightOn_dashboard' );
}          

### CSS für LightOn laden
function lightOn_css() {
        $lto_css_link=get_option('siteurl').'/wp-content/plugins/lighton/lighton.css';
		include_once TEMPLATE_PATH.'css.php';
}


### Konvertierung Sekunden nach Tag, Stunde, Minute, Sekunde
function secondsToWords($seconds) {
	$ret = "";
    $days = intval($seconds / 3600 / 24);
	if ($days > 0) $ret .= "$days ds ";
	$seconds = $seconds - ( $days * 24 * 3600);
	$hours = bcmod((intval($seconds / 3600)),3600);
	if($hours > 0) $ret .= "$hours hr ";
    $minutes = bcmod((intval($seconds / 60)),60);
    if($hours > 0 || $minutes > 0) $ret .= "$minutes min "; 
    $seconds = bcmod(intval($seconds),60);
    $ret .= "$seconds sec.";
    return $ret;
}


### Sidebar widget Registrierung
function lightOn_front(){
	register_sidebar_widget("LightOn", "lighton_widget");     
}

# Dashboard Anzeige
function lightOn_dashboard() {
	$lto_options=get_option('lto_options');
	if ($lto_options['servertyp']==1) {
		$lt_result=lightOn_getLighty();
	} elseif ($lto_options['servertyp']==2) {
		$lt_result=lightOn_getApache();		
	}
	$lt_load=lightOn_getLoad();
	list(,$lt_system_proc,$lt_user_proc,,$lt_idle_proc) = lightOn_getCpu();
	include_once TEMPLATE_PATH.$lto_options["tpl_dashboard"];
}


### Sidebar Widget
function lighton_widget() {
	$lto_options=get_option('lto_options');
	if ($lto_options['servertyp']==1) {
		$lt_result=lightOn_getLighty();
	} elseif ($lto_options['servertyp']==2) {
		$lt_result=lightOn_getApache();		
	}
	$lt_load=lightOn_getLoad();
	list(,$lt_system_proc,$lt_user_proc,,$lt_idle_proc) = lightOn_getCpu();       
	include_once TEMPLATE_PATH.$lto_options["tpl_sidebar_widget"];
}

?>
