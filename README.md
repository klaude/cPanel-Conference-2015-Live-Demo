# Add a list of related products to a WHMCS shopping cart page's sidebar.

Define a (very very very) simple list of strings describing products related to what a user has in their WHMCS shopping cart based on the product type.
WHMCS's `PreCalculateCartTotals` hook point has a list of the products in the cart. Query each product using the `WHMCS\Product\Product` class, and use the product object's type property to find which related products to load.

Load each recommended product as children for a new menu item. This menu item is displayed as a panel on the sidebar. Use the `ClientAreaSecondarySidebar` hook point to load the new panel into the sidebar.

*Note: This was a demo written live during a talk [Nate Custer](https://github.com/n8whnp) and I gave at cPanel Conference 2015 about WHMCS module customization. A segment of the talk was a live coding session. One of the audience members requested this feature, and by gum I was able to get it done in 30 minutes. I apologize in advance for the simplicity and dirtiness. If this were running in production it would probably include cool things like related product ids, links to the related products, helpful icons, and the like, but I'll take simple strings given the time constraint. :)*

*Note: This code was written against WHMCS 6.1 release candidate 1. There's a known issue in the release candidate that prevented programmatic sidebars from loading on order forms. That's fixed in the WHMCS 6.1 general release.*

## Related Links

* http://docs.whmcs.com/classes/classes/WHMCS.Product.Product.html - WHMCS's `WHMCS\Product\Product` class
* http://docs.whmcs.com/Hooks:PreCalculateCartTotals - The `PreCalculateCartTotals` hook point
* http://docs.whmcs.com/Hooks:ClientAreaSecondarySidebar - The `ClientAreaSecondarySidebar` hook point
* http://docs.whmcs.com/Editing_Client_Area_Menus - General information on editing WHMCS menu items
* https://github.com/n8whnp/cPconf-2015 - The code demonstrated during Nate's speaking portion of our presentation
