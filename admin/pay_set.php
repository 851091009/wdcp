<?php

/*
# WDlinux Control Panel,online management of Linux servers, virtual hosts
# Wdcdn system
# Author:wdlinux QQ:12571192
# Url:http://www.wdlinux.cn/wdcdn
# Last Updated 2011.05
*/

require_once "../inc/common.inc.php";
require_once "../login.php";
if ($wdcp_gid!=1) exit;
//if ($wdcdn_gid!=1 or empty($_SESSION['admin'])) exit;

$pay_conf="../data/pay_conf.php";
if (isset($_POST['Submit'])) {
	$name=chop($_POST['name']);
	$id=chop($_POST['id']);
	$key=chop($_POST['key']);
	$account=chop($_POST['account']);
	if (empty($name) or empty($id) or empty($key) or empty($account)) go_back("������������ϸ���!");
	$page1=chop($_POST['page1']);
	$page2=chop($_POST['page2']);
	$page3=chop($_POST['page3']);
	$pay_type=intval($_POST['pay_type']);
	
	config_update("pay_partner",$id,"���������ID");
	config_update("pay_key",$key,"��ȫ������");
	config_update("pay_seller_email",$account,"ǩԼ֧�����˺Ż�����֧�����ʻ�");
	config_update("pay_notify_url",$page1,"������֪ͨ��ҳ��");
	config_update("pay_return_url",$page2,"��������ת��ҳ��");
	config_update("pay_show_url",$page3,"��վ��Ʒ��չʾ��ַ");
	config_update("pay_mainname",$name,"�տ����");
	config_update("pay_type",$pay_type,"�ӿ�����");
	config_updatef();
	optlog($wdcp_uid,"�޸���֧������",0,0);//
	str_go_url("����ɹ���",0);
}
//if (file_exists($pay_conf)) require_once $pay_conf;
//else {
if (!isset($pay_notify_url))
	if ($pay_type==2)
		$pay_notify_url="http://".$_SERVER["SERVER_NAME"]."/alipays/notify_url.php";
	else
		$pay_notify_url="http://".$_SERVER["SERVER_NAME"]."/alipay/notify_url.php";
if (!isset($pay_return_url))
	if ($pay_type==2)
		$pay_return_url="http://".$_SERVER["SERVER_NAME"]."/alipays/return_url.php";
	else
		$pay_return_url="http://".$_SERVER["SERVER_NAME"]."/alipay/return_url.php";
if (!isset($pay_show_url))
	$pay_show_url="http://".$_SERVER["SERVER_NAME"];
if ($pay_type==2) $pay_return_url=$pay_notify_url;

require_once(G_T("admin/pay_set.htm"));