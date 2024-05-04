<?php
/*
  Discount Code 5.8.3
  by @raiwa
  raiwa@phoenixcartaddons.com
  www.phoenixcartaddons.com

  Based on Discount Codes BS 3.x and 4.x by @Tsimi and @raiwa
  Based on the Discount Code for osCommerce 2.3.1 addon by high-quality-php-coding.com

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

 const HEADING_TITLE = 'Discount Codes';

 const TABLE_HEADING_DISCOUNT_CODE = 'Discount Code';
 const TABLE_HEADING_DESCRIPTION = 'Description';
 const TABLE_HEADING_APPLIES_TO = 'Applies to';
 const TABLE_HEADING_DISCOUNT = 'Discount';
 const TABLE_HEADING_MINIMUM_ORDER_AMOUNT = 'Min Sub-Total<br><small>Minimum Order Sub-Total</small>';
 const TABLE_HEADING_MINIMUM_ORDER_AMOUNT_FULL = 'Minimum Order Sub-Total';
 const TABLE_HEADING_EXPIRY = 'Expiry';
 const TABLE_HEADING_NUMBER_OF_ORDERS = 'Orders';
 const TABLE_HEADING_NUMBER_OF_ORDERS_FULL = 'Number of Orders';
 const TABLE_HEADING_ORDER_INFO = 'Order Info';
 const TABLE_HEADING_ORDER_INFO_FULL = 'Include discount code in the order info';
 const TABLE_HEADING_NUMBER_OF_USE = 'Number of Use:';
 const TABLE_HEADING_NUMBER_OF_PRODUCTS = 'Number of products to apply the discount:';
 const TABLE_HEADING_STATUS = 'Status';
 const TABLE_HEADING_ACTION = 'Action';

 const TEXT_DISCOUNT_CODE = 'Discount Code:';
 const TEXT_DISCOUNT_DESCRIPTION = 'Discount Description:<br><small>For internal use in Admin only</small>';
 const TEXT_APPLIES_TO = 'Applies to:';
 const TEXT_DISCOUNT = 'Discount:';
 const TEXT_DISCOUNT_EXPL = 'You can enter a fixed or percentage discount amount, for example: <strong>5</strong> or <strong>10%</strong><br>Shipping discounts can only be set as percentage. Type <strong>100%</strong> for free shipping.';
 const TEXT_MINIMUM_ORDER_SUB_TOTAL = 'Minimum Order Sub-Total:';
 const TEXT_NUMBER_OF_USE = 'Number of Use:<br><small>Empty is unlimited.<br>If used together with customers, per customer, otherwise total use.</small>';
 const TEXT_NUMBER_OF_PRODUCTS = 'Number of products to apply the discount:<br><small>For one order, empty is unlimited</small>';
 const TEXT_EXPIRY = 'Expiry Date:<br><small>YYYY-MM-DD</small>';
 const TEXT_ORDER_INFO = 'Order info';
 const TEXT_ORDER_INFO_INCLUDE = 'Include discount code';
 const TEXT_SPECIALS = 'Specials';
 const TEXT_EXCLUDE_SPECIALS = 'Exclude specials';
 const TEXT_NEWSLETTER = 'Only for Newsletter subscribers';
 const TEXT_ORDER_NUMBER = 'Discount on Order Nº';
 const TEXT_PRODUCTS = 'Products:';
 const TEXT_CATEGORIES = 'Categories:';
 const TEXT_MANUFACTURERS = 'Manufacturers:';
 const TEXT_CUSTOMERS = 'Customers';
 const TEXT_CUSTOMERS_SELECT = 'Select Customers:';
 const TEXT_NEWSLETTER_CUSTOMERS = 'Newsletter subscribers';
 const TEXT_NEWSLETTER_ONLY = 'Only subscribed customers';
 const TEXT_ORDER_CUSTOMERS = 'Applies only to Order Nº:<br><small>Zero doesn\'t apply</small>';
 const TEXT_INFO_CUSTOMERS = 'Discount code assigned to the customers:';
 const TEXT_EXCLUDED_PRODUCTS = 'Excluded Products:';
 const TEXT_ORDER_TOTAL = 'Order Total';
 const TEXT_ORDER_SUBTOTAL = 'Order Sub-Total';
 const TEXT_INFO_DELETE_INTRO = 'Are you sure you want to delete this discount code?';
 const TEXT_SHIPPING = 'Shipping';

 const SUCCESS_DISCOUNT_CODE_INSERTED = 'Success: The discount code has been inserted.';
 const SUCCESS_DISCOUNT_CODE_UPDATED = 'Success: The discount code has been updated.';
 const SUCCESS_DISCOUNT_CODE_REMOVED = 'Success: The discount code has been removed.';
 const ERROR_DISCOUNT_CODE_INSERTED = 'Error: The discount code has not been inserted.';

 const TEXT_DISPLAY_NUMBER_OF_DISCOUNT_CODES = 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> discount codes)';
 const BUTTON_INSERT_NEW_DISCOUNT_CODE = 'New Discount Code';

 const WARNING_OT_MODULE_INSTALL = 'Discount Code Order Total module is not installed!<br>The discount input modules on the shopping cart and checkout payment page will not show.<a href="%s"> Install Now</a>';
 const WARNING_OT_MODULE_SWITCH = 'Discount Code Order Total module is not switched on!<br>The discount input modules on the shopping cart and checkout payment page will not show.<a href="%s"> Switch Now</a>';
