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
  $db->query(sprintf(<<<'EOSQL'
DELETE
  FROM customers_to_discount_codes
  WHERE discount_codes_id = %s
EOSQL
  , (int)$_GET['dID']));

  $db->query(sprintf(<<<'EOSQL'
DELETE
  FROM discount_codes
  WHERE discount_codes_id = %s
  LIMIT 1
EOSQL
  , (int)$_GET['dID']));

  $messageStack->add_session(SUCCESS_DISCOUNT_CODE_REMOVED, 'success');

  return $Admin->link('discount_codes.php')->retain_query_except(['action', 'dD']);
