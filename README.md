An extension for Magento 2.4.5 to add webhook calls, with payloads, to Magento events.
New/Update/Save/Delete for Customers/Orders/Products/ etc can be configured to also ***send information about that event to a webhook.***

# Quickstart
Install docker or colima: [Guide](https://ddev.readthedocs.io/en/stable/users/install/docker-installation/)

Install DDEV: [Guide](https://ddev.readthedocs.io/en/stable/users/install/ddev-installation/)

If you're on mac or windows - don't forget mutagen! - It's outlined in the guides above and helps with I/O.
```
git clone https://github.com/Bwilliamson55/magento-hooks.git ~/your-project-dir
cd $_

ddev config --project-type=magento2 --php-version=8.1 --docroot=pub --create-docroot --disable-settings-management
# Select magento2 as project type

ddev start
# All `ddev` commands can be used normally from the cli after running `ddev ssh` to enter the web container 
ddev composer install
# Get your adobe account keys ready 
ddev magento setup:install --base-url='https://ddev-magento2.ddev.site/' --cleanup-database --db-host=db --db-name=db --db-user=db --db-password=db --elasticsearch-host=elasticsearch --admin-firstname=Admin --admin-lastname=Lastname --admin-email=admin@admin.com --admin-user=admin --admin-password=Password1! --language=en_US
# Note the admin URI returned
ddev magento deploy:mode:set developer
ddev magento module:disable Magento_TwoFactorAuth
ddev magento setup:di:compile
ddev config --disable-settings-management=false
ddev describe
```
If the project gives you trouble you can follow the DDEV quickstart guide here for a from-scratch project: https://ddev.readthedocs.io/en/latest/users/quickstart/#magento-2

Or, cut the app/code folder out and put it in your existing project and run 
```
bin/magento setup:upgrade && bin/magento setup:di:compile
```
followed by 
```
bin/magento c:f
```
(Cache flush)

If you lose your admin URI, it can be found in `app > etc > env.php` 

# DISCLAIMER:
This is an amalgamation of others previous open source work.
Please see [Mageplaza's webhook extension](https://docs.mageplaza.com/webhook/index.html) and any other extensions with the word "webhook" in the name.
If you are not a dev **I recommend buying this, not using mine**. I probably broke stuff. 
