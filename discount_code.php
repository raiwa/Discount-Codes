<?php
/*
  $Id$

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

  require('includes/application_top.php');

  if (!empty($_GET['discount_code'])) $_SESSION['sess_discount_code'] = Text::prepare($_GET['discount_code']);
  if (!empty($_POST['discount_code'])) $_SESSION['sess_discount_code'] = Text::prepare($_POST['discount_code']);

  $discount = 0;

  if (MODULE_ORDER_TOTAL_DISCOUNT_STATUS == 'True' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {

    if (!isset($GLOBALS['customer'])) {
      $GLOBALS['customer'] = new class {
        public function fetch_to_address($to = null) {
          return [];
        }
        public function get($key, $to = 0) {
          return null;
        }
     };
    }

    $order = new order;

    include(language::map_to_translation('/modules/order_total/ot_discount.php'));
    include('includes/modules/order_total/ot_discount.php');
    $ot_discount = new ot_discount;
    $ot_discount->process();
  }

  session_write_close();

  echo $discount > 0 ? 1 : 0;
  exit();
?>
