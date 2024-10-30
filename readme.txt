=== LightOn Server Monitoring ===
Contributors: David Krcek
Tags: OS, Lighttpd, Apache, mySQL, database, server, monitor
Requires at least: 2.7
Tested up to: 2.9
Stable tag: 1.2.0

LightOn is a lightweight monitoring tool for operationg system, webserver (lighttpd & apache) and database 
New in version 1.2.0: mysql monitoring and templates for own customizing
== Description ==
                                         
LightOn is a lightweight monitoring tool for operationg system, webserver (lighttpd & apache) and database 
New in version 1.2: mysql monitoring and templates for own customizing
The supported operating systems are Linux and MAC OSX, BSD will be also supported in a later version.

Windows aka os-joke from Redmond will not be supported.

ToDo:
* Translation
* Nginx Support
* Cool Ajax stuff

== Installation ==

Unzip the lighton.1.2.0.zip at your plugin directory.
Activate the plugin through the 'Plugins' menu in WordPress

Lighttpd users have to adjust their configuration as follows.
<pre>
server.modules += ( "mod_status" )
$HTTP["remoteip"] =~ "127.0.0.1" {
    status.status-url = "/server-status"
}
</pre>

Apache users have to adjust their configuration as follows:
<pre>
LoadModule status_module libexec/apache2/mod_status.so
"Location /server-status"
    SetHandler server-status
    Order deny,allow
    Deny from all
    Allow from 127.0.0.1
"/Location"  

ExtendedStatus On 
</pre>    

For more check your lighttpd or apache documentation.
                                                         
After activation you can drag and drop the widget on your sidebar.

On the setup page you can choose your own templates for dashboard, widget and detail page.
For this upload your own templates as php-files to the templates directory and choose it.
Start your template filename with 'customer_' please. I'll will never use this prefix and
your templates are save for overwrite in later versions. 

== Frequently Asked Questions ==

= Please see <a href="http://www.switch2mac.de/2010/software/wordpress/lighton">switch2mac-lighton</a>  =

== Screenshots ==
                
<p><img src="http://www.switch2mac.de/wp-content/uploads/2010/01/screen-capture-5.jpg" width="400px"></p>
<p><img src="http://www.switch2mac.de/wp-content/uploads/2010/01/screen-capture-6.jpg" width="400px"></p> 
<p><img src="http://www.switch2mac.de/wp-content/uploads/2010/01/setup1.jpg" width="400px"></p>
<p><img src="http://www.switch2mac.de/wp-content/uploads/2010/01/widget1.jpg"></p>

== Changelog ==

= 1.2.0 =
* Adding mySQL support
* Changing to template based output, so you can change the templates like you want.
* Minor bugfixes
 
= 1.1.1 =
* Adding sidebar widget
* Fixing serverlist on setup page
* Fixing dashboard layout

= 1.1.0 =
* Adding apache2 server support

= 1.0.2 =
* Minor changes on readme.txt

= 1.0.1 =
* Minor changes on readme.txt

= 1.0 =
* Initial version.
                       
== Upgrade Notice ==
Unzip the lighton.1.2.0.zip at your plugin directory or use the automatic upgrade.