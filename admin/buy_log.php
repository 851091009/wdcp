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
//require_once "../inc/admlogin.php";
//echo $wdcdn_user."|".$wdcdn_uid."|".$wdcdn_gid;
//if ($wdcp_gid!=1) exit;
//if ($wdcdn_gid!=1 or empty($_SESSION['admin'])) exit;

$pagenum=20;
if (!isset($_GET['page'])) $start=0;
else	$start=(intval($_GET['page'])-1)*$pagenum;
if ($start<0) $start=0;

if (isset($_GET['Submit']) and !empty($_GET['domain'])) {
	$domain=chop($_GET['domain']);
	$t=intval($_GET['t']);
	if ($wdcp_gid!=1) go_back("err");
	if ($t==1) {
		$q=$db->query("select * from wd_member where name='$domain'");
		if ($db->num_rows($q)==0) go_back("�û���������");
		$r=$db->fetch_array($q);
		$wh="where uid='".$r['id']."'";	//
		//$wh="where uid like '%$domain%'";
	}elseif ($t==2) {
		$wh="where domain like '%$domain%'";		
	}else
		go_back("err");
}else
	$wh="";

if ($wdcp_gid==1) {
	$sum=sum_result("select * from wd_dns_buylog $wh");
	$query=$db->query("select * from wd_dns_buylog $wh order by id desc limit $start,$pagenum");
}else{
	$sum=sum_result("select * from wd_dns_buylog where uid='$wdcp_uid'");
	$query=$db->query("select * from wd_dns_buylog where uid='$wdcp_uid' order by id desc limit $start,$pagenum");
}
$list=array();
$i=0;
  while ($r=$db->fetch_array($query)) {
  	//if ($r['state']==0) { $ss='<a href="'.$PHP_SELF.'?act=stop&id='.$r['id'].'">��ͣ</a>';$ss1="����";}
	//else {$ss='<a href="'.$PHP_SELF.'?act=start&id='.$r['id'].'">����</a>';$ss1="��ͣ";}
	$list[$i]['id']=$r['id'];
	if ($r['pid']==0)
		$list[$i]['title']='����ײ�';
	else
	    $list[$i]['title']=pid_to_name($r['pid']);
	$list[$i]['price']=$r['money'];
	$list[$i]['uid']=uid_name($r['uid']);
	$list[$i]['rtime']=date("Y-m-d H:i",$r['rtime']);
	$list[$i]['note']=$r['note'];
	if ($r['state']==0)
		$list[$i]['state']="�ɹ�";
	else
		$list[$i]['state']="ʧ��";
	$i++;
}
$page=new page(array('total'=>$sum,'perpage'=>$pagenum));
$pagelist=$page->show();

if ($wdcp_gid==1)
	$search_d='<form id="form1" name="form1" method="get" action="">����:
  <select name="t" id="t">
    <option value="1" selected="selected">�û���</option>
    <option value="2">������</option>
  </select>
  <input name="domain" type="text" id="domain" size="20" /></label>
  <input type="submit" name="Submit" value="��ѯ" />
  </form>';//
else
	$search_d='';
require_once(G_T("admin/buy_log.htm"));
?>