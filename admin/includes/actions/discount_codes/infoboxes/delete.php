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

    $link = $GLOBALS['Admin']->link('discount_codes.php')->retain_query_except(['action']);
    $contents = ['form' => new Form('discount_codes', ($GLOBALS['Admin']->link('discount_codes.php'))->retain_query_except(['action'])->set_parameter('action', 'delete_confirm'))];
    $contents[] = ['text' => TEXT_INFO_DELETE_INTRO];
    $contents[] = [
      'class' => 'text-center',
      'text' => new Button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger text-white mr-2')
              . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $link),
    ];
  }
