![HenTie Logo](http://i.imgur.com/acmngin.png)

Hen Tie Browser
===============

A web server file browser / media manager / index viewer.

Made using PHP, highly customizable and usable within a PHP website or included in a static page using AJAX on client-side.

Quick Index
-----------

```shell
cd /directory/to/index
wget "http://www.fmwconcepts.com/imagemagick/downloadcounter.php?scriptname=squareup&dirname=squareup" -O squareup.sh
bash ./generate_thumbnails .
wget https://raw.githubusercontent.com/db0company/HenTie/master/browser.php -O index.php
```

Documentation
-------------

Requires a web server (Apache, Nginx) that supports PHP5+.

If you index images, use the `generate_thumbnail.sh` script to, well... generate the thumbnails.
For better results, download the ImageMagick `squareup.sh` script (see [Quick Index](#quick-index)).

Edit the first part of the file according to the comments to fit your desired configuration:
- Authentication page
- Custom logo, title, icons
- Restrict files extensions and hidden files
- Display as a grid or as a list
- And more!

![Hen Tie File Browser](http://i.imgur.com/haifeLB.png)

Grid style, when activated:
![Hen Tie Grid style](http://i.imgur.com/WMQnU7O.png)

Authentication page, when activated:

![Hen Tie File Browser Authentication](http://i.imgur.com/k2pHIs2.png)

Include the browser inside a page
---------------------------------

```html
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Hen Tie Browser, Inclusion Test Page</title>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
  </head>
  <body>
    <h1>Hen Tie Browser, Inclusion Test Page</h1>

    <p>Server-side, using PHP:</p>
    <?php include_once('browser.php'); ?>

    <p>Client-side, using JQuery:</p>
    <div id="browser"></div>

    <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script>
      $.ajax('browser.php').done(function(html) {
        $('#browser').html(html);
      });
    </script>
  </body>
</html>
```

Copyright/License
=================

    Copyright 2014 Deby Barbara Lepage <db0company@gmail.com>
   
    Licensed under the Apache License, Version 2.0 (the "License");
    you may not use this file except in compliance with the License.
    You may obtain a copy of the License at
   
        http://www.apache.org/licenses/LICENSE-2.0
   
    Unless required by applicable law or agreed to in writing, software
    distributed under the License is distributed on an "AS IS" BASIS,
    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
    See the License for the specific language governing permissions and
    limitations under the License.    


Icons Copyright
---------------

- Chicken by Ana María Lora Macias from The Noun Project
- Tie by Michela Tannoia from The Noun Project
- File by Julien Deveaux from The Noun Project
- Folder by Julien Deveaux from The Noun Project
- Left by im icons from The Noun Project
   
Up to date
----------

 /!\ Latest version is on GitHub :
* https://github.com/db0company/HenTie
