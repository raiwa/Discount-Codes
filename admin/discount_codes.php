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

  $always_valid_actions = ['set_flag'];
  require 'includes/application_top.php';

  Guarantor::ensure_global('currencies');

  require 'includes/segments/process_action.php';

  require 'includes/template_top.php';

?>
  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1>
      <?php
      if ( !defined('MODULE_ORDER_TOTAL_DISCOUNT_STATUS') ) {
        echo '<div class="alert alert-warning">';
        printf(WARNING_OT_MODULE_INSTALL, $Admin->link('modules.php?set=order_total&module=ot_discount&action=install'));
        echo '</div>';
      } elseif ( MODULE_ORDER_TOTAL_DISCOUNT_STATUS != 'True' ) {
        echo '<div class="alert alert-warning">';
        printf(WARNING_OT_MODULE_SWITCH, $Admin->link('modules.php?set=order_total&module=ot_discount&action=edit'));
        echo '</div>';
      }
      ?>
    </div>
    <div class="col text-right align-self-center">
      <?php
      if (empty($action)) {
        echo new Button(BUTTON_INSERT_NEW_DISCOUNT_CODE, 'fas fa-tag', 'btn-danger text-white', [], $Admin->link('discount_codes.php', 'action=new'));
      } else {
        echo new Button(IMAGE_CANCEL, 'fas fa-angle-left', 'btn-light mt-2', [], $Admin->link('discount_codes.php'));
      }
      ?>
    </div>
  </div>

<?php
  $base_url = HTTP_SERVER . DIR_WS_ADMIN;

  if ($view_file = $Admin->locate('/views', $action)) {
    require $view_file;
  }

  require('includes/template_bottom.php');
  require('includes/application_bottom.php');
?>
