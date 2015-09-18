<?php

/**
 * Add a list of related products to a WHMCS shopping cart page's sidebar.
 *
 * Define a (very very very) simple list of strings describing products related
 * to what a user has in their WHMCS shopping cart based on the product type.
 * WHMCS's PreCalculateCartTotals hook point has a list of the products in the
 * cart. Query each product using the WHMCS\Product\Product class, and use the
 * product object's type property to find which related products to load.
 *
 * Load each recommended product as children for a new menu item. This menu item
 * is displayed as a panel on the sidebar. Use the ClientAreaSecondarySidebar
 * hook point to load the new panel into the sidebar.
 *
 * Note: This was a demo written live during a talk Nate Custer and I gave at
 * cPanel Conference 2015 about WHMCS module customization. A segment of the
 * talk was a live coding session. One of the audience members requested this
 * feature, and by gum I was able to get it done in 30 minutes. I apologize in
 * advance for the simplicity and dirtiness. If this were running in production
 * it would probably include cool things like related product ids, links to the
 * related products, helpful icons, and the like, but I'll take simple strings
 * given the time constraint. :)
 *
 * Code was edited and comments were added later for readability prior to
 * publishing.
 *
 * @author Kevin Laude <kevin@whmcs.com>
 * @license MIT <http://opensource.org/licenses/MIT>
 * @see http://docs.whmcs.com/classes/classes/WHMCS.Product.Product.html
 * @see http://docs.whmcs.com/Hooks:PreCalculateCartTotals
 * @see http://docs.whmcs.com/Hooks:ClientAreaSecondarySidebar
 * @see http://docs.whmcs.com/Editing_Client_Area_Menus
 * @see https://github.com/n8whnp/cPconf-2015
 */

use WHMCS\Product\Product;
use WHMCS\View\Menu\Item as MenuItem;
use WHMCS\View\Menu\MenuFactory;

/**
 * A (very very very) simple list of recommended products based off of product
 * types.
 *
 * @var array
 */
$recommendedProducts = [
    'hostingaccount' => [
        'Try out our cool cPanel thinger!',
        'Buy our SSL certs!',
    ],
    'reselleraccount' => [
        'Goodness gracious the paper!',
        'Where the cash at?',
        'Where the stash at?',
    ],
    'server' => [
        'Buy more bandwidth!',
        'Buy more hard drives!',
        'Buy more RAM!',
    ],
    'other' => [
        'Other is really a pretty big category',
        'How do I recommend anything for this?',
        'I mean damn',
    ],
];

/**
 * The panel on the checkout page that contains recommended products.
 *
 * @var MenuItem
 */
$recommendedProductsPanel = null;

// Build a sidebar panel with related product items on the cart checkout page.
add_hook(
    'PreCalculateCartTotals',
    1,
    function (array $parameters) use ($recommendedProducts, &$recommendedProductsPanel)
    {
        // Don't build a panel if there's nothing in the cart (because there's
        // nothing to recommend!)
        if (count($parameters['products']) == 0) {
            return;
        }

        $recommendedProductsPanel = new MenuItem('Related Products', new MenuFactory);

        // Look through the products in the cart and instantiate a new Product
        // object based off the product's id.
        foreach ($parameters['products'] as $product) {
            $product = Product::find($product['pid']);

            // Add the product type's related products to the new panel.
            foreach ($recommendedProducts[$product->type] as $recommendedProduct) {
                $recommendedProductsPanel->addChild($recommendedProduct);
            }
        }
    }
);

// Assign the new panel to the secondary sidebar.
add_hook(
    'ClientAreaSecondarySidebar',
    1,
    function (MenuItem $secondarySidebar) use (&$recommendedProductsPanel)
    {
        // $myPanel is only defined on the cart checkout page, so if it's null
        // then the user is on another page and we shouldn't edit the sidebar.
        if (is_null($recommendedProductsPanel)) {
            return;
        }

        $secondarySidebar->addChild($recommendedProductsPanel);
    }
);
