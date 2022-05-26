<?php
/*
  $Id: cm_sc_discount_code.php
  $Loc: catalog/includes/modules/content/shopping_cart/

  Discount Code 5.7.0 Phoenix
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


  class cm_sc_discount_code extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_SC_DISCOUNT_CODE_';

    public function __construct() {
      parent::__construct(__FILE__);

      if (!defined('MODULE_ORDER_TOTAL_DISCOUNT_STATUS') || MODULE_ORDER_TOTAL_DISCOUNT_STATUS != 'True') {
        $this->description .=   '<div class="alert alert-warning">' . MODULE_CONTENT_SC_DISCOUNT_CODE_OT_WARNING . '<br>
                                <a href="modules.php?set=order_total&module=ot_discount&action=install">' . MODULE_CONTENT_SC_DISCOUNT_CODE_OT_INSTALL_NOW . '</a></div>';
      }

      if ( !defined('MODULE_ORDER_TOTAL_DISCOUNT_STATUS') || MODULE_ORDER_TOTAL_DISCOUNT_STATUS != 'True' ) {
        $this->enabled = false;
      }

    }

    function execute() {
      global $sess_discount_code;

      if ( isset($_SESSION['cart']) && $_SESSION['cart']->count_contents() > 0 ) {

        if ( isset($_POST['rem_discount_code']) && $_POST['rem_discount_code'] == 'remove' ) {
          unset($_SESSION['sess_discount_code']);
        }

        $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
        include 'includes/modules/content/cm_template.php';

        $discount_script = <<<eod
<script>
function discount_submit(sid){
  if(sid){
    document.discount.sid.value=sid;
  }
  document.discount.submit();
  return false;
}

$(document).ready(function() {
  var a = 0;
  discount_code_process();
  $("#discount_code").blur(function() { 
    if (a == 0) discount_code_process(); a = 0 });
  $("#discount_code").keypress(function(event) { 
    if (event.which == 13) { 
      event.preventDefault(); a = 1; 
      discount_code_process()
    } 
  });
  function discount_code_process() { 
    if ($("#discount_code").val() != "") { 
      $("#discount_code").attr("readonly", "readonly"); 
      $("#discount_code_status").empty().append('<i class="fa fa-cog fa-spin fa-lg" style="padding-right:50px;">&nbsp;</i>'); 
      $.post("discount_code.php", { 
        discount_code: $("#discount_code").val() 
      }, 
      function(data) { 
        if (data == 1) {  
          $("#discount_code_status").empty().append('<i class="fa fa-check fa-lg" style="color:#00b100; padding-right:50px;"></i>'); 
        } else { 
          $("#discount_code_status").empty().append('<i class="fa fa-ban fa-lg" style="color:#ff2800; padding-right:50px;"></i>');
          $("#discount_code").removeAttr("readonly") 
        }; 
      }); 
    } 
  }      	
});
</script>
eod;

        $GLOBALS['Template']->add_block($discount_script, 'footer_scripts');

      } // eof if $cart->count_contents() > 0
    } // eof execute

    protected function get_parameters() {
      return [
        'MODULE_CONTENT_SC_DISCOUNT_CODE_STATUS' => [
          'title' => 'Enable Shopping Cart Discount Code Module',
          'value' => 'True',
          'desc' => 'Do you want to add the module to your shop?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_CONTENT_SC_DISCOUNT_CODE_CONTENT_WIDTH' => [
          'title' => 'Content Width',
          'value' => '4',
          'desc' => 'What width container should the content be shown in?',
          'set_func' => "Config::select_one(['12', '11', '10', '9', '8', '7', '6', '5', '4', '3', '2', '1'], ",
        ],
        'MODULE_CONTENT_SC_DISCOUNT_CODE_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '145',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
        'MODULE_CONTENT_SC_DISCOUNT_CODE_GUEST' => [
          'title' => 'Show discount input to guests',
          'value' => 'False',
          'desc' => 'Do you want to let enter discount codes to guests?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
      ];
    }

  }
