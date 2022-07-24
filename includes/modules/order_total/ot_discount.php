<?php
/*
  $Id$

  Discount Code 5.8.0. Phoenix Pro 1.0.8.6
  by @raiwa
  info@oscaddons.com
  www.oscaddons.com

  Based on Discount Codes BS 3.x and 4.x by @Tsimi and @raiwa
  Based on the Discount Code for osCommerce 2.3.1 addon by high-quality-php-coding.com

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class ot_discount extends abstract_module {
    var $version = '5.8.0.-1.0.8.6';

    const CONFIG_KEY_BASE = 'MODULE_ORDER_TOTAL_DISCOUNT_';

    public $output = [];

    public function __construct() {
      parent::__construct(__FILE__);

      if (!file_exists(DIR_FS_CATALOG . 'templates/' . TEMPLATE_SELECTION . '/includes/hooks/shop/siteWide/discountCode.php')) {
        $this->enabled = false;
        $this->description .= '<div class="alert alert-danger" role="alert">' .
                                 MODULE_ORDER_TOTAL_DISCOUNT_HOOK_MODULE_WARNING .
                             '</div>' .
                             $this->description;
      }

    }

    function process() {
      global $order, $currencies, $discount, $db;

      $discount = 0;
      $subtotal_correction = 0;
      $tax_correction = 0;
      $newsletter = 0;
      $shipping_discount = 'false';

      if (isset($_SESSION['sess_discount_code'])) {

        $check_query = $db->fetch_all(sprintf(<<<'EOSQL'
SELECT *
  FROM discount_codes
  WHERE discount_codes = '%s'
    AND IF(expires_date = '0000-00-00', date_format(date_add(now(), interval 1 day), '%%Y-%%m-%%d'), expires_date) >= date_format(now(), '%%Y-%%m-%%d')
    AND status = 1
  LIMIT 1
EOSQL
          , $_SESSION['sess_discount_code']));

        if ([] === $check_query) return;

        $check = $check_query[0];

        if ( !isset($_SESSION['customer_id']) && !empty($check['number_of_use']) && $check['number_of_orders'] >= $check['number_of_use']) return;
        $order_total = (isset($order->info['subtotal'])? $order->info['subtotal'] : $_SESSION['cart']->show_total());
        if ( !empty($check['minimum_order_amount']) && $order_total < $check['minimum_order_amount']) return;

        if ( isset($_SESSION['customer_id']) ) {
// logged in customer
          $customer_id = $_SESSION['customer_id'];
          $customers = [];
          if (empty($check['customers_id'])) {
            if (!empty($check['number_of_use']) && $check['number_of_orders'] >= $check['number_of_use']) return;
          } else {
            $customers = explode(',', $check['customers_id']);
            if ( !in_array($GLOBALS['customer']->get('email_address'), $customers) ) return;
              $check_customers_query = $db->query(sprintf(<<<'EOSQL'
SELECT count(*) AS total
  FROM customers_to_discount_codes
  WHERE discount_codes_id = '%s'
    AND customers_id = %s
  GROUP BY customers_id
  LIMIT 1
EOSQL
              , $check['discount_codes_id'], (int)$customer_id));

            if ( !empty($check['number_of_use']) && (mysqli_num_rows($check_customers_query)) >= $check['number_of_use']) return;
          }

          if (!empty($check['newsletter'])) {
            $check_news = $db->fetch_all(sprintf(<<<'EOSQL'
SELECT customers_newsletter
FROM customers
  WHERE customers_id = %s
EOSQL
              , (int)$customer_id));

            if ( $check_news[0]['customers_newsletter'] != 1) return;
          }

          if (!empty($check['order_number'])) {
            $check_query_order = $db->query(sprintf(<<<'EOSQL'
SELECT count(*) AS orders
FROM orders
WHERE customers_id = %s
EOSQL
              , (int)$customer_id));

            $check_order = $check_query_order->fetch_assoc();
            $orders = $check_order['orders']+1;
            // Support for PWA guest orders BEGIN
            $guest_check = $db->query("SHOW COLUMNS FROM orders LIKE 'customers_guest'");
            $exists = (mysqli_num_rows($guest_check))? true : false;
            if ($exists) {
              $check_query_mail = $db->query(sprintf(<<<'EOSQL'
SELECT customers_email_address
FROM customers
WHERE customers_id = %s
EOSQL
              , (int)$customer_id));

              $check_mail = $check_query_mail->fetch_assoc();
              if (!empty($check_mail['customers_email_address'])) {
                $check_query_order_guest = $db->query(sprintf(<<<'EOSQL'
SELECT count(*) AS orders
FROM orders
WHERE customers_email_address = '%s'
  AND customers_guest = 1
EOSQL
                , $check_mail['customers_email_address']));

                $check_order_guest = $check_query_order_guest->fetch_assoc();
                $orders = $orders + $check_order_guest['orders'];
              }
            }
            // Support for PWA guest orders END
            if ( $orders != $check['order_number'] ) return;
          }

        }

        // check if shipping has tax
        $module = substr($_SESSION['shipping']['id'], 0, strpos($_SESSION['shipping']['id'], '_'));
        if (!Text::is_empty($order->info['shipping_method'])) {
          if ( !empty($GLOBALS[$module]) && $GLOBALS[$module]->tax_class > 0) {
            $shipping_tax = Tax::get_rate($GLOBALS[$module]->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
          }
        }

        if (!empty($check['products_id']) || !empty($check['categories_id']) || !empty($check['manufacturers_id']) || (int)$check['exclude_specials'] == 1) {

          $products = [];
          if (!empty($check['products_id'])) {
            $products = explode(',', $check['products_id']);
          } elseif (!empty($check['categories_id'])) {
            $product_query = $db->query(sprintf(<<<'EOSQL'
SELECT products_id
  FROM products_to_categories
  WHERE categories_id IN (%s)%s
EOSQL
              , $check['categories_id'], (empty($check['excluded_products_id']) ? '' : ' AND products_id NOT IN (' . $check['excluded_products_id'] . ')')));

            while ($product = $product_query->fetch_assoc()) {
              $products[] = $product['products_id'];
            }
          } elseif (!empty($check['manufacturers_id'])) {
            $product_query = $db->query(sprintf(<<<'EOSQL'
SELECT products_id
  FROM products
  WHERE manufacturers_id IN (%s)%s
EOSQL
              , $check['manufacturers_id'], (empty($check['excluded_products_id']) ? '' : ' AND products_id NOT IN (' . $check['excluded_products_id'] . ')')));

            while ($product = $product_query->fetch_assoc()) {
              $products[] = $product['products_id'];
            }
          } elseif ((int)$check['exclude_specials'] == 1) {
            for ($i = 0, $n = count($order->products); $i < $n; $i++) {
              $products[] = $order->products[$i]['id'];
            }
          }

          if ((int)$check['exclude_specials'] == 1) {
            $specials = [];
            $product_query = $db->query(<<<'EOSQL'
SELECT p.products_id
  FROM products p, specials s
  WHERE p.products_id = s.products_id
  AND s.status = '1'
  AND ifnull(s.expires_date, now()) >= now()
EOSQL
              );

            while ($product = $product_query->fetch_assoc()) {
              $specials[] = $product['products_id'];
            }
            if (count($specials) > 0) {
              $products = array_diff($products, $specials);
            }
          }

          if (empty($check['number_of_products'])) {
            $k = PHP_INT_MAX;
          } else {
            $k = $check['number_of_products'];
          }

          for ($i = 0, $n = count($order->products); $i < $n; $i++) {
            if (in_array(Product::build_prid($order->products[$i]['id']), $products)) {
              if ($k >= $order->products[$i]['qty']) {
                $products_discount = $currencies->format_raw(strpos($check['discount_values'], '%') === false ? $check['discount_values'] * $order->products[$i]['qty'] : Tax::price($order->products[$i]['final_price'], $order->products[$i]['tax']) * str_replace('%', '', $check['discount_values']) / 100 * $order->products[$i]['qty']);
                $k -= $order->products[$i]['qty'];
              } else {
                $products_discount = $currencies->format_raw(strpos($check['discount_values'], '%') === false ? $check['discount_values'] * $k : Tax::price($order->products[$i]['final_price'], $order->products[$i]['tax']) * str_replace('%', '', $check['discount_values']) / 100 * $k);
                $k = 0;
              }

              if (!empty($order->products[$i]['tax'])) {
                if (DISPLAY_PRICE_WITH_TAX != 'true') {
                  $tax_correction = $currencies->format_raw(($products_discount * ($order->products[$i]['tax'] / 100)));
                  $order->info['total'] -= $tax_correction;
                } else {
                  $tax_correction = $currencies->format_raw($products_discount - $products_discount / (1.0 + $order->products[$i]['tax'] / 100));
                }
              }
              $subtotal_correction +=  $order->products[$i]['final_price']; //use for tax calculation only products which have taxes
              $order->info['tax'] -= $tax_correction;
              $order->info['tax_groups'][$order->products[$i]['tax_description']] -= $tax_correction;
              $discount += $products_discount;
            }
          }

          // revert currency conversion to default currency
          $order->info['total'] -= $currencies->format_raw($discount, true, $order->info['currency'], 1/$order->info['currency_value']);

          // format discount for output, do not apply currency conversion, product price already converted to order currency
          $discount_formatted = $currencies->format($discount, false);

        } elseif (!empty($check['orders_total'])) {
          if ($check['orders_total'] == 2) {
            $discount = (strpos($check['discount_values'], '%') === false ? $check['discount_values'] : $order->info['subtotal'] * str_replace('%', '', $check['discount_values']) / 100);
            if ($discount > $order->info['subtotal']) {
              $discount = $order->info['subtotal'];
            }
            $discount = $currencies->format_raw($discount, false);

            $order_tax = $order->info['tax'];
            if (DISPLAY_PRICE_WITH_TAX == 'true' &&  MODULE_ORDER_TOTAL_DISCOUNT_TAX_CALCULATION_EXCL == 'true' ) {
              // find order subtotal excl. tax
              $order_subtotal_excl = null;
              for ($i = 0, $n = count($order->products); $i < $n; $i++) {
                $order_subtotal_excl += $order->products[$i]['qty'] * $order->products[$i]['final_price'];
              }
              for ($i = 0, $n = count($order->products); $i < $n; $i++) {
                if (!empty($order->products[$i]['tax'])) {
                  $portion = ($order->products[$i]['qty'] * $order->products[$i]['final_price']) / $order_subtotal_excl;
                  $global_tax_correction = $discount * $portion;
                  $discount_excl = ($global_tax_correction / (1+$order->products[$i]['tax']/100));
                  $discount_tax = $global_tax_correction - $discount_excl;
                  // strip discount from order total
                  $order->info['total'] -= $currencies->format_raw($global_tax_correction, false);
                  // correct tax
                  if (!empty($discount_tax) && is_array($order->info['tax_groups']) && count($order->info['tax_groups']) > 0) {
                    foreach ($order->info['tax_groups'] as $key => $value) {
                      if ($key == $order->products[$i]['tax_description']) {
                       $order->info['tax_groups'][$key] -= $discount_tax;
                       $order->info['tax'] -= $discount_tax;
                      }
                    } // end for each tax group
                  } // if discount tax and tax groups
                } // end if products tax
              } // end products loop
            } else {

            for ($i = 0, $n = count($order->products); $i < $n; $i++) {
              if (!empty($order->products[$i]['tax'])) {
                //here it gets complicate, we have to find the proportional part of the global discount for each product
                $global_tax_correction = $order->products[$i]['qty']*(( $order->products[$i]['final_price']/$order->info['subtotal'])*$discount)+(($order->products[$i]['qty']* $order->products[$i]['final_price']/$order->info['subtotal'])*$discount) * ($order->products[$i]['tax'] / 100);
                $order->info['total'] -= $global_tax_correction;
             }
            }

            if (is_array($order->info['tax_groups']) && count($order->info['tax_groups']) > 0) {
              foreach ($order->info['tax_groups'] as $key => $value) {
                if (!empty($value)) {
                    $order->info['tax_groups'][$key] = $currencies->format_raw(($order->info['subtotal'] - $discount) * ($value / $order->info['subtotal']), false);
                  $order_tax += $order->info['tax_groups'][$key];
                }
              }
            }
          }

          if ( !empty($order_tax) ) {
            $order->info['tax'] = $order_tax;
          } else {
            $order->info['total'] -= $discount;
          }

        }
        // format discount for output, do not apply currency conversion
        $discount_formatted = $currencies->format($discount, true, $order->info['currency'], $order->info['currency_value']);

        } elseif (!empty($check['shipping'])) { //.eof $check['orders_total']
          if ($check['shipping'] == 2) {
            $discount = $order->info['shipping_cost'] * str_replace('%', '', strtolower($check['discount_values'])) / 100;
            if ($discount > $order->info['shipping_cost']) {
              $discount = $order->info['shipping_cost'];
            }
            // calculate shipping tax
            $module = substr($GLOBALS['shipping']['id'], 0, strpos($GLOBALS['shipping']['id'], '_'));
            if (!Text::is_empty($order->info['shipping_method'])) {
              if ($GLOBALS[$module]->tax_class > 0) {
                $shipping_tax = Tax::get_rate($GLOBALS[$module]->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
              }
            }
            if (DISPLAY_PRICE_WITH_TAX == 'true' && MODULE_ORDER_TOTAL_DISCOUNT_SORT_ORDER <= MODULE_ORDER_TOTAL_SHIPPING_SORT_ORDER ) $discount += Tax::calculate($discount, $shipping_tax);
            $order_tax = 0;
            if (is_array($order->info['tax_groups']) && count($order->info['tax_groups']) > 0) {
              if ( MODULE_ORDER_TOTAL_DISCOUNT_SORT_ORDER <= MODULE_ORDER_TOTAL_SHIPPING_SORT_ORDER ) { // discount before shipping
                foreach ($order->info['tax_groups'] as $key => $value) {
                  if (!empty($value)) {
                    if ($shipping_tax > 0) {
                      $order->info['tax_groups'][$key] = $currencies->format_raw(($order->info['subtotal'] - $discount) * ($value / $order->info['subtotal']));
                    } else {
                      $order->info['tax_groups'][$key] = $currencies->format_raw(($order->info['subtotal']) * ($value / $order->info['subtotal']));
                    }
                    $order_tax += $order->info['tax_groups'][$key];
                  }
                }
              } else { // shipping before discount
                foreach ($order->info['tax_groups'] as $key => $value) {
                  if (!empty($value)) {
                    if ($shipping_tax > 0) {
                      $order->info['tax_groups'][$key] = $currencies->format_raw(($order->info['subtotal']) * (($value - Tax::calculate(((DISPLAY_PRICE_WITH_TAX == 'true')? $discount / (1 + $shipping_tax / 100) : $discount ), $shipping_tax)) / $order->info['subtotal']));
                    } else {
                      $order->info['tax_groups'][$key] = $currencies->format_raw(($order->info['subtotal']) * ($value / $order->info['subtotal']));
                    }
                    $order_tax += $order->info['tax_groups'][$key];
                  }
                }
              }
            }

            $order->info['total'] -= $discount;
            if (DISPLAY_PRICE_WITH_TAX != 'true') {
              $order->info['total'] -= Tax::calculate($discount, $shipping_tax);
            }
            if (!empty($order_tax)) {
              $order->info['tax'] = $order_tax;
            }
            $shipping_discount = 'true';
          }

          // format discount for output, apply currency conversion
          $discount_formatted = $currencies->format($discount, true, $order->info['currency'], $order->info['currency_value']);

        } //.eof $check['shipping']
        if ( MODULE_ORDER_TOTAL_DISCOUNT_SHOW_SHIPPING_DISCOUNTED == 'true' && $shipping_discount == 'true') {
          $order->info['shipping_cost'] -= $discount;
        }

        if ( MODULE_ORDER_TOTAL_DISCOUNT_SORT_ORDER <= MODULE_ORDER_TOTAL_SUBTOTAL_SORT_ORDER ) { // discount before order subtotal
          $order->info['subtotal'] -= $discount;
        }
      } // eof check newsletter and order number

      if (!empty($discount)) {
        $this->output[] = ['title' => (($shipping_discount == 'true')? TEXT_SHIPPING_DISCOUNT : TEXT_DISCOUNT) . (strpos($check['discount_values'], '%') ? ' ' . $check['discount_values'] . ' ' : '') . (!empty($order_info) ? ' (' . $_SESSION['sess_discount_code'] . ')' : '') . ':',
                           'text' => '<span style="color:#ff0000">' . $discount_formatted . '</span>',
                           'value' => -$discount];
      }
    }

    public function get_parameters() {
      return [
        'MODULE_ORDER_TOTAL_DISCOUNT_VERSION' => [
          'title' => 'Module Version',
          'value' => $this->version,
          'desc' => 'Do you want to display the order shipping cost?',
          'set_func' => 'ot_discount::readonly(',
        ],
        'MODULE_ORDER_TOTAL_DISCOUNT_STATUS' => [
          'title' => 'Use Discount Codes',
          'value' => 'True',
          'desc' => 'Do you want to use discount code?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_ORDER_TOTAL_DISCOUNT_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '15',
          'desc' => 'Sort order of display.',
        ],
        'MODULE_ORDER_TOTAL_DISCOUNT_SHOW_SHIPPING_DISCOUNTED' => [
          'title' => 'Show shipping fee discounted',
          'value' => 'false',
          'desc' => 'Do you want to show the shipping cost with applied discount?<br>Only applies if ot discount module is shown before ot shipping module.',
          'set_func' => "Config::select_one(['true', 'false'], ",
        ],
        'MODULE_ORDER_TOTAL_DISCOUNT_TAX_CALCULATION_EXCL' => [
          'title' => 'Base discount tax on prices excl.',
          'value' => 'true',
          'desc' => 'Shall discount tax be calculated by portions based on product prices excl tax?<br> Only affects stores showing prices incl. tax and mixed orders including products with different tax rates.',
          'set_func' => "Config::select_one(['true', 'false'], ",
        ],
        'MODULE_ORDER_TOTAL_DISCOUNT_DELETE_TABLES' => [
          'title' => 'Delete auto created tables when uninstalling',
          'value' => 'False',
          'desc' => 'Do you want to remove the tables that were created during installing this module?<br><i>Note: all the created discount codes will be deleted</i>.',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
      ];
    }

    function install($parameter_key = null) {
      parent::install($parameter_key);
      global $db;

      // CREATE NEEDED TABLES INTO DB
      $db->query("
        CREATE TABLE IF NOT EXISTS customers_to_discount_codes (
          customers_to_discount_codes_id int(30) NOT NULL auto_increment,
          customers_id int(11) NOT NULL default '0',
          discount_codes_id int(11) NOT NULL default '0',
          PRIMARY KEY (customers_to_discount_codes_id)
          ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1;
        ");
      $db->query("
        CREATE TABLE IF NOT EXISTS discount_codes (
          discount_codes_id int(11) NOT NULL auto_increment,
          discount_description VARCHAR(128) NULL,
          products_id TEXT NOT NULL,
          categories_id TEXT NOT NULL,
          manufacturers_id TEXT NOT NULL,
          excluded_products_id TEXT NOT NULL,
          customers_id TEXT NOT NULL,
          orders_total tinyint(1) NOT NULL default '0',
          shipping tinyint(1) NOT NULL default '0',
          order_info tinyint(1) NOT NULL default '0',
          exclude_specials tinyint(1) NOT NULL default '0',
          discount_codes varchar(8) NOT NULL default '',
          discount_values varchar(8) NOT NULL default '',
          minimum_order_amount decimal(15,4) NOT NULL default '0.0000',
          expires_date date NOT NULL default '0000-00-00',
          number_of_orders int(4) NOT NULL default '0',
          number_of_use int(4) NOT NULL default '0',
          number_of_products int(4) NOT NULL default '0',
          status tinyint(1) NOT NULL default '1',
          PRIMARY KEY (discount_codes_id)
          ) DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1 ;
        ");

      // check if new field exist if not create
      $check = $db->query("SHOW COLUMNS FROM discount_codes LIKE 'shipping'");
      $exists = (mysqli_num_rows($check))?TRUE:FALSE;
      if(!$exists) {
        $db->query("ALTER TABLE discount_codes ADD shipping tinyint(1) NOT NULL default '0'");
      }
      $check = $db->query("SHOW COLUMNS FROM discount_codes LIKE 'newsletter'");
      $exists = (mysqli_num_rows($check))?TRUE:FALSE;
      if(!$exists) {
        $db->query("ALTER TABLE discount_codes ADD newsletter tinyint(1) NOT NULL default '0'");
      }
      $check = $db->query("SHOW COLUMNS FROM discount_codes LIKE 'order_number'");
      $exists = (mysqli_num_rows($check))?TRUE:FALSE;
      if(!$exists) {
        $db->query("ALTER TABLE discount_codes ADD order_number tinyint(1) NOT NULL default '0'");
      }
      $check = $db->query("SHOW COLUMNS FROM discount_codes LIKE 'discount_description'");
      $exists = (mysqli_num_rows($check))?TRUE:FALSE;
      if(!$exists) {
        $db->query("ALTER TABLE discount_codes ADD discount_description VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER discount_codes_id");
      }
      $check = $db->query("SHOW COLUMNS FROM orders LIKE 'discount_codes'");
      $exists = (mysqli_num_rows($check))?TRUE:FALSE;
      if(!$exists) {
        $db->query("ALTER TABLE orders ADD discount_codes varchar(8) NOT NULL default ''");
      }
      // update columns
      $check = $db->query("SHOW COLUMNS FROM discount_codes LIKE 'products_id'")->fetch_assoc();
      if($check['Type'] == 'int(11)') {
        $db->query("ALTER TABLE discount_codes CHANGE products_id products_id TEXT NOT NULL");
        $db->query("ALTER TABLE discount_codes CHANGE categories_id categories_id TEXT NOT NULL");
        $db->query("ALTER TABLE discount_codes CHANGE manufacturers_id manufacturers_id TEXT NOT NULL");
        $db->query("ALTER TABLE discount_codes CHANGE excluded_products_id excluded_products_id TEXT NOT NULL");
        $db->query("ALTER TABLE discount_codes CHANGE customers_id customers_id TEXT NOT NULL");
      }
    }

    function remove($parameter_key = null) {
      parent::remove($parameter_key);
      global $db;

      if ( defined('MODULE_ORDER_TOTAL_DISCOUNT_DELETE_TABLES') && MODULE_ORDER_TOTAL_DISCOUNT_DELETE_TABLES == 'True' ) {
        $db->query("DROP TABLE IF EXISTS customers_to_discount_codes");
        $db->query("DROP TABLE IF EXISTS discount_codes");
        $db->query("ALTER TABLE orders DROP discount_codes");
      }

    }

  ////
  // Function for version read out
    public static function readonly($value) {
      return $value;
    }
  }
