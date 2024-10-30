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

if (empty($lt_load)) {
    print "<small>... noch keine Eintr&auml;ge</small></div>";
	print "<hr />";
    return;
}
                                       
$charturl = "http://chart.apis.google.com/chart?";

$ltlb_system = sprintf("System&nbsp;%.0f%%",$lt_cpu[1]);
$ltlb_user = sprintf("User&nbsp;%.0f%%",$lt_cpu[2]);
$ltlb_idle = sprintf("Idle&nbsp;%.0f%%",$lt_cpu[4]); 

$charttype = "cht=p&";                      	
$charttitel = "chtt=Aktuelle&nbsp;CPU&nbsp;Nutzung&";
$chartdata = "chd=t:".$lt_cpu[1].",".$lt_cpu[2].",".$lt_cpu[4]."&";
$chartlabels = "chl=".$ltlb_system."|".$ltlb_user."|".$ltlb_idle."&";
$chartsize = "chs=400x170&";
$chartcolor = "chco=0000FF&";
$chartbgcolor = "chf=bg,s,F9F9F9";        
   

$chart1=$charturl.$charttitel.$chartsize.$charttype.$chartdata.$chartlabels.$chartcolor.$chartbgcolor;

$maxvalue = sprintf("%.0f",(max($lt_load)  + max($lt_load)/3));
$maxvalue==0?$maxvalue=0.2:false;
$load = implode(',', $lt_load);

$charttitel = "chtt=System&nbsp;Load&";
$chartsize = "chs=400x170&";
$charttype = "cht=bvs&";
$chartgrid = "chg=25,25&";
$chartdata = "chd=t:".$load."&";
$chartminmax = "chds=0,".$maxvalue."&";
$barsize = "chbh=a&";
$axis = "chxt=x,y&";
$axisdataX = "chxl=0:|1min|5min|15min|";
$axisdataY = "1:|0|".$maxvalue."&";
$datalabels = "chm=N,000000,0,-1,10&";
$chart = $charturl.$charttitel.$chartsize.$charttype.$chartgrid.$chartdata.$chartminmax.$barsize.$axis.$axisdataX.$axisdataY.$datalabels.$chartcolor.$chartbgcolor;


echo <<<EOF
	<div class='chart'>
		<img src='$chart'' alt='Chart nicht verf&uuml;gbar'/> 
		&nbsp &nbsp
    	<img src='$chart1' alt='Chart nicht verf&uuml;gbar'/> 
    </div>
	<hr />
EOF;
?>