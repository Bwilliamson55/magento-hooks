An extension for Magento 2.4.5 to add webhook calls, with payloads, to Magento events.
New/Update/Save/Delete for Customers/Orders/Products/ etc can be configured to also ***send information about that event to a webhook.***

# Quickstart
```
git clone https://github.com/Bwilliamson55/magento-hooks.git ~/your-project-dir
cd $_
ddev config
ddev start
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

# DISCLAIMER:
This is an amalgamation of others previous open source work.
Please see [Mageplaza's webhook extension](https://docs.mageplaza.com/webhook/index.html) and any other extensions with the word "webhook" in the name.
If you are not a dev **I recommend buying this, not using mine**. I probably broke stuff. 
