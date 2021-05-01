# nWatch
NKN Node Monitoring interface (desktop & mobile)

# Requirements 
Apache, PHP, PHPCurl 

# Install

## Hardcore mode

1. Rename nodes-examples.txt to nodes.txt
2. Delete the example lines
3. Add your nodes IPs and names in nodes.txt
4. Save the file, go check your dashboard.

## Easy mode (but still hardcore)

1. Go to your dashboard
2. Click the "Manage my nodes" button
3. Add your nodes. 

⚠️ DO NOT CREATE THE "nodes.txt" file if you follow the easy mode. 
If you already had a nodes.txt file please change it rights : 

` chown www-data:www-data /var/www/html/nodes.txt ` 

...or just copy the content, delete the file and use the button. 

![Screenshot](screenshot.png)
![Mobile](mobile_screenshot.png)
