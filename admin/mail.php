<?php

/*
# WDlinux Control Panel,online management of Linux servers, virtual hosts
# Wdcdn system
# Author:wdlinux QQ:12571192
# Url:http://www.wdlinux.cn/wdcdn
# Last Updated 2011.05
*/

require_once "../inc/common.inc.php";
require_once "../inc/page_class.php";
require_once "../login.php";
require_once "../inc/admlogin.php";
if ($wdcp_gid!=1) exit;
//if ($wdcdn_gid!=1 or empty($_SESSION['admin'])) exit;


if (empty($mail_port)) $mail_port=25;


if (isset($_POST['Submit'])) {
	wdl_demo_sys();
	$mailsend=intval($_POST['mailsend']);
	$mail_server=chop($_POST['mail_server']);
	$mail_port=chop($_POST['mail_port']);
	$mail_auth=chop($_POST['mail_auth']);
	$mail_from=chop($_POST['mail_from']);
	$mail_auth_name=chop($_POST['mail_auth_name']);
	$mail_auth_passwd=chop($_POST['mail_auth_passwd']);
	
	config_update("mailsend",$mailsend,"�ʼ����ͷ�ʽ");
	config_update("mail_server",$mail_server,"smtp������");
	config_update("mail_port",$mail_port,"smtp�˿�");
	config_update("mail_auth",$mail_auth,"smpt������Ҫ�������֤");
	config_update("mail_from",$mail_from,"�����˵�ַ");
	config_update("mail_auth_name",$mail_auth_name,"smtp��������֤�û���");
	config_update("mail_auth_passwd",$mail_auth_passwd,"smtp��������֤����");
	
	config_updatef();
	optlog($wdcp_uid,"�޸����ʼ�����",0,0);
	str_go_url("����ɹ���",0);
}

if (isset($_POST['Submit_test'])) {
	$mail_from_t=chop($_POST['mail_from_t']);
	$mail_to_t=chop($_POST['mail_to_t']);
	mail_send($mail_to_t,$mail_from_t);
}
if (!@isset($mailsend)) {
	$mailsend=1;
	config_update("mailsend",$mailsend,"�ʼ����ͷ�ʽ");
	config_updatef();
}
if (empty($mail_server)) $mail_server="localhost";
if (empty($mail_auth)) $mail_auth=0;
if (empty($mail_from)) $mail_from="";
if (empty($mail_auth_name)) $mail_auth_name="";
if (empty($mail_auth_passwd)) $mail_auth_passwd="";
	
require_once(G_T("admin/mail.htm"));