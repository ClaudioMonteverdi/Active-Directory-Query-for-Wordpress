# Active-Directory-Query-for-Wordpress

Original Development Provided by:
AgaMatrix, http://www.agamatrix.com
The Atom Group http://www.theatomgroup.com/

Basic function here is to query AD users for information (which you can define by using AD specific identifiers) and return a sorted list to a wordpress template that you can assign to a page.  In our case a corporate directory that is not easily managed on our wordpress intranet.  However this has obvious other uses.

generate.php 
Connects to LDAP
parses the user data
creates a collection of people objects
sorts it by their last name
generates HTML markup
saves HTML into file "directory.html"


In order to deploy this, these two files need to be placed in wp-content/themes/simplicity/ (or other theme). 

Once that has been complete, edit the directory page in wordpress and change the template from default to "Directory Page".

The credentials for LDAP are hard-coded into generate.php, you will need to replace USER and PASSWORD, and AD details like OU and DC.
Also need to setup a cron job to execute this file nightly by simply running php path/to/generate.php.  (make sure your cron job is outputting the php in the same directory as your generate.php)
