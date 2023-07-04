An extension for Magento 2.4.5 to add webhook calls, with payloads, to Magento events.
New/Update/Save/Delete for Customers/Orders/Products/ etc can be configured to also ***send information about that event to a webhook.***

# Quickstart
```shell
git clone https://github.com/Bwilliamson55/magento-hooks.git ~/your-project-dir/app/code/Bwilliamson
```
Install:
```
bin/magento setup:upgrade && bin/magento setup:di:compile
```
followed by 
```
bin/magento c:f
```
(Cache flush)