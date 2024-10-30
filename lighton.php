<?php
/*
Plugin Name: LightOn
Plugin URI: http://www.switch2mac.de/2010/software/wordpress/lighton/
Description: LightOn is a lightweight monitoring tool for operating systems, webserver (Lighttpd & Apache) and mySQL database 
Version: 1.2.0
Author: David Krcek
Author URI: http://www.switch2mac.de/
Update Server: http://www.switch2mac.de/wp-content/download
Min WP Version: 2.5
Max WP Version: 2.9
*/
/* 
Copyright 2009  David Krcek  (email : admin@switch2mac.de)

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

include_once dirname(__FILE__).'/lighton-functions.php';

#### Global Variables

### Konstante für version
define('LIGHTON_VERSION', '1.2.0');
define('TEMPLATE_PATH', dirname(__FILE__).'/templates/');

########## Actions 
	
### Erstellen des Top-Level-Menus im Adminbereich
add_action('admin_menu', 'lightOn_menu');		
		
### Default-Werte der Optionen laden
add_action('init', 'lto_init_options');

### LightOn CSS
add_action('admin_head', 'lightOn_css');       

### LightOn dashboard 
add_action('wp_dashboard_setup', 'lightOn_dashboard_setup');
     
# Lighton Sidebar widget 
add_action("plugins_loaded", "lightOn_front");

#########

?>