<div class="col-sm-<?= (int)MODULE_CONTENT_SC_DISCOUNT_CODE_CONTENT_WIDTH ?> cm-sc-discount mt-3">
  <div class="card">
    <div class="card-header"><?= MODULE_CONTENT_SC_DISCOUNT_HEADER_TITLE ?></div>
    <div class="card-body">
<?php
      echo new Form('discount', Guarantor::ensure_global('Linker')->build('shopping_cart.php'), 'post', ['role' => 'form']);

      if (isset($_SESSION['customer_id']) || MODULE_CONTENT_SC_DISCOUNT_CODE_GUEST == 'True') {
        if ( isset($_SESSION['sess_discount_code']) ) {
          echo '<div class="input-group">' . new Input('discount_code', ['value' => $_SESSION['sess_discount_code'], 'id' => 'discount_code', 'readonly' => null]) . '<span class="form-control-feedback"></span>';
          // show remove button only if ship in cart ot module is present
          echo '<div class="input-group-append">' .
                  new Button(IMAGE_BUTTON_REMOVE, 'fas fa-times', 'btn-danger') . new Input('rem_discount_code', ['value' => 'remove'], 'hidden') .
               '</div>';
          echo '</div>';
        } else {
          echo '<div class="input-group">' . new Input('discount_code', ['value' => ($_SESSION['sess_discount_code'] ?? ''), 'id' => 'discount_code']) . '<span class="form-control-feedback" id="discount_code_status"></span>';
          // show apply button only if ship in cart ot module is present
          echo '<div class="input-group-append">' . 
                  new Button(IMAGE_BUTTON_APPLY, 'fas fa-sign-in-alt', 'btn-primary discount-apply disabled', ['id' => 'discount-apply', 'href' => '_', 'onclick' => 'return discount_submit(\'\');']) . 
                '</div>';
          echo '</div>';
        }
      } else {
        echo '<p class="text-center">' . MODULE_CONTENT_SC_DISCOUNT_TEXT_LOG_IN . '</p>
              <div class="text-center">' . new Button(IMAGE_BUTTON_LOGIN, 'fas fa-sign-in-alt', 'btn-success', [], Guarantor::ensure_global('Linker')->build('login.php')) . '</div>';
      }
?>
      </form>

    </div>
  </div>
</div>

<?php
/*
  $Id: cm_sc_discount_code.php
  $Loc: catalog/includes/modules/content/shopping_cart/

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
