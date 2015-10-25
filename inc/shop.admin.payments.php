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
$adminpath[] = $L['shop_payments'];

if($a == 'add')
{
	$radd = array();
	$radd['odp_title'] = cot_import('rtitle', 'P', "TXT");
	$radd['odp_desc'] = cot_import('rdesc', 'P', "HTM");
	$radd['odp_driver'] = cot_import('rdriver', 'P', "TXT");

	if(empty($radd['odp_title']))
	{
		cot_error($L['err_no_name']);
	}
	if(!cot_error_found())
	{
		$db->insert($db_orders_payments, $radd);
	}
	$cache && $cache->clear();
	cot_redirect(cot_url('admin', array('m' => 'other', 'p' => 'shop', 'n' => 'payments'), '', true));
}
if($a == 'update')
{

	$titles = cot_import('rtitle', 'P', "ARR");
	$descs = cot_import('rdesc', 'P', "ARR");
	$drivers = cot_import('rdriver', 'P', "ARR");

	foreach($titles as $id => $ti)
	{
		$id = cot_import($id, 'D', 'INT');
		$redit = array();
		$redit['odp_title'] = cot_import($titles[$id], 'D', "TXT");
		$redit['odp_desc'] = cot_import($descs[$id], 'D', "HTM");
		$redit['odp_driver'] = cot_import($drivers[$id], 'D', "TXT");		
		$db->update($db_orders_payments, $redit, "odp_id=".(int)$id);

	}
	$cache && $cache->clear();
	cot_redirect(cot_url('admin', array('m' => 'other', 'p' => 'shop', 'n' => 'payments'), '', true));
}
if($a == 'delete')
{
	cot_check_xg();
	$id = cot_import('id', 'G', "ALP");
	$db->delete($db_orders_payments, "odp_id=".(int)$id);
	$cache && $cache->clear();
	cot_redirect(cot_url('admin', array('m' => 'other', 'p' => 'shop', 'n' => 'payments'), '', true));
}

$rows = $db->query("SELECT * FROM $db_orders_payments ORDER BY odp_id ASC")->fetchAll();
foreach ($rows as $row)
{
	$t->assign(array(
		'FORM_EDIT_ID' => $row['odp_id'],
		'FORM_EDIT_TITLE_TEXT' => htmlspecialchars($row['odp_title']),
		'FORM_EDIT_TITLE' => cot_inputbox('text', 'rtitle['.$row['odp_id'].']', $row['odp_title'], array('maxlength' => '255')),
		'FORM_EDIT_DESC' => cot_textarea('rdesc['.$row['odp_id'].']',  $row['odp_desc'], 5, 60),
		'FORM_EDIT_DRIVER' => cot_inputbox('text', 'rdriver['.$row['odp_id'].']', $row['odp_driver'], array('maxlength' => '255')),
		'FORM_EDIT_DELETE_URL' => cot_url('admin', array('m' => 'other', 'p' => 'shop', 'n' => 'payments', 'a' => 'delete', 'id' => $row['odp_id'],'x' => $sys['xk'])),
	));
	$t->parse('MAIN.ROW');
}
if(!count($rows))
{
	$t->parse('MAIN.NOROW');
}
$t->assign(array(
	'FORM_EDIT_URL' => cot_url('admin', array('m' => 'other', 'p' => 'shop', 'n' => 'payments', 'a' => 'update')),
	'FORM_ADD_URL' => cot_url('admin', array('m' => 'other', 'p' => 'shop', 'n' => 'payments', 'a' => 'add')),
	'FORM_ADD_TITLE' => cot_inputbox('text', 'rtitle', '', array('maxlength' => '255')),
	'FORM_ADD_DESC' => cot_textarea('rdesc', '', 5, 60),
	'FORM_ADD_DRIVER' => cot_inputbox('text', 'rdriver', '', array('maxlength' => '255')),
));
cot_display_messages($t);