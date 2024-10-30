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
			<th>PID</th>
			<th>Kommando</th>
			<th>%-CPU</th>
			<th>Zeit</th>
			<th>Speicher</th>
			<th>PPID</th>
			<th>Zustand</th>
			<th>UID</th>
			<th>Page In</th>
			<th>User</th>
	   	</tr>
EOF;
foreach ($lt_proc_list as $lt_reg) {
	   $i++;
	   $k=$i%2;
	   echo '<tr>';
		   for($count = 0; $count < 10; $count++) {
		   		echo "<td class='td_$k'>{$lt_reg[$count]}</td>";
		   }                                        
	   echo '</tr>';
	}
echo <<<EOF
		</table>  
	</div> 
	<hr />
EOF;

?>