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
require_once "../inc/admlogin.php";
if ($wdcp_gid!=1) exit;

if (isset($_GET['act']) and $_GET['act']=="del") {
	$id=intval($_GET['id']);
	if ($id==1 or $id==10) go_back("Ĭ���鲻��ɾ��!");
	$q=$db->query("delete from wd_group where id='$id'");
	if (!$q) go_back("��ɾ��ʧ��");
	else {
		optlog($wdcp_uid,"ɾ����$id",0,0);
		str_go_url("��ɾ���ɹ�!",0);
		}
}

if (isset($_POST['Submit_add'])) {
	$name=chop($_POST['name']);
	$site=intval($_POST['site']);
	$ftp=intval($_POST['ftp']);
	$mysql=intval($_POST['mysql']);
	check_string($name);
	$q=$db->query("select * from wd_group where name='$name'");
	if ($db->num_rows($q)!=0) go_back("���û����Ѵ���!");
	$q=$db->query("insert into wd_group(name,site,ftp,mysql) values('$name','$site','$ftp','$mysql')");
	if (!$q) go_bac("����ʧ��!");
	else {
		optlog($wdcp_uid,"������$name",0,0);
		str_go_url("�����ӳɹ�!",0);
		}
}

if (isset($_POST['Submit_edit'])) {
	$id=intval($_POST['id']);
	$name=chop($_POST['name']);
	$site=intval($_POST['site']);
	$ftp=intval($_POST['ftp']);
	$mysql=intval($_POST['mysql']);
	$q=$db->query("update wd_group set name='$name',site='$site',ftp='$ftp',mysql='$mysql' where id='$id'");
	if (!$q) go_bac("����ʧ��!");
	else {
		optlog($wdcp_uid,"�޸���$name",0,0);
		str_go_url("���޸ĳɹ�!",0);
		}
}

if (isset($_GET['act']) and $_GET['act']=="add") {
	require_once(G_T("member/group_add.htm"));
}

if (isset($_GET['act']) and $_GET['act']=="edit") {
$id=intval($_GET['id']);
$q=$db->query("select * from wd_group where id='$id'");
$r=$db->fetch_array($q);
require_once(G_T("member/group_edit.htm"));
}

if (!isset($_GET['act']) or $_GET['act']=="list") {
$q=$db->query("select * from wd_group");

$list=array();
$i=0;
  while ($r=$db->fetch_array($q)) {
	$list[$i]['id']=$r['id'];
	$list[$i]['name']=$r['name'];
	$list[$i]['site']=$r['site'];
	$list[$i]['ftp']=$r['ftp'];
	$list[$i]['mysql']=$r['mysql'];
	$i++;
}
require_once(G_T("member/group_list.htm"));
G_T_F("footer.htm");
}
?>

