<?php
/*
  Discount Code 5.4.2. Phoenix
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

 const HEADING_TITLE = 'Códigos de Descuento';

 const TABLE_HEADING_DISCOUNT_CODE = 'Código de Descuento';
 const TABLE_HEADING_DESCRIPTION = 'Descripción';                                                   
 const TABLE_HEADING_APPLIES_TO = 'Aplica a';
 const TABLE_HEADING_DISCOUNT = 'Descuento';
 const TABLE_HEADING_MINIMUM_ORDER_AMOUNT = 'Min Sub-Total<br><small>Sub-Total Mínimo del Pedido</small>';
 const TABLE_HEADING_MINIMUM_ORDER_AMOUNT_FULL = 'Sub-Total Mínimo del Pedido';
 const TABLE_HEADING_EXPIRY = 'Expira';
 const TABLE_HEADING_NUMBER_OF_ORDERS = 'Pedidos';
 const TABLE_HEADING_NUMBER_OF_ORDERS_FULL = 'Número de Pedidos';
 const TABLE_HEADING_ORDER_INFO = 'Info del Pedido';
 const TABLE_HEADING_ORDER_INFO_FULL = 'Incluir código del pedido en la info del pedido';
 const TABLE_HEADING_NUMBER_OF_USE = 'Usos por cada Código:';
 const TABLE_HEADING_NUMBER_OF_PRODUCTS = 'Número de productos a los que aplicar el descuento:';
 const TABLE_HEADING_STATUS = 'Estado';
 const TABLE_HEADING_ACTION = 'Acción';

 const TEXT_DISCOUNT_CODE = 'Código de Descuento:';
 const TEXT_DISCOUNT_DESCRIPTION = 'Descripción del descuento:<br><small>Solo para uso interno en Admin</small>';
 const TEXT_APPLIES_TO = 'Aplica a:';
 const TEXT_DISCOUNT = 'Descuento:';
 const TEXT_DISCOUNT_EXPL = '<small>Se puede introducir una cantidad fija o un porcentaje, por ejemplo: <strong>5</strong> o <strong>10%</strong><br>Los descuentos para gastos de envío sólo se pueden poner en porcentajes. Escribe <strong>100%</strong> para poner envío gratuito.</small>';
 const TEXT_MINIMUM_ORDER_SUB_TOTAL = 'Sub-Total Mínimo del Pedido:';
 const TEXT_NUMBER_OF_USE = 'Usos por cada Código:<br><small>Deje vacío, para uso ilimitado. <br>Si se usa junto con clientes, por cliente, de lo contrario, uso total.</small>';
 const TEXT_NUMBER_OF_PRODUCTS = 'Número de productos para aplicar el descuento:<br><small>Para un pedido, deje vacío para uso ilimitado';
 const TEXT_EXPIRY = 'Fecha de Expiración:<br><small>YYYY-MM-DD</small>';
 const TEXT_ORDER_INFO = 'Info del pedido';
 const TEXT_ORDER_INFO_INCLUDE = 'Incluir código de descuento';
 const TEXT_EXCLUDE_SPECIALS = 'No Aplicar para Productos en Ofertas';
 const TEXT_NEWSLETTER = 'Sólo para suscritos al Boletín';
 const TEXT_ORDER_NUMBER = 'Descontar en el Pedido Nº';
 const TEXT_PRODUCTS = 'Productos:';
 const TEXT_CATEGORIES = 'Categorías:';
 const TEXT_MANUFACTURERS = 'Fabricantes:';
 const TEXT_CUSTOMERS = 'Clientes:';
 const TEXT_NEWSLETTER_CUSTOMERS = 'Suscritos al Boletín';
 const TEXT_NEWSLETTER_ONLY = 'Sólo clientes suscritos';
 const TEXT_ORDER_CUSTOMERS = 'Se aplia sólo al Pedido Nº:<br><small>Cero, no aplica</small>';
 const TEXT_INFO_CUSTOMERS = 'Descuento asignado a los clientes:';
 const TEXT_EXCLUDED_PRODUCTS = 'Productos Excluidos:';
 const TEXT_ORDER_TOTAL = 'Total del Pedido';
 const TEXT_ORDER_SUBTOTAL = 'Sub-Total del Pedido';
 const TEXT_INFO_DELETE_INTRO = '¿Seguro que quieres eliminar este código de descuento?';
 const TEXT_SHIPPING = 'Envío';

 const SUCCESS_DISCOUNT_CODE_INSERTED = 'Correcto: Se ha añadido el código de descuento.';
 const SUCCESS_DISCOUNT_CODE_UPDATED = 'Correcto: Se ha actualizado el código de descuento.';
 const SUCCESS_DISCOUNT_CODE_REMOVED = 'Correcto: Se ha eliminado el código de descuento.';
 const ERROR_DISCOUNT_CODE_INSERTED = 'Error: El código de descuento no se ha añadido.';

 const TEXT_DISPLAY_NUMBER_OF_DISCOUNT_CODES = 'Mostrando  <b>%d</b> a <b>%d</b> (de <b>%d</b> códigos de descuento)';
 const BUTTON_INSERT_NEW_DISCOUNT_CODE = 'Crear Cód. Descuento';

 const WARNING_OT_MODULE_INSTALL = '¡El módulo Total de pedido de código de descuento no está instalado!<br>Los módulos de descuento en el carrito de compras y la página de pago no se mostrarán. <a href="%s">Instalar ahora</a>';
 const WARNING_OT_MODULE_SWITCH = '¡El módulo Total de pedidos de código de descuento no está activado!<br>Los módulos de descuento en el carrito de compras y la página de pago no se mostrarán. <a href="%s">Activar ahora</a>';
