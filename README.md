# webfolders
A php script to make nice web indexes of folders on a web server


Nginx config: (place the folders.php file in the root web folder)
```
server {
	absolute_redirect off;
	error_page   403  /folders.php;
}
```
