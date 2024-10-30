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
	<h1>LightOn</h1>
	<div class="lighton_dash">
		<table width="100%">
			<tr>
				<td colspan=3><small><strong>Hostname:</strong> {$lt_result['hostname']}</small></td>
			</tr>
			<tr>
				<td colspan=3><small><strong>Uptime:</strong> {$lt_result['uptime']}</small></td>
			</tr>
	   		<tr>
				<td colspan=3><small><strong>Started:</strong> {$lt_result['started']}</small></td>
			</tr>
			<tr>
				<th width="33%" style="text-align:left"><small>Since Start</small></th>
				<th width="34%"  style="text-align:left"><small>Avg. Start</small></th>
EOF;
if ($lto_options['servertyp']==1) echo '<th width="33%" style="text-align:left"><small>Avg. 5sec.</small></th>';
if ($lto_options['servertyp']==2) echo '<th width="33%" style="text-align:left"><small>Server Info</small></th>';
echo <<<EOF
			</tr>
			<tr>
				<td style="text-align:left"><small>Request:</small></td>
				<td style="text-align:left"><small>Requests:</small></td>
EOF;
if ($lto_options['servertyp']==1) echo '<td style="text-align:left"><small>Requests:</small></td></tr>';
if ($lto_options['servertyp']==2) echo '<td style="text-align:left"><small>Restarts:</small></td></tr>';
echo <<<EOF
			<tr>
				<td style="text-align:left"><small>{$lt_result['absolute']['Requests']}</small></td>
				<td style="text-align:left"><small>{$lt_result['average']['Requests']}</small></td>
EOF;
if ($lto_options['servertyp']==1) echo "<td style='text-align:left'><small>{$lt_result['sliding']['Requests']}</small></td></tr>";
if ($lto_options['servertyp']==2) echo "<td style='text-align:left'><small>{$lt_result['generation']}</small></td></tr>";

echo <<<EOF
			<tr>
				<td style="text-align:left"><small>Traffic:</small></td>
				<td style="text-align:left"><small>Traffic:</small></td>
EOF;
if ($lto_options['servertyp']==1) echo '<td style="text-align:left"><small>Traffic:</small></td></tr>';
if ($lto_options['servertyp']==2) echo '<td style="text-align:left"><small>Worker:</small></td></tr>';
echo <<<EOF
			<tr>
				<td style="text-align:left"><small>{$lt_result['absolute']['Traffic']}</small></td>
				<td style="text-align:left"><small>{$lt_result['average']['Traffic']}</small></td> 
EOF;
if ($lto_options['servertyp']==1) echo "<td style='text-align:left'><small>{$lt_result['sliding']['Traffic']}</small></td></tr>";
if ($lto_options['servertyp']==2) echo "<td style='text-align:left'><small>{$lt_result['prc_run']} run / {$lt_result['prc_idle']} idl</small></td></tr>";
echo <<<EOF
			<tr>
				<th style="text-align:left" colspan=3><small>CPU Load</th>
			</tr>
			<tr>
				<td style="text-align:left"><small>1 min: {$lt_load[0]}</small></td>
				<td style="text-align:left"><small>5 min: {$lt_load[1]}</small></td> 
				<td style="text-align:left"><small>15 min: {$lt_load[2]}</small></td>
			</tr>
			<tr>
				<th style="text-align:left" colspan=3><small>CPU Usage</th>
			</tr>
EOF;
echo sprintf ("<tr><td style='text-align:left'><small>Sys: %.2f%% </small></td><td style='text-align:left'><small>Usr: %.2f%% </small></td>
<td style='text-align:left'><small>Idl: %.2f%% </small></td></tr></table></div>", $lt_system_proc, $lt_user_proc, $lt_idle_proc);
?>