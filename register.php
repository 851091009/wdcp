<?php
/*
# WDlinux Control Panel,online management of Linux servers, virtual hosts
# Wdcdn system
# Author:wdlinux QQ:12571192
# Url:http://www.wdlinux.cn/wdcdn
# Last Updated 2011.05
*/

require_once "inc/common.inc.php";
//require_once WD_ROOT."/inc/userinfo.php";
//require_once "../login.php";
//require_once "../inc/page_class.php";
if ($is_reg==0 or isset($_GET['is_reg'])) go_back("δ����ע��");//

if (isset($_POST['Submit_reg'])) {
	$name=chop(strip_tags($_POST['username']));
	$passwd=chop($_POST['passwd']);
	$passwd2=chop($_POST['passwd2']);
	if (strcmp($passwd,$passwd2)!=0) go_back("�������벻ͬ,��ȷ������");
	$ft=chop($_POST['ft']);
	$charge=chop($_POST['charge']);
	//if (empty($charge) or !is_numeric($charge)) go_back("����������������");
	$xm=chop($_POST['xm']);
	$xb=chop($_POST['xb']);
	$sfzh=chop($_POST['sfzh']);
	$addr=chop($_POST['addr']);
	$tel=chop($_POST['tel']);
	$qq=chop($_POST['qq']);
	$email=chop($_POST['email']);
	$is_ck=intval($_POST['is_ck']);
	check_user($name);
	check_passwd($passwd);
	//if (!empty($charge
	if (!empty($xm)) check_string($xm);
	$sfzh1=substr($sfzh,0,strlen($sfzh)-1);
	if (!empty($sfzh) and !is_numeric($sfzh1)) go_back("���֤�����д�");
	if (!empty($addr)) check_string($addr,"��ַ");
	if (!empty($tel)) check_string($tel,"�绰");
	if (!empty($qq) and !is_numeric($qq)) go_back("QQ�������д�");
	if (!empty($email)) check_email($email);

	if ($is_ck==1) {
		$ckcode=intval($_POST['ckcode']);
		check_ckcode($ckcode);
	}


	$rtime=time();
	$lip=$_SERVER['REMOTE_ADDR'];
	$passwd=md5($passwd);
	$q=$db->query("select * from wd_member where name='$name'");
	if ($db->num_rows($q)!=0) go_back("���û����Ѵ���");
	$q=$db->query("select * from wd_member where email='$name'");
	if ($db->num_rows($q)!=0) go_back("�������Ѵ���");
	$q=$db->query("insert into wd_member(name,passwd,xm,xb,sfzh,addr,tel,qq,email,rtime,state) values('$name','$passwd','$xm','$xb','$sfzh','$addr','$tel','$qq','$email','$rtime',2)");
	if (!$q) go_bac("����ʧ��!");
	else
		str_go_url("�û�ע��ɹ�!","../index.php");
}
$rid=isset($_GET['rid'])?intval($_GET['rid']):'';
$lc=@login_validation();
require_once(G_T("member/register.htm"));
?>
