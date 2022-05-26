<?php
/*
  $Id$

  Discount Code 5.7.0. Phoenix
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

  foreach ( $cl_box_groups as &$group ) {
    if ( $group['heading'] == BOX_HEADING_CATALOG ) {
      $group['apps'][] = [
        'code' => 'discount_codes.php',
        'title' => BOX_CATALOG_DISCOUNT_CODES,
        'link' => $GLOBALS['Admin']->link('discount_codes.php'),
      ];

      break;
    }
  }
