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
$adminpath[] = $L['shop_delivery'];

if($a == 'add')
{
	$radd = array();
	$radd['odd_title'] = cot_import('rtitle', 'P', "TXT");
	$radd['odd_desc'] = cot_import('rdesc', 'P', "HTM");

	if(empty($radd['odd_title']))
	{
		cot_error($L['err_no_name']);
	}
	if(!cot_error_found())
	{
		$db->insert($db_orders_delivery, $radd);
	}
	$cache && $cache->clear();
	cot_redirect(cot_url('admin', array('m' => 'other', 'p' => 'shop', 'n' => 'delivery'), '', true));
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
		$redit['odd_title'] = cot_import($titles[$id], 'D', "TXT");
		$redit['odd_desc'] = cot_import($descs[$id], 'D', "HTM");		
		$db->update($db_orders_delivery, $redit, "odd_id=".(int)$id);

	}
	$cache && $cache->clear();
	cot_redirect(cot_url('admin', array('m' => 'other', 'p' => 'shop', 'n' => 'delivery'), '', true));
}
if($a == 'delete')
{
	cot_check_xg();
	$id = cot_import('id', 'G', "ALP");
	$db->delete($db_orders_delivery, "odd_id=".(int)$id);
	$cache && $cache->clear();
	cot_redirect(cot_url('admin', array('m' => 'other', 'p' => 'shop', 'n' => 'delivery'), '', true));
}

$rows = $db->query("SELECT * FROM $db_orders_delivery ORDER BY odd_id ASC")->fetchAll();
foreach ($rows as $row)
{
	$t->assign(array(
		'FORM_EDIT_ID' => $row['odd_id'],
		'FORM_EDIT_TITLE_TEXT' => htmlspecialchars($row['odd_title']),
		'FORM_EDIT_TITLE' => cot_inputbox('text', 'rtitle['.$row['odd_id'].']', $row['odd_title'], array('maxlength' => '255')),
		'FORM_EDIT_DESC' => cot_textarea('rdesc['.$row['odd_id'].']',  $row['odd_desc'], 5, 60),
		'FORM_EDIT_DELETE_URL' => cot_url('admin', array('m' => 'other', 'p' => 'shop', 'n' => 'delivery', 'a' => 'delete', 'id' => $row['odd_id'],'x' => $sys['xk'])),
	));
	$t->parse('MAIN.ROW');
}
if(!count($rows))
{
	$t->parse('MAIN.NOROW');
}
$t->assign(array(
	'FORM_EDIT_URL' => cot_url('admin', array('m' => 'other', 'p' => 'shop', 'n' => 'delivery', 'a' => 'update')),
	'FORM_ADD_URL' => cot_url('admin', array('m' => 'other', 'p' => 'shop', 'n' => 'delivery', 'a' => 'add')),
	'FORM_ADD_TITLE' => cot_inputbox('text', 'rtitle', '', array('maxlength' => '255')),
	'FORM_ADD_DESC' => cot_textarea('rdesc', '', 5, 60),
));
cot_display_messages($t);
