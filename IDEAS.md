Ideas on how we'll solve future problems.

Installation
------------
We'll provide a simple way to install the application once downloaded, from the command line.

e.g. php artisan getcandy:install

It will ask if it's OK to track some annonymous stats and also whether they want to set-up a site license, for adding
plugins etc down the line. We could also potentially set-up an optional GetCandy Marketplace account at this point,
with a password.


Updating the main application
-----------------------------
Artisan command to update, e.g. php artisan getcandy:update

Will download a remote changes.json which it will use to download the required updates.
Then run the database migrations.

It should confirm via the CLI that the user has taken a backup of the database and preferably, is performing update on
a new Git branch. And of course not running it in production environment.


Plugins
-------
Plugins are put in a plugins/ folders with their own individual service providers, which will be registered
in config/plugins.php

Plugin configuration will reside in the plugin's own folder.

Plugins will be installed either manually, for private plugins (or those not on the marketplace - tut tut!), or via the
command line from the marketplace.

e.g. php artisan getcandy:plugin:install reviews

Then the user will be prompted to add the plugin's server provider to config/plugins.php

The system will send the the site's license key to the marketplace when requesting the download.

Plugins installed via the GetCandy Marketplace will be updated via the command line,
e.g. php artisan getcandy:plugin:update reviews

Other example commands...

    php artisan getcandy:plugin:update-all
    php artisan getcandy:plugin:remove reviews

To disable a plugin, you'll simply remove (or comment it out) in config/plugins.php

Site Licenses will be generated automatically by giving the site a name, e.g. URL and an administrator's email. This
will be used to add it to a GetCandy Marketplace account and also to provide security alerts by email if/when
they happen.

