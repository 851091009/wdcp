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

if (isset($_POST['Submit_edit'])) {
	$uid=chop($_POST['uid']);
	$user=chop($_POST['name']);
	//echo $uid."|".$wdcp_uid;
	if ($uid!=$wdcp_uid) go_back("ID����");
	$passwd=chop($_POST['passwd']);
	//check_passwd($passwd);
	$ft=chop($_POST['ft']);
	$charge=chop($_POST['charge']);
	$xm=chop($_POST['xm']);
	$xb=chop($_POST['xb']);
	$sfzh=chop($_POST['sfzh']);
	$addr=chop($_POST['addr']);
	$tel=chop($_POST['tel']);
	$qq=chop($_POST['qq']);
	$email=chop($_POST['email']);
	//check_user($name);
	//if (empty($charge) or !is_numeric($charge)) go_back("����������������");
	$sql="update wd_member set xb='$xb'";
	//$sql.=",charge='$charge'";
	if (!empty($xm)) check_string($xm);
	$sql.=",xm='$xm'";
	$sfzh1=substr($sfzh,0,strlen($sfzh)-1);
	if (!empty($sfzh) and !is_numeric($sfzh1)) go_back("���֤�������д�");
	$sql.=",sfzh='$sfzh'";
	if (!empty($addr)) check_string($addr,"��ַ");
	$sql.=",addr='$addr'";
	if (!empty($tel)) check_string($tel,"�绰");
	$sql.=",tel='$tel'";
	if (!empty($qq) and !is_numeric($qq)) go_back("qq�������д�");
	$sql.=",qq='$qq'";
	if (!empty($email)) check_email($email);
	$sql.=",email='$email'";
	//if (strlen($passwd)!=32) {
		//check_passwd($passwd);
		//$passwd=md5($passwd);
		//$sql.=",passwd='$passwd'";
	//}
	$sql.=" where id='$uid'";
	$q=$db->query($sql);
	optlog($wdcp_uid,"�޸��û�$user����",0,0);//
	if (!$q) go_bac("����ʧ��!");
	else
		str_go_url("�޸ĳɹ�!",0);
}

//$id=intval($_GET['id']);
//if ($id!=$wdcp_uid) go_back("ID����");
//echo $wdcp_uid;
$q=$db->query("select * from wd_member where id='$wdcp_uid'");
if ($db->num_rows($q)==0) go_back("ID������");
$r=$db->fetch_array($q);
$username=$r['name'];
$passwd=$r['passwd'];
$xm=$r['xm'];
$xb=$r['xb'];
$sfzh=$r['sfzh'];
$addr=$r['addr'];
$tel=$r['tel'];
$qq=$r['qq'];
$email=$r['email'];
$uid=$wdcp_uid;
require_once(G_T("member/useredit.htm"));
?>

