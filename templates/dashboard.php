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
	<div class="lighton_dash">
		<table>
			<tr>
				<td><small><strong>Hostname:</strong> {$lt_result['hostname']}</small></td>
				<td><small><strong>Started:</strong> {$lt_result['started']}</small></td>
				<td><small><strong>Uptime:</strong> {$lt_result['uptime']}</small></td>
		    </tr>
			<tr>
				<th style="text-align:left"><small>Since Start</small></th>
				<th style="text-align:left"><small>Average since start</small></th>
EOF;

if ($lto_options['servertyp']==1) echo '<th style="text-align:left"><small>Average 5 sec.</small></th>';
if ($lto_options['servertyp']==2) echo '<th style="text-align:left"><small>Server Info</small></th>';

echo <<<EOF
	   		</tr>
			<tr>
				<td style="text-align:left"><small>Request: {$lt_result['absolute']['Requests']}</small></td>
				<td style="text-align:left"><small>Requests: {$lt_result['average']['Requests']}</small></td>
EOF;

if ($lto_options['servertyp']==1) echo "<td style='text-align:left'><small>Requests: {$lt_result['sliding']['Requests']}</small></td></tr>";  
if ($lto_options['servertyp']==2) echo "<td style='text-align:left'><small>Restarts: {$lt_result['generation']}</small></td></tr>";
echo <<<_EOF
           <tr>
		  		<td style="text-align:left"><small>Traffic: {$lt_result['absolute']['Traffic']}</small></td>
		   		<td style="text-align:left"><small>Traffic: {$lt_result['average']['Traffic']}</small></td>
_EOF;
if ($lto_options['servertyp']==1) echo "<td style='text-align:left'><small>Traffic: {$lt_result['sliding']['Traffic']}</small></td></tr>";
if ($lto_options['servertyp']==2) echo "<td style='text-align:left'><small>Worker: {$lt_result['prc_run']} running / {$lt_result['prc_idle']} idle</small></td></tr>";

echo <<<EOF
			<tr>
				<th style="text-align:left" colspan=3><small>CPU Load</small></th>
			</tr>
			<tr>
				<td style="text-align:left"><small>Last 1 min: $lt_load[0]</small></td>
				<td style="text-align:left"><small>Last 15 min: $lt_load[1]</small></td> 
				<td style="text-align:left"><small>Last 5 min: $lt_load[2]</small></td>
			</tr>
			<tr>
				<th style="text-align:left" colspan=3><small>CPU Usage</th>
			</tr>
EOF;
echo sprintf ("<tr><td style='text-align:left'><small>System: %.2f%% </small></td><td style='text-align:left'><small>User:%.2f%% </small></td>
			   <td style='text-align:left'><small>Idle: %.2f%% </small></td></tr></table></div>", $lt_system_proc, $lt_user_proc, $lt_idle_proc);
?>