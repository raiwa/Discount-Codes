<?php
/*
  $Id$

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

  echo new Form('new_discount_code', ($Admin->link('discount_codes.php'))->retain_query_except()->set_parameter('action', 'insert'));

  $dInfo = new objectInfo(['products_id' => '',
                           'categories_id' => '',
                           'manufacturers_id' => '',
                           'excluded_products_id' => '',
                           'customers_id' => '',
                           'orders_total' => '2',
                           'shipping' => '',
                           'newsletter' => '',
                           'order_number' => '',
                           'order_info' => '',
                           'exclude_specials' => '',
                           'discount_codes' => substr(md5(uniqid(rand(), true)), 0, 8),
                           'discount_description' => '',
                           'discount_values' => '',
                           'minimum_order_amount' => '',
                           'expires_date' => '',
                           'number_of_orders' => '',
                           'number_of_use' => '1',
                           'number_of_products' => '1',
                           'status' => '']);

  if (isset($_GET['dID'])) {
    $discount_code_query = $db->query(sprintf(<<<'EOSQL'
SELECT *
  FROM discount_codes
  WHERE discount_codes_id = %s
EOSQL
      , (int)$_GET['dID']));

    $discount_code = $discount_code_query->fetch_assoc();

    $dInfo->objectInfo($discount_code);

    if (!empty($discount_code['products_id'])) $dInfo->products_id = explode(',', $discount_code['products_id']);
    if (!empty($discount_code['categories_id'])) $dInfo->categories_id = explode(',', $discount_code['categories_id']);
    if (!empty($discount_code['manufacturers_id'])) $dInfo->manufacturers_id = explode(',', $discount_code['manufacturers_id']);
    if (!empty($discount_code['excluded_products_id'])) $dInfo->excluded_products_id = explode(',', $discount_code['excluded_products_id']);
    if (!empty($discount_code['customers_id'])) $dInfo->customers_id = explode(',', $discount_code['customers_id']);
    if ($discount_code['minimum_order_amount'] == '0.0000') $dInfo->minimum_order_amount = '';
    if ($discount_code['expires_date'] == '0000-00-00') $dInfo->expires_date = '';
    if ($discount_code['number_of_use'] == 0) $dInfo->number_of_use = '';
    if ($discount_code['number_of_products'] == 0) $dInfo->number_of_products = '';
  }

  $manufacturers_array = [];
  $manufacturers_query = $db->query(<<<'EOSQL'
SELECT manufacturers_id, manufacturers_name
  FROM manufacturers
  ORDER by manufacturers_name
EOSQL
  );

  while ( $manufacturers = $manufacturers_query->fetch_assoc() ) {
    $manufacturers_array[] = ['id' => $manufacturers['manufacturers_id'],
                              'text' => $manufacturers['manufacturers_name']];
  }

?>

  <div class="alert alert-info">
    <?= TEXT_DISCOUNT_EXPL ?>
  </div>

  <div class="form-group row">
    <label for="discount_description" class="col-form-label col-sm-3 text-left text-sm-right pt-0"><?= TEXT_DISCOUNT_DESCRIPTION ?></label>
    <div class="col-sm-9"><?= new Input('discount_description', ['value' => $dInfo->discount_description, 'id' => 'discount_description']) ?>
    </div>
  </div>

  <div class="row">
    <div class="col-sm-6">
      <div class="form-group row">
        <label for="discount_codes" class="col-form-label col-sm-6 text-left text-sm-right"><?= TEXT_DISCOUNT_CODE ?></label>
        <div class="col-sm-6"><?= new Input('discount_codes', ['value' => $dInfo->discount_codes, 'style' => 'width: 150px;']) ?>
        </div>
      </div>
      <div class="form-group row">
        <label for="discount_values" class="col-form-label col-sm-6 text-left text-sm-right"><?= TEXT_DISCOUNT ?></label>
        <div class="col-sm-6"><?= new Input('discount_values', ['value' => $dInfo->discount_values, 'id' => 'discount_values', 'style' => 'width: 150px;']) ?>
        </div>
      </div>
      <div class="form-group row">
        <label for="minimum_order_amount" class="col-form-label col-sm-6 text-left text-sm-right"><?= TEXT_MINIMUM_ORDER_SUB_TOTAL ?></label>
        <div class="col-sm-6"><?= new Input('minimum_order_amount', ['value' => $dInfo->minimum_order_amount, 'id' => 'minimum_order_amount', 'style' => 'width: 150px;']) ?>
        </div>
      </div>
      <div class="form-group row">
        <label for="expires_date" class="col-form-label col-sm-6 text-left text-sm-right pt-0"><?= TEXT_EXPIRY ?></label>
        <div class="col-sm-6"><?= new Input('expires_date', ['value' => $dInfo->expires_date, 'id' => 'expires_date', 'style' => 'width: 150px;']) ?>
        </div>
      </div>
    </div>

    <div class="col-sm-6">
      <div class="form-group row">
        <label for="number_of_use" class="col-form-label col-sm-6 text-left text-sm-right py-0"><?= TEXT_NUMBER_OF_USE ?></label>
        <div class="col-sm-6"><?= new Input('number_of_use', ['value' => $dInfo->number_of_use, 'id' => 'number_of_use', 'style' => 'width: 150px;']) ?>
        </div>
      </div>
      <div class="form-group row">
        <label for="number_of_products" class="col-form-label col-sm-6 text-left text-sm-right py-0"><?= TEXT_NUMBER_OF_PRODUCTS ?></label>
        <div class="col-sm-6"><?= new Input('number_of_products', ['value' => $dInfo->number_of_products, 'id' => 'number_of_products', 'style' => 'width: 150px;']) ?>
        </div>
      </div>
      <div class="form-group row">
        <label for="order_number" class="col-form-label col-sm-6 text-left text-sm-right py-0"><?= TEXT_ORDER_CUSTOMERS ?></label>
        <div class="col-sm-6"><?= new Input('order_number', ['value' => $dInfo->order_number, 'id' => 'order_number', 'style' => 'width: 50px;']) ?>
        </div>
      </div>
      <div class="form-group row align-items-center my-0">
        <div class="col-form-label col-sm-6 text-left text-sm-right"><?= TEXT_ORDER_INFO ?></div>
        <div class="col-sm-6 pl-5 custom-control custom-switch">
          <?= (new Tickable('order_info', ['value' => '1', 'class' => 'custom-control-input', 'id' => 'order_info'], 'checkbox'))->tick($dInfo->order_info == 1) ?>
          <label for="order_info" class="custom-control-label text-muted"><small><?= TEXT_ORDER_INFO_INCLUDE ?>&nbsp;</small></label>
        </div>
      </div>
      <div class="form-group row align-items-center my-0">
        <div class="col-form-label col-sm-6 text-left text-sm-right"><?= TEXT_NEWSLETTER_CUSTOMERS ?></div>
        <div class="col-sm-6 pl-5 custom-control custom-switch">
          <?= (new Tickable('newsletter', ['value' => '1', 'class' => 'custom-control-input', 'id' => 'newsletter'], 'checkbox'))->tick($dInfo->newsletter) ?>
          <label for="newsletter" class="custom-control-label text-muted"><small><?= TEXT_NEWSLETTER_ONLY; ?>&nbsp;</small></label>
        </div>
      </div>
      <div class="form-group row align-items-center">
        <div class="col-form-label col-sm-6 text-left text-sm-right"><?= TEXT_SPECIALS; ?></div>
        <div class="col-sm-6 pl-5 custom-control custom-switch">
          <?= (new Tickable('exclude_specials', ['value' => '1', 'class' => 'custom-control-input', 'id' => 'exclude_specials'], 'checkbox'))->tick($dInfo->exclude_specials) ?>
          <label for="exclude_specials" class="custom-control-label text-muted"><small><?= TEXT_EXCLUDE_SPECIALS ?>&nbsp;</small></label>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-3">
    <div class="col-sm-4">
    <?= TEXT_APPLIES_TO; ?>
    </div>
    <div class="col-sm-4">
      <?= '<div class="custom-control custom-radio custom-control-inline">' . (new Tickable('applies_to', ['value' => '5', 'class' => 'custom-control-input', 'id' => 'cOt', 'onclick' => 'applies_to_onclick();', 'aria-describedby' => TEXT_ORDER_SUBTOTAL], 'radio'))->tick($dInfo->orders_total == 2) . '<label class="custom-control-label" for="cOt">' . TEXT_ORDER_SUBTOTAL . '</label></div>' ?>
    </div>
    <div class="col-sm-4">
      <?= '<div class="custom-control custom-radio custom-control-inline">' . (new Tickable('applies_to', ['value' => '6', 'class' => 'custom-control-input', 'id' => 'cShip', 'onclick' => 'applies_to_onclick();', 'aria-describedby' => TEXT_SHIPPING], 'radio'))->tick($dInfo->shipping == 2) . '<label class="custom-control-label" for="cShip">' . TEXT_SHIPPING . '</label></div>' ?>
    </div>
  </div>

  <div class="row">
    <div class="col-sm-4">
      <?= '<div class="custom-control custom-radio custom-control-inline">' . (new Tickable('applies_to', ['value' => '1', 'class' => 'custom-control-input', 'id' => 'cCProd', 'onclick' => 'applies_to_onclick();', 'aria-describedby' => TEXT_PRODUCTS], 'radio'))->tick(is_array($dInfo->products_id)) . '<label class="custom-control-label" for="cCProd">' . TEXT_PRODUCTS . '</label></div>' ?>

      <?php
      $products_selector = new Select('products_id[]', Products::list_options(), ['size' => '10', 'multiple' => null, 'style' => 'width: 350px;', 'id' => 'products_id', 'disabled' => null]);
      if (is_array($dInfo->products_id)) {
        foreach ($dInfo->products_id as $v) {
          $products_selector = str_replace('<option value="' . $v . '">', '<option value="' . $v . '" selected>', $products_selector);
        }
      }

      $excluded_products_selector = new Select('excluded_products_id[]', Products::list_options(), ['size' => '10', 'multiple' => null, 'style' => 'width: 350px;', 'id' => 'excluded_products_id', 'disabled' => null]);
      if (is_array($dInfo->excluded_products_id)) {
        foreach ($dInfo->excluded_products_id as $v) {
          $excluded_products_selector = str_replace('<option value="' . $v . '">', '<option value="' . $v . '" selected>', $excluded_products_selector);
        }
      }
      ?>
      <div class="form-group">
        <?= $products_selector ?>
      </div>

      <p class="mt-4 mb-2"><?= TEXT_EXCLUDED_PRODUCTS ?></p>
      <div class="form-group">
        <?= $excluded_products_selector ?>
      </div>


    </div>
    <div class="col-sm-4">
    <?php
      $categories_id = new Select('categories_id[]', Guarantor::ensure_global('category_tree')->get_selections([], '0'), ['size' => '10', 'multiple' => null, 'style' => 'width: 350px;', 'id' => 'categories_id', 'disabled' => null]);
      if (is_array($dInfo->categories_id)) {
        foreach ($dInfo->categories_id as $v) {
          $categories_id = str_replace('<option value="' . $v . '">', '<option value="' . $v . '" selected>', $categories_id);
        }
      }
    ?>
    <?= '<div class="custom-control custom-radio custom-control-inline">' . (new Tickable('applies_to', ['value' => '2', 'class' => 'custom-control-input', 'id' => 'cCat', 'onclick' => 'applies_to_onclick();', 'aria-describedby' => TEXT_CATEGORIES], 'radio'))->tick(is_array($dInfo->categories_id)) . '<label class="custom-control-label" for="cCat">' . TEXT_CATEGORIES . '</label></div>' ?>
      <div class="form-group">
        <?= $categories_id ?>
      </div>

      <?php
      $sql = $customer_data->add_order_by($customer_data->build_read(['sortable_name', 'email_address'], 'customers'), ['sortable_name']);
      $customers_query = $db->query($sql);
      while ($customers_values = $customers_query->fetch_assoc()) {
        $customers[] = [
          'id' => $customer_data->get('email_address', $customers_values),
          'text' => $customer_data->get('sortable_name', $customers_values) . ' (' . $customer_data->get('email_address', $customers_values) . ')',
        ];
      }
      $customers_id = new Select('customers_id[]', $customers, ['size' => '10', 'multiple' => null, 'style' => 'width: 350px;', 'id' => 'customers_id', 'disabled' => null]);

      if (is_array($dInfo->customers_id)) {
        foreach ($dInfo->customers_id as $v) {
          $customers_id = str_replace('<option value="' . $v . '">', '<option value="' . $v . '" selected>', $customers_id);
        }
      }
      ?>
      <div class="form-group row align-items-center mb-0">
        <div class="col-form-label col-4"><?= TEXT_CUSTOMERS ?></div>
        <div class="col-sm-8 custom-control custom-switch">
          <?= (new Tickable('customers', ['value' => '1', 'class' => 'custom-control-input', 'id' => 'customers', 'onchange' => 'customers_onclick();'], 'checkbox'))->tick(is_array($dInfo->customers_id)) ?>
          <label for="customers" class="custom-control-label text-muted"><small><?= TEXT_CUSTOMERS_SELECT ?>&nbsp;</small></label>
        </div>
      </div>
      <div class="form-group">
        <?= $customers_id; ?>
      </div>
    </div>

    <div class="col-sm-4">
      <?= '<div class="custom-control custom-radio custom-control-inline">' . (new Tickable('applies_to', ['value' => '4', 'class' => 'custom-control-input', 'id' => 'cManu', 'onclick' => 'applies_to_onclick();', 'aria-describedby' => TEXT_MANUFACTURERS], 'radio'))->tick(is_array($dInfo->manufacturers_id)) . '<label class="custom-control-label" for="cManu">' . TEXT_MANUFACTURERS . '</label></div>' ?>
      <?php
        $manufacturers_id = new Select('manufacturers_id[]', $manufacturers_array, ['size' => '10', 'multiple' => null, 'style' => 'width: 350px;', 'id' => 'manufacturers_id', 'disabled' => null]);
        if (is_array($dInfo->manufacturers_id)) {
          foreach ($dInfo->manufacturers_id as $v) {
            $manufacturers_id = str_replace('<option value="' . $v . '">', '<option value="' . $v . '" selected>', $manufacturers_id);
          }
        }
      ?>
      <div class="form-group">
        <?= $manufacturers_id ?>
      </div>
    </div>
  </div>

  <?= (new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success btn-block btn-lg')) ?>
</form>

<script>
$(document).ready(function() {
    if (<?= ((isset($action) && $action == 'new') ? 'true' : 'false')?>) {
        onload();
    }
});
function applies_to_onclick() {
  var a = document.new_discount_code.applies_to, b = document.getElementById("excluded_products_id"), c = document.getElementById("number_of_products"), d = document.getElementById("exclude_specials"), e = document.getElementById("products_id"), f = document.getElementById("categories_id"), g = document.getElementById("manufacturers_id");
  for (var ﻿i = 0, n = a.length; i < n; i++) if (a[i].checked) { b.disabled = (a[i].value == 2 || a[i].value == 4 ? false : true); c.disabled = (a[i].value == 3 || a[i].value == 5 || a[i].value == 6 ? true : false); d.disabled = (a[i].value == 3 || a[i].value == 6 ? true : false); e.disabled = (a[i].value == 1 ? false : true); f.disabled = (a[i].value == 2 ? false : true); g.disabled = (a[i].value == 4 ? false : true) }
  }
function customers_onclick() {
  var d = document.getElementById("customers"), e = document.getElementById("customers_id"); e.disabled = !d.checked;
}
function onload() {
  applies_to_onclick();
  customers_onclick();
}
$('#expires_date').datepicker({
  dateFormat: 'yy-mm-dd'
});
</script>
