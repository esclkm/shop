<?php

/**
 * Shop
 *
 * @version 3.00
 * @author esclkm
 * @copyright (с) 2010 esclkm
 */
// Generated by Cotonti developer tool (littledev.ru)
defined('COT_CODE') or die('Wrong URL.');
/* @var $db CotDB */
/* @var $cache Cache */
/* @var $t Xtemplate */

//$adminpath[] = $L['Custom_configs'];

$t->assign(array(
	'DELIVERY_URL' => cot_url('admin', array('m' => 'other', 'p' => 'shop', 'n' => 'delivery')),
	'DELIVERY_PRICES_URL' => cot_url('admin', array('m' => 'other', 'p' => 'shop', 'n' => 'delivery.prices')),
	'PAYMENTS_URL' => cot_url('admin', array('m' => 'other', 'p' => 'shop', 'n' => 'payments')),
));
cot_display_messages($t);