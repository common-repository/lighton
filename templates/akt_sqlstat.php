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
				<td><strong>DB Server:</strong> $lto_sql_hostname</td>
				<td><strong>Version:</strong> $lto_sql_version</td>
				<td><strong>Uptime:</strong> $lto_sql_uptime</td>
			</tr>
			<tr>
				<td><strong>Threads:</strong> {$lto_sql_status['Threads_connected']}</td>
				<td><strong>Max. Connections:</strong> {$lto_sql_status['Max_used_connections']}</td>
				<td><strong>Open tables:</strong> {$lto_sql_status['Open_tables']}</td>
			</tr>
			<tr>
				<td><strong>Queries:</strong> {$lto_sql_status['Queries']}</td>
				<td><strong>Locks (waited):</strong> {$lto_sql_status['Table_locks_waited']}</td>
				<td><strong>Slow queries:</strong> {$lto_sql_status['Slow_queries']}</td>
			</tr>
        </table>
    </div>
	<div class="lighton">
		<table style="border-top: 1px solid; border-top-color: #ddd;">
			<tr>
			  	<th>Id</th>
				<th>User</th>
				<th>Host</th>
				<th>Database</th>
				<th>Time</th>
				<th>State</th>
				<th>Command</th>
				<th>Info</th>
		    </tr>
EOF;
$i=0;
while ($row = mysql_fetch_assoc($lto_sql_list))
{
	$i++;
	$j=$i%2;	
	echo <<<EOF
			<tr>
				<td class='td_$j'>{$row["Id"]}</td>
				<td class='td_$j'>{$row["User"]}</td>
				<td class='td_$j'>{$row["Host"]}</td>
				<td class='td_$j'>{$row["db"]}</td>
				<td class='td_$j'>{$row["Time"]}</td>
				<td class='td_$j'>{$row["State"]}</td>
				<td class='td_$j'>{$row["Command"]}</td>
				<td class='td_$j'>{$row["Info"]}</td>
			</tr>
EOF;
}
echo '</table>';
echo '<hr>';
?>