<?php
/*
  Discount Code 5.8.0 Phoenix 1.0.8.6
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

class hook_shop_siteWide_discountCode {

  public $version = '5.8.0-1.0.8.6';

  function listen_injectRedirects() {

    if ( (Request::get_page() == 'logoff.php') && isset($_SESSION['sess_discount_code']) ) {
      unset($_SESSION['sess_discount_code']);
    }
    if ( Request::get_page() == 'checkout_payment.php' && isset($_SESSION['sess_discount_code']) && isset($_GET['rem_discount_code']) && $_GET['rem_discount_code'] == 'remove' ) {
      unset($_SESSION['sess_discount_code']);
      Href::redirect(Guarantor::ensure_global('Linker')->build('checkout_payment.php')->retain_query_except(['rem_discount_code']));
    }

  }

  function listen_injectFormDisplay() {

    $output = null;

    if ( defined('MODULE_ORDER_TOTAL_DISCOUNT_STATUS') && MODULE_ORDER_TOTAL_DISCOUNT_STATUS == 'True' && Request::get_page() == 'checkout_payment.php' ) {
      $this->load_lang();

      if ( isset($_SESSION['sess_discount_code']) ) {
        $output .= '<hr>' . PHP_EOL;
        $output .= '<div class="form-group row hook-discountcode">' . PHP_EOL;
        $output .= '  <label for="discount_code" class="col-form-label col-sm-4 text-sm-right">' .  HOOK_DISCOUNTCODE_TITLE . '</label>' . PHP_EOL;
        $output .= '  <div class="input-group col-sm-5 col-lg-4">' . new Input('discount_code', ['value' => $_SESSION['sess_discount_code'], 'id' => 'discount_code', 'readonly' => null]) . PHP_EOL;
        $output .= '    <div class="input-group-append">' .
                          new Button(IMAGE_BUTTON_REMOVE, 'fas fa-times', 'btn-danger', [], Guarantor::ensure_global('Linker')->build('checkout_payment.php', ['rem_discount_code' => 'remove'])) .
                   '    </div>
                      </div>';
        $output .= '</div>' . PHP_EOL;
      } else {
        $output .= '<hr>' . PHP_EOL;
        $output .= '<div class="form-group row hook-discountcode">' . PHP_EOL;
        $output .= '  <label for="discount_code" class="col-form-label col-sm-4 text-sm-right">' .  HOOK_DISCOUNTCODE_TITLE . '</label>' . PHP_EOL;
        $output .= '  <div class="col-sm-4 col-lg-3">' . new Input('discount_code', ['value' => ($_SESSION['sess_discount_code'] ?? ''), 'id' => 'discount_code']) . '<span class="form-control-feedback" id="discount_code_status"></span></div>' . PHP_EOL;
        $output .= '</div>' . PHP_EOL;
      }
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
    if (a == 0) discount_code_process(); a = 0
  });
  $("#discount_code").keypress(function(event) {
    if (event.which == 13) {
    event.preventDefault();
    a = 1; discount_code_process()
    }
  });
  function discount_code_process() {
    if ($("#discount_code").val() != "") {
      $("#discount_code").attr("readonly", "readonly");
      $("#discount_code_status").empty().append('<i class="fa fa-cog fa-spin fa-lg">&nbsp;</i>');
      $.post("discount_code.php", {
        discount_code: $("#discount_code").val()
      },
      function(data) {
        if (data == 1) {
          $("#discount_code_status").empty().append('<i class="fa fa-check fa-lg" style="color:#00b100;"></i>');
        } else {
          $("#discount_code_status").empty().append('<i class="fa fa-ban fa-lg" style="color:#ff2800;"></i>');
          $("#discount_code").removeAttr("readonly");
        };
      });
    }
  }
});
</script>
eod;

      $GLOBALS['Template']->add_block($discount_script, 'footer_scripts');

      return $output;
    }
  }

  function listen_databaseOrderBuild($parameters) {

    if (!empty($parameters['builder']->get('discount_codes'))) {
      $order_info = &$parameters['order']->info;
      $order_info['discount_codes'] = $parameters['builder']->get('discount_codes');
    }
  }

  function listen_constructOrder($parameters) {

    if (!empty($_SESSION['sess_discount_code'])) {
      $order_info = &$parameters->info;
      $order_info['discount_codes'] = ($_SESSION['sess_discount_code']);
    }
  }

  function listen_insertOrder($parameters) {

    $orders = &$parameters['sql_data']['orders'];
    $orders['discount_codes'] = ($GLOBALS['order']->info['discount_codes'] ?? '');
  }

  function listen_afterStart() {

    if ( defined('MODULE_ORDER_TOTAL_DISCOUNT_STATUS') && MODULE_ORDER_TOTAL_DISCOUNT_STATUS == 'True' && !empty($GLOBALS['order']->info['discount_codes']) ) {
      $discount_codes_query = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT discount_codes_id
  FROM discount_codes
  WHERE discount_codes = '%s'
EOSQL
      , $GLOBALS['order']->info['discount_codes']));

      $discount_codes = $discount_codes_query->fetch_assoc();
      $GLOBALS['db']->query(sprintf(<<<'EOSQL'
UPDATE discount_codes
  SET number_of_orders = number_of_orders + 1
  WHERE discount_codes_id = %s
EOSQL
      , (int)$discount_codes['discount_codes_id']));

      $sql_data = ['customers_id' => (int)$_SESSION['customer_id'],
                   'discount_codes_id' => (int)$discount_codes['discount_codes_id']
                   ];
      $GLOBALS['db']->perform('customers_to_discount_codes ', $sql_data);

      unset($_SESSION['sess_discount_code']);

    }
  }

  function load_lang() {
    if (!defined('HOOK_DISCOUNTCODE_TITLE')) {
      require(language::map_to_translation('hooks/shop/siteWide/discountCode.php'));
    }
  }

}
