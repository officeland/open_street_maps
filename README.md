OpenStreetMap Maps plugin for Osclass
=====================================

osm_maps plugin for osclass.org Open Source Classified

This plugin shows OpenStreetMap map for each item.

Mapquest introduced a free API key starting from September 15, 2015.

Register and get your free API key at:

    https://developer.mapquest.com/

It takes a minute or so. Then add your MapQuest's PUBLIC API key in the file:

    \oc-content\plugins\osm_maps\index.php

in this line:

    const MAPQUEST_API_KEY ='you_public_key_here';

it should look like this:

    MAPQUEST_API_KEY ='Mf0UOnA76bfMm6Gzpqj8dFFBMGxP7KhY';

It requires activated:

    allow_url_fopen = On

in the server php.ini file or cPanel's PHP configuration (it usually requires server restart if you are on VPS/Dedicated systems),

as it uses simplexml_load_file PHP function.

It can be checked if it is activated via standard phpinfo(); call.

If it is not activated, and if you have no access to php.ini file on the server, try to put: 

    php_value allow_url_fopen On 

into .htaccess file in the root folder of the server.

Google Maps plugin should be deactivated before installation (to avoid js conflicts).

If a house number is not mapped on the OpenStreetMap, one can add it on the map himself, as it is a wiki-style map (see www.osm.org for details).

The plugin uses MapQuest Open nominatim free API. Please, read usage policy: http://developer.mapquest.com/web/products/open/nominatim

The license of the OpenStreetMap allows to use it for free for commercial projects, even with high traffic volumes. However, keep in mind MapQuest's GeoCoding API limits for free accounts!

OSM debug
=========

Plugin has a debug function (e.g. you can see what data plugin retrieves for specific item page).
For security reasons, admin must be logged-in @ oc-admin to see actual debug code on item page load.

In order to use debug feature, uncomment (remove // symbols) in front of this line in plugin's index.php file:

```
// osc_add_hook('footer', 'osm_debug');
```

Alternatively, if you wish to use manual code embedding, place this code in your theme's footer.php file at the end:

```
<?php
if (osc_is_admin_user_logged_in()) {
    if (osc_is_ad_page()) {
        osm_debug(osc_item());
    }
}
?>
```

In case of problems or errors, you will most probably see an empty response for [place] or 0.00 / 0.00 for latitude and longitude values.
Under normal response, you should see all the geo data, like address, street, lat/lon etc.

