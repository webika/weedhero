# weedhero
Online ordering software for cannabis dispensaries and delivery

Installation Instructions:

Requirements:
PHP 7.0 
Mysql 

Step 1: Download weedhero software from github.

Step 2: Unzip the repository (archive) and upload the folder content to a destination folder on your server where you would like to install online ordering (for example www/mydomain.com/order)

Step 3:  Open any browser and go to address where files were uploaded (For example:www.mydomain.com/order). You will see a weedhero installer page. 

Step 4.   Please insert all the information into the fields. Please fill out all the required fields. Make sure you have inserted FTP credentials to make sure that our installer will set the proper file and folder permissions. 

a.    Application Title – the title of the page where you will be able to accept online orders (For Example: San Diego Marijuana #1 Dispensary).

b.    Database username, Database password, Database name, Database host, Table prefix. Our software requires a database to store orders, items information. In your hosting account go to databases and create a database and database user. Grant all permissions for this user on this database. Enter corresponding information in fields for database name, username and password in installation wizard. Database host – usually is a localhost, but if the database is hosted somewhere else, please provide the path to it.

c.    FTP Connection – Please enter the FTP credentials to a server where files are located. FTP credentials are needed to automatically set the permissions for online ordering folders. If you prefer to manually set the permissions, do not fill out these fields and in the installation process it will show you which folders need to have 0777 permissions. In FTP Path field enter the exact path to online ordering files. For Example:  www/mydomain.com/order

d.    Weedhero online ordering Administrator. In this section you are able to create admin that will be able to login to online ordering back end and create menus, categories, items, etc. Please enter desired login name and password. After installation you can access administration by going to mydomain.com/order/admin

Step 5:    After all the information is entered, click “Install” button. If everything was entered correctly the installation process will go successfully and you will see front page of weedhero online ordering. If the installation did not succeed you will see the error messages which will help you to fix the issues.

Step 6.   Login to Admin and follow these steps:
a.	Create at least one Location
b.	Create a Menu and Categories
c.	Assign a Menu to a location
d.	Assign Category to a Menu
e.	Create a first Item and assign the item to the Menu
Our software will show only menus or categories if there at least one item is active and assigned to a category. 

To create a coupon go to Orders -> Coupon -> Create a coupon. 

You are ready to accept online orders !

