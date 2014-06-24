=== Plugin Name ===
Contributors: teixeira@broadinstitute.org, clifgriffin
Tags: Crowd, authentication, login
Requires at least: 2.8
Tested up to: 3.2.1
Stable tag: 0.1

Integrating Wordpress with Crowd.  This is basically a rewrite of the "Simple LDAP Login" plugin for Wordpress with all the LDAP logic removed and replaced with calls to a Crowd authentication library instead.  The basic functionality of the plugin was written by clifgriffin (http://clifgriffin.com) with the Crowd stuff implanted by me.  Also, the Crowd client library was written by Infinite Campus, Inc. (http://www.infinitecampus.com/).

== Description ==
Having a single login for every service is a must in large organizations. This plugin allows you to integrate Wordpress with Crowd quickly and easily.

**Support**

= Features =

* Supports Atlassian Crowd authentication
* Includes three login modes:
* * Normal Mode: Authenticates existing Wordpress usernames against Crowd. This requires you to create all Wordpress accounts manually using the same user names as those in your Crowd directory.
* * Account Creation Mode 1: Creates Wordpress accounts automatically for any Crowd user.
* * Account Creation Mode 2: Creates Wordpress accounts automatically for Crowd users in a specific Group you specify.
* * Account Creation Mode 3: Creates Wordpress accounts automatically for Crowd users in groups you specify, you have to map these groups onto Wordpress roles. If user is in Crowd group A, B and mapping from B Crowd group is to role which has more capabilities like **Administrator**, than role which is mapped from Crowd group A, like **Editor**, then the user will have the role with more capabilities (**Administrator**).
* Intuitive control panel.

= Architecture =
Crowd Login adds an authentication filter to Wordpress that authentication requests must pass. In doing so, it makes several decisions.

* Can the provided credentials be authenticated against Crowd?
* * If so, is the username a valid WP username?
* * * If not, can we create a WP user?
* * * * If we can, does the user belong to the right (if any) group?
* * * * * If the user does, create the Wordpress user and log the user in.
* * If the username is already valid wordpress user, are they in the right group?
* * * If so, log the user in.

This is simply a high level overview. The actual logic the plugin employs is more complex, but hopefully this gives you an idea, philosophically, about how the plugin accomplishes what it does. If the plugin is unable to authenticate the user, it passes it down the chain to Wordpress. (Unless security mode is set to high, which will disable this functionality.)

== Changelog ==
**Version 0.1**
* Original release.

== Installation ==

1. Create an application in Crowd as you will need the application name,
   password, and URL to the Crowd server to setup this plugin.  Also, make
   sure that you add the correct IP address of the web server where this
   WordPress installation is installed in your Crowd application so that
   Crowd will allow authentication requests from your WordPress installation.
2. Use the WordPress plugin directory to install the plugin or upload the
   directory "crowd-login" to the `/wp-content/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Immediately update the settings to those that best match your environment
   by going to Settings -> Crowd Login
5. If you don't get the settings right the first time, don't fret! Just use
   your Wordpress credentials, they will always work in low security mode.
6. Once you have the settings correct, you can change the security mode to
   High Security (if you so desire).
7. To make your life easier, consider using two different browsers (e.g., IE
   and Firefox) to do testing.  Change settings in one. Test in the other.
   This will prevent any chance of being locked out.

== Frequently Asked Questions ==

== Screenshots ==

1. Easy to use admin panel. 
