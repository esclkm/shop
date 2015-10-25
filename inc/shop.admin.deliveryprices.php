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

$adminpath[] = array(cot_url('admin', array('m' => 'other', 'p' => 'shop', 'n' => 'main')), $L['Edit']);
$adminpath[] = $L['shop_delivery_prices'];

$rows = $db->query("SELECT * FROM $db_orders_delivery ORDER BY odd_id ASC");
$deliveries = array();
foreach ($rows as $row)
{
	$deliveries[$row['odd_id']] = $row['odd_title'];
}
if(!count($deliveries))
{
	cot_error($L['shop_error_nodeliveries']);
	cot_redirect(cot_url('admin', array('m' => 'other', 'p' => 'shop', 'n' => 'main'), '', true));
}

if($a == 'add')
{
	$radd = array();
	$radd['oddp_mintotal'] = cot_import('rmintotal', 'P', "NUM");
	$radd['oddp_price'] = cot_import('rprice', 'P', "NUM");
	$radd['oddp_type'] = cot_import('rtype', 'P', "BOL") ? 1 : 0;
	$radd['oddp_oddid'] = cot_import('roddid', 'P', "INT");

	if(empty($radd['oddp_oddid']))
	{
		cot_error($L['err_no_name']);
	}
	if(!cot_error_found())
	{
		$db->insert($db_orders_delivery_prices, $radd);
	}
	$cache && $cache->clear();
	cot_redirect(cot_url('admin', array('m' => 'other', 'p' => 'shop', 'n' => 'deliveryprices'), '', true));
}
if($a == 'update')
{
	$mintotals = cot_import('rmintotal', 'P', "ARR");
	$prices = cot_import('rprice', 'P', "ARR");
	$types = cot_import('rtype', 'P', "ARR");
	$oddids = cot_import('roddid', 'P', "ARR");

	foreach($oddids as $id => $ti)
	{
		$id = cot_import($id, 'D', 'INT');
		$redit = array();
		$redit['oddp_mintotal'] = cot_import($mintotals[$id], 'D', "NUM");
		$redit['oddp_price'] = cot_import($prices[$id], 'D', "NUM");
		$redit['oddp_type'] = cot_import($types[$id], 'D', "BOL") ? 1 : 0;
		$redit['oddp_oddid'] = cot_import($oddids[$id], 'D', "INT");

		$db->update($db_orders_delivery_prices, $redit, "oddp_id=".(int)$id);

	}
	$cache && $cache->clear();
	cot_redirect(cot_url('admin', array('m' => 'other', 'p' => 'shop', 'n' => 'deliveryprices'), '', true));
}
if($a == 'delete')
{
	cot_check_xg();
	$id = cot_import('id', 'G', "ALP");
	$db->delete($db_orders_delivery_prices, "oddp_id=".(int)$id);
	$cache && $cache->clear();
	cot_redirect(cot_url('admin', array('m' => 'other', 'p' => 'shop', 'n' => 'deliveryprices'), '', true));
}

$rows = $db->query("SELECT * FROM $db_orders_delivery_prices ORDER BY oddp_oddid ASC, oddp_mintotal ASC, oddp_id ASC")->fetchAll();
foreach ($rows as $row)
{
	$t->assign(array(
		'FORM_EDIT_ID' => $row['oddp_id'],
		'FORM_EDIT_MIN' => cot_inputbox('text', 'rmintotal['.$row['oddp_id'].']', $row['oddp_mintotal'], array('maxlength' => '15')),
		'FORM_EDIT_PRICE' => cot_inputbox('text', 'rprice['.$row['oddp_id'].']', $row['oddp_price'], array('maxlength' => '15')),
		'FORM_EDIT_PERCENT' => cot_checkbox($row['oddp_type'], 'rtype['.$row['oddp_id'].']'),
		'FORM_EDIT_DELIVERY' => cot_selectbox($row['oddp_oddid'], 'roddid['.$row['oddp_id'].']', array_keys($deliveries), array_values($deliveries), false),
		'FORM_EDIT_DELETE_URL' => cot_url('admin', array('m' => 'other', 'p' => 'shop', 'n' => 'deliveryprices', 'a' => 'delete', 'id' => $row['oddp_id'],'x' => $sys['xk'])),
	));
	$t->parse('MAIN.ROW');
}
if(!count($rows))
{
	$t->parse('MAIN.NOROW');
}
$t->assign(array(
	'FORM_EDIT_URL' => cot_url('admin', array('m' => 'other', 'p' => 'shop', 'n' => 'deliveryprices', 'a' => 'update')),
	'FORM_ADD_URL' => cot_url('admin', array('m' => 'other', 'p' => 'shop', 'n' => 'deliveryprices', 'a' => 'add')),
	'FORM_ADD_MIN' => cot_inputbox('text', 'rmintotal', "0", array('maxlength' => '15')),
	'FORM_ADD_PRICE' => cot_inputbox('text', 'rprice', "0", array('maxlength' => '15')),
	'FORM_ADD_PERCENT' => cot_checkbox(false, 'rtype'),
	'FORM_ADD_DELIVERY' => cot_selectbox("", 'roddid', array_keys($deliveries), array_values($deliveries), false),

	'FORM_ADD_TITLE' => cot_inputbox('text', 'rtitle', '', array('maxlength' => '255')),
	'FORM_ADD_DESC' => cot_textarea('rdesc', '', 5, 60),
));
cot_display_messages($t);
//		