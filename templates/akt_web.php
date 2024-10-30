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
echo <<<EOF
	<div class="lighton">
		<table>
			<tr>
				<td><strong>Hostname:</strong> {$lt_result['hostname']}</td>
				<td><strong>Started:</strong> {$lt_result['started']}</td>
				<td><strong>Uptime:</strong> {$lt_result['uptime']}</td>
			</tr>
			<tr>
				<th>Since Start</th>
				<th>Average since start</th>   
EOF;
if ($lto_options['servertyp']==1) echo '<th>Average 5 sec.</th></tr>';
if ($lto_options['servertyp']==2) echo '<th>Server Info</th></tr>';
echo <<<EOF
			<tr>
				<td>Request: {$lt_result['absolute']['Requests']}</td>
				<td>Requests:{$lt_result['average']['Requests']}</td>
EOF;
if ($lto_options['servertyp']==1) echo "<td>Requests: {$lt_result['sliding']['Requests']}</td></tr>";
if ($lto_options['servertyp']==2) echo "<td>Restarts: {$lt_result['generation']}</td></tr>";
echo <<<EOF
			<tr>
				<td>Traffic: {$lt_result['absolute']['Traffic']}</td>
				<td>Traffic: {$lt_result['average']['Traffic']}</td>
EOF;
if ($lto_options['servertyp']==1) echo "<td>Traffic: {$lt_result['sliding']['Traffic']}";
if ($lto_options['servertyp']==2) echo "<td>Worker: {$lt_result['prc_run']} running / {$lt_result['prc_idle']} idle";
echo <<<EOF
				</td>
		    </tr>
	   </table>
	</div>
EOF;
if ($lto_options['servertyp']==1) {   	
	echo <<<EOF
		<div class="lighton">
			<table style="border-top: 1px solid; border-top-color: #ddd;">
				<tr>
					<th>Client IP</th>
					<th>Read</th>
					<th>Written</th>
					<th>State</th>
					<th>Time</th>
					<th>Host</th>
					<th>URI</th>
					<th>File</th>
				</tr>
EOF;
foreach ($lt_request as $lt_reg) {
   $i++;
   $j=$i%2;
   echo '<tr>';
   for($count = 0; $count < 8; $count++) {
   	   echo "<td class='td_$j'>{$lt_reg[$count]}</td>";
   }                                        
   echo '</tr>';
}                    
echo <<<EOF
		  </table>  
	</div>  
EOF;
} elseif ($lto_options['servertyp']==2) {
echo <<<EOF
	<div class="lighton">
		<table style="border-top: 1px solid; border-top-color: #ddd;">
			<tr>
				<th>Child Nr</th>
				<th>PID</th>
				<th>Nr. Access</th>
				<th>State</th>
				<th>Time CPU</th>
				<th>KB transferd</th>
				<th>Client</th>
				<th>Virtual Host</th>
				<th>Request</th>
			</tr>
EOF;
$i=0;
foreach ($lt_request as $lt_reg) {
   $i++;
   $j=$i%2;
   echo '<tr>';
	   for($count = 0; $count < 13; $count++) {
	   		switch ($count) {
				case 5:
				case 6:
				case 8:
				case 9:
					continue;
					break;
			    default:
					echo "<td class='td_$j'>{$lt_reg[$count]}</td>";  
					break;           
			}
	   }                                        
	   echo '</tr>';     
	}
	echo '</table>';  
	echo '</div>';
}

 
echo "<hr />";
?>