# Discount Codes 5.8.0. Phoenix Pro 1.0.8.6 (full installation package)

Discount Codes 5.8.0. Addon for Phoenix Cart v1.0.8.6.+
by @raiwa
info@oscaddons.com
www.oscaddons.com

Compatibility:
Phoenix 1.0.8.6+
Tested with Phoenix 1.0.8.16
Tested with PayPal standard ipn and Stripe SCA 3.0 version stripe_sca_v1.1.0r1
PHP 7.0-8.0

Based on Discount Codes BS 3.x and 4.x by @Tsimi and @raiwa
Based on the Discount Code for osCommerce 2.3.1 addon by high-quality-php-coding.com 

## INSTALL

*Database changes will be done automatically.

1. Copy the new files to your shop, preserving the directory structure.
Folder "upload"
admin/discount_codes.php
admin/includes/languages/english/discount_codes.php
admin/includes/boxes/catalog_dc_content.php
admin/includes/languages/english/modules/boxes/catalog_dc_content.php
discount_code.php
includes/languages/english/hooks/shop/siteWide/discountCode.php
includes/languages/english/modules/order_total/ot_discount.php
includes/modules/order_total/ot_discount.php
templates/override[YOUR SELECTED TEMPLATE]/includes/hooks/shop/siteWide/discountCode.php


*OPTIONAL for the shopping cart Discount Codes Module (requires Ship in Cart Addon):
includes/languages/english/modules/content/shopping_cart/cm_sc_discount_code.php
includes/modules/content/shopping_cart/templates/tpl_cm_sc_discount_code.php
includes/modules/content/shopping_cart/cm_sc_discount_code.php


/********************************/
NOTE: Make sure you are using the "override" template and have it selected in:
Admin : Configuration : My Store : Template Selection
Otherwise you must copy:
  templates/override/includes/hooks/shop/siteWide/discountCode.php
TO:
  templates/YOUR SELECTED TEMPLATE/includes/hooks/shop/siteWide/discountCode.php
/********************************/

      
      
## SETUP/CONFIGURATION

4.1. Install the Order Total module under Administration -> Modules -> Order Total -> Install Module -> Discount Code -> Install Module.
     Set the sort order of the order total modules like in the examples below under point 5
   
*OPTIONAL if using Ship in cart add-on (required)
4.2.   Install the Content module under Administration -> Modules -> Content -> Install Module -> Discount Code[shopping_cart] -> Install Module.

5. Set the sort order for order total modules under Administration -> Modules -> Order Total.

Possibility A
Modules       Sort Order
Discount Code 15
Shipping      20
Sub-Total     10
Tax           30
Total         40

Possibility B
Modules       Sort Order
Discount Code 25
Shipping      20
Sub-Total     10
Tax           30
Total         40

Possibility C
Modules       Sort Order
Discount Code 5
Shipping      20
Sub-Total     10
Tax           30
Total         40

Possibility D
Modules       Sort Order
Discount Code 5
Shipping      10
Sub-Total     20
Tax           30
Total         40


NOTE: If Discount Code is placed before Subtotal, shipping discount will also be substracted from Subtotal.

Create a discount code under Administration -> Catalog -> Discount Codes.

Done!

NOTE: If you are using this PWA Guest Checkout add-on:
https://phoenixcart.org/forum/app.php/addons/free_addon/purchase_without_account/
Guest orders will be taken in consideration for Order Nº Discounts validation.

If you have an older version and want to upgrade please read the upgrade manual provided with this addon.
Support topic: https://phoenixcart.org/forum/app.php/addons/free_addon/discount_codes/support