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

  $db->query(sprintf(<<<'EOSQL'
UPDATE discount_codes
  SET status = %s
  WHERE discount_codes_id = %s
  LIMIT 1
EOSQL
  , (int)$_GET['flag'], (int)$_GET['dID']));

  return $Admin->link('discount_codes.php')->retain_query_except(['action', 'flag']);
