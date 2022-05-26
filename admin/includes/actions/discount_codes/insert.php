<?php
/*
  $Id$

  Discount Code 5.7.1 Phoenix 1.0.8.6
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

  if (!empty($_POST['discount_codes']) && !empty($_POST['discount_values'])) {
    $exclude_specials = isset($_POST['exclude_specials']) ? (int) $_POST['exclude_specials'] : 0;
    $sql_data_array = ['products_id' => '',
                       'categories_id' => '',
                       'manufacturers_id' => '',
                       'excluded_products_id' => '',
                       'customers_id' => '',
                       'orders_total' => '0',
                       'shipping' => '0',
                       'newsletter' => empty($_POST['newsletter']) ? 0 : (int)$_POST['newsletter'] ,
                       'order_number' => empty($_POST['order_number']) ? 0 : (int)$_POST['order_number'] ,
                       'order_info' => empty($_POST['order_info']) ? 0 : (int)$_POST['order_info'] ,
                       'exclude_specials' => $exclude_specials,
                       'discount_codes' => Text::input($_POST['discount_codes']),
                       'discount_description' => Text::input($_POST['discount_description']),
                       'discount_values' => Text::input($_POST['discount_values']),
                       'minimum_order_amount' => Text::input($_POST['minimum_order_amount']),
                       'expires_date' => empty($_POST['expires_date']) ? '0000-00-00' : Text::input($_POST['expires_date']),
                       'number_of_use' => (int)$_POST['number_of_use'],
                       'number_of_products' => 0];

    $error = true;
    if ((int)$_POST['applies_to'] == 1) {
      if (isset($_POST['products_id']) && is_array($_POST['products_id']) && count($_POST['products_id']) > 0) {
        $sql_data_array['products_id'] = implode(',', $_POST['products_id']);
        $error = false;
      }
    } elseif ((int)$_POST['applies_to'] == 2) {
      if (isset($_POST['categories_id']) && is_array($_POST['categories_id']) && count($_POST['categories_id']) > 0) {
        $sql_data_array['categories_id'] = implode(',', $_POST['categories_id']);
        $error = false;
      }
    } elseif ((int)$_POST['applies_to'] == 3) {
      $sql_data_array['orders_total'] = 1; // total
      $error = false;
    } elseif ((int)$_POST['applies_to'] == 4) {
      if (isset($_POST['manufacturers_id']) && is_array($_POST['manufacturers_id']) && count($_POST['manufacturers_id']) > 0) {
        $sql_data_array['manufacturers_id'] = implode(',', $_POST['manufacturers_id']);
        $error = false;
      }
    } elseif ((int)$_POST['applies_to'] == 5) {
      $sql_data_array['orders_total'] = 2; // subtotal
      $error = false;
    } elseif ((int)$_POST['applies_to'] == 6) {
      $sql_data_array['shipping'] = 2; // shipping
      $error = false;
}

    if ((int)$_POST['applies_to'] == 2 || (int)$_POST['applies_to'] == 4) {
      if (isset($_POST['excluded_products_id']) && is_array($_POST['excluded_products_id']) && count($_POST['excluded_products_id']) > 0) {
        $sql_data_array['excluded_products_id'] = implode(',', $_POST['excluded_products_id']);
      }
    }

    if ((int)$_POST['applies_to'] != 3 && !empty($_POST['number_of_products'])) {
      $sql_data_array['number_of_products'] = (int)$_POST['number_of_products'];
    }

    if (!empty($_POST['customers']) && $_POST['customers'] == 1) {
      if (is_array($_POST['customers_id']) && count($_POST['customers_id']) > 0) {
        $sql_data_array['customers_id'] = implode(',', $_POST['customers_id']);
      }
    }

    if (!empty($_POST['newsletter']) && $_POST['newsletter'] == 1) {
      $sql_data_array['newsletter'] = (int)$_POST['newsletter'];
    }

    if (!empty($_POST['order_number']) && $_POST['order_number'] > 0) {
      $sql_data_array['order_number'] = (int)$_POST['order_number'];
    }

    if ($error == false) {
      if (empty($_GET['dID'])) {
        $db->perform('discount_codes', $sql_data_array);
        $messageStack->add_session(SUCCESS_DISCOUNT_CODE_INSERTED, 'success');
        $discount_code_id = mysqli_insert_id($db);
      } else {
        $db->perform('discount_codes', $sql_data_array, 'update', "discount_codes_id = '" . (int)$_GET['dID'] . "'");
        $messageStack->add_session(SUCCESS_DISCOUNT_CODE_UPDATED, 'success');
        $discount_code_id = $_GET['dID'];
      }
      return $Admin->link('discount_codes.php')->retain_query_except(['action'])->set_parameter('dID', $discount_code_id);
    }
  }
