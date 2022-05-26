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

  if (is_object($table_definition['info'] ?? null)) {
    $dInfo = $table_definition['info'];
    $heading = $dInfo->discount_codes;

    $link = $GLOBALS['Admin']->link('discount_codes.php')->retain_query_except()->set_parameter('dID', $dInfo->discount_codes_id);
    $contents[] = [
      'class' => 'text-center',
      'text' => $GLOBALS['Admin']->button(IMAGE_EDIT, 'fas fa-cogs', 'btn-warning mr-2', (clone $link)->set_parameter('action', 'new'))
              . $GLOBALS['Admin']->button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger mr-2', $link->set_parameter('action', 'delete')),
    ];
    $contents[] = ['text' => '<br>'];
    $contents[] = ['text' => TABLE_HEADING_NUMBER_OF_ORDERS_FULL . ':&nbsp;' . $dInfo->number_of_orders];
    if ($dInfo->minimum_order_amount > 0) $contents[] = ['text' => TABLE_HEADING_MINIMUM_ORDER_AMOUNT_FULL . ':&nbsp;' . $GLOBALS['currencies']->format($dInfo->minimum_order_amount)];
    if ($dInfo->exclude_specials == 1) $contents[] = ['text' => '<i class="fas fa-check text-success"></i>' . '&nbsp;' . TEXT_EXCLUDE_SPECIALS];
    if ($dInfo->newsletter == 1) $contents[] = ['text' => '<i class="fas fa-check text-success"></i>' . '&nbsp;' . TEXT_NEWSLETTER];
    if ($dInfo->order_number > 0) $contents[] = ['text' => '<i class="fas fa-check text-success"></i>' . '&nbsp;' . TEXT_ORDER_NUMBER . $dInfo->order_number];
    if ($dInfo->number_of_use != 0) $contents[] = ['text' => TABLE_HEADING_NUMBER_OF_USE . ' ' . $dInfo->number_of_use];
    if ($dInfo->number_of_products != 0) $contents[] = ['text' => TABLE_HEADING_NUMBER_OF_PRODUCTS . ' ' . $dInfo->number_of_products];
    if (!empty($dInfo->customers_id)) {
      $select_string = '';
      $customers_query = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT CONCAT(customers_lastname, ', ', customers_firstname, ' (', customers_email_address, ')') AS customers_info
  FROM customers
  WHERE customers_email_address IN (%s)
ORDER BY customers_lastname, customers_firstname
EOSQL
      , (int)$dInfo->customers_id));

      while ( $customers = $customers_query->fetch_assoc() ) {
        $select_string .= (empty($select_string) ? '' : '<br>') . $customers['customers_info'];
      }
      if (!empty($select_string)) {
        $contents[] = ['text' => '<br>' . TEXT_INFO_CUSTOMERS . '<br>' . $select_string];
      }
    }
  }
