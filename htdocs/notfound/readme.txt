In order to automatically redirect 404 and 500 pages to the NotFound page, you need to add these lines into you .htaccess file :

ErrorDocument 404 http://yoursite.com/notfound/
ErrorDocument 500 http://yoursite.com/notfound/