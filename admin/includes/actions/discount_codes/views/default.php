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

    $discount_sql = <<<'EOSQL'
SELECT *
  FROM discount_codes
  ORDER BY discount_codes_id DESC
EOSQL;

  function get_applies_to($discount_codes) {
    global $db;

    $discount_codes['applies_to'] = '';
    if (!empty($discount_codes['orders_total'])) {
      if ($discount_codes['orders_total'] == 2) {
        $discount_codes['applies_to'] = TEXT_ORDER_SUBTOTAL;
      }
    } elseif (!empty($discount_codes['shipping'])) {
      if ($discount_codes['shipping'] == 2) {
        $discount_codes['applies_to'] = TEXT_SHIPPING;
      }
    } elseif (!empty($discount_codes['products_id'])) {
      $discount_codes['applies_to'] = TEXT_PRODUCTS;
      foreach (explode(',', $discount_codes['products_id']) as $products_id) {
        $discount_codes['applies_to'] .= (empty($discount_codes['applies_to']) ? '' : '<br>') . Product::fetch_name($products_id);
      }
    } elseif (!empty($discount_codes['categories_id'])) {
      $discount_codes['applies_to'] = TEXT_CATEGORIES;
      $discount_codes['applies_to'] .= (empty($discount_codes['applies_to']) ? '' : '<br>') . Categories::draw_breadcrumbs(explode(',', $discount_codes['categories_id']));
    } elseif (!empty($discount_codes['manufacturers_id'])) {
      $discount_codes['applies_to'] = TEXT_MANUFACTURERS;
      $manufacturer_query = $db->query(sprintf(<<<'EOSQL'
SELECT manufacturers_name
  FROM manufacturers
  WHERE manufacturers_id IN (%s)
  ORDER BY manufacturers_name
EOSQL
      , $discount_codes['manufacturers_id']));
      while ( $manufacturer = $manufacturer_query->fetch_assoc() ) {
        $discount_codes['applies_to'] .= (empty($discount_codes['applies_to']) ? '' : '<br>') . $manufacturer['manufacturers_name'];
      }
    }
    if (!empty($discount_codes['excluded_products_id'])) {
      $discount_codes['applies_to'] .= '<br>' . TEXT_EXCLUDED_PRODUCTS;
      foreach (explode(',', $discount_codes['excluded_products_id']) as $excluded_products_id) {
        $discount_codes['applies_to'] .= (empty($discount_codes['applies_to']) ? '' : '<br>') . Product::fetch_name($excluded_products_id);
      }
    }

    return $discount_codes;

  }

  function get_discount_value($discount_codes) {
    if (strpos($discount_codes['discount_values'], '%') == true) {
      $discount_codes['discount_codes_value'] = $discount_codes['discount_values'];
    } else {
      $discount_codes['discount_codes_value'] = $GLOBALS['currencies']->format($discount_codes['discount_values']);
    }

    return $discount_codes;
  }

  $table_definition = [
    'columns' => [
      [
        'name' => TABLE_HEADING_DISCOUNT_CODE,
        'function' => function (&$row) {
          return $row['discount_codes'];
        },
      ],
      [
        'name' => TABLE_HEADING_DESCRIPTION,
        'function' => function (&$row) {
          return $row['discount_description'];
        },
      ],
      [
        'name' => TABLE_HEADING_APPLIES_TO,
        'function' => function (&$row) {
          $applies_to = get_applies_to($row);
          return $applies_to['applies_to'];
        },
      ],
      [
        'name' => TABLE_HEADING_DISCOUNT,
        'class' => 'text-center',
        'function' => function (&$row) {
          $applies_to = get_discount_value($row);
          return $applies_to['discount_codes_value'];
        },
      ],
      [
        'name' => TABLE_HEADING_EXPIRY,
        'class' => 'text-center',
        'function' => function (&$row) {
          return $row['expires_date'] == '0000-00-00' ? '-' : Date::abridge($row['expires_date']);
        },
      ],
      [
        'name' => TABLE_HEADING_STATUS,
        'class' => 'text-center',
        'function' => function (&$row) {
          $href = (clone $row['onclick'])->set_parameter('action', 'set_flag');
          return ($row['status'] == '1')
               ? '<i class="fas fa-check-circle text-success"></i> <a href="' . $href->set_parameter('flag', '0')  . '"><i class="fas fa-times-circle text-muted"></i></a>'
               : '<a href="' . $href->set_parameter('flag', '1') . '"><i class="fas fa-check-circle text-muted"></i></a> <i class="fas fa-times-circle text-danger"></i>';
        },
      ],
      [
        'name' => TABLE_HEADING_ACTION,
        'class' => 'text-right',
        'function' => function ($row) {
          return (isset($row['info']))
               ? '<a href="' . (clone $row['onclick'])->set_parameter('action', 'new')  . '"><i class="fas fa-chevron-circle-right text-info"></i> </a>'
               : '<a href="' . $row['onclick'] . '"><i class="fas fa-info-circle text-muted"></i></a>';
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_DISCOUNT_CODES,
    'page' => $_GET['page'] ?? null,
    'web_id' => 'dID',
    'db_id' => 'discount_codes_id',
    'rows_per_page' => MAX_DISPLAY_SEARCH_RESULTS,
    'sql' => $discount_sql,
  ];

  $table_definition['split'] = new Paginator($table_definition);

  $table_definition['split']->display_table();

?>

</div>
