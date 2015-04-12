TEST URL: www.navbhatti.com/noteClone

Server has "DEBUG" enabled: 
	- no emails will get sent, they are instead echoed in browser
	- user is automatically activated on registration
	- above functionality is there, but disabled for testing purposes.
	
Zipped file contents will work on local server if database script "noteclone.sql" 
is executed and unzipped folder contents placed into localhost/noteclone.
If this is not the case, check the Database constants defined at the top of "functions.php"
and change them accordingly.


----------------------------------------------------------------------
Step	Score	Implementation
----------------------------------------------------------------------
1.		5		Database will not accept non-unique email.
				Password is hashed using md5 and some salt.
				Form is validated using javascript.
----------------------------------------------------------------------
2.		5		Captcha is implemented using securimage captcha library
----------------------------------------------------------------------
3.		5		Password is reset to a unique id when requested.
				If DEBUG is defined, email body is echo'd to browser. And email is not sent
----------------------------------------------------------------------
4.		5		Database contains an "active" field for all users.
				If DEBUG is defined, email body is echo'd to browser, and user is automatically activated
----------------------------------------------------------------------
5.		5		After 3 attempts, password is reset as per requirement (3).
----------------------------------------------------------------------
6.		5		Notes are stored and retrieved
----------------------------------------------------------------------
7.		5		TBD is stored and retrieved
----------------------------------------------------------------------
8.		4.5		Hyperlinks are stored and retrieved, though click to open does not work properly (As in original site)
----------------------------------------------------------------------
9.		5		Images are stored and retrieved in the DB.
----------------------------------------------------------------------
10.		5		Notes can be edited
----------------------------------------------------------------------
11.		5		TBD can be edited
----------------------------------------------------------------------
12.		5		Hyperlinks can be edited
----------------------------------------------------------------------
13.		5		After 4 images are uploaded, upload button is not shown.
----------------------------------------------------------------------
14.		5		If extension is not .jpg or .gif, file upload is rejected
----------------------------------------------------------------------
15.		5		If checkbox is clicked when form is submitted, image is deleted
----------------------------------------------------------------------
16.		3.5		Filesystem stores images, and if user logs out properly images are deleted.
----------------------------------------------------------------------
17.		5		Logging out will kill the session and delete all temp files
----------------------------------------------------------------------
18. 	3.5		If user session is not initialized, user is redirected. Cookies are not used.
----------------------------------------------------------------------
19.		3		Session ids are not regenerated. Email and SessionID are used to identify user
----------------------------------------------------------------------
20.		5		After 20 minutes, user is logged out and served the "logout.php" page
----------------------------------------------------------------------
Total	94.5				
