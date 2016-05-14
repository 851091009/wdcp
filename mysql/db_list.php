<?php
/*
# WDlinux Control Panel, online management of Linux servers, virtual hosts
# Author:wdlinux QQ:12571192
# Url:http://www.wdlinux.cn/wdcp
# Last Updated 2011.03
*/

require_once "../inc/common.inc.php";
require_once "../login.php";
//require_once "../inc/admlogin.php";
//if ($wdcp_gid!=1) exit;


if (isset($_GET['act']) and $_GET['act']=="del") {
	$id=intval($_GET['id']);
	if (!is_numeric($id)) go_back("ID����");
	if ($wdcp_gid==1)
		$q=$db->query("select * from wd_mysql where id='$id'");
	else
		$q=$db->query("select * from wd_mysql where uid='$wdcp_uid' and id='$id'");
	if ($db->num_rows($q)==0) go_back("���ݿⲻ���ڣ�");
	$r=$db->fetch_array($q);
	//$dbuser=$r['dbuser'];
	$dbname=$r['dbname'];
	//del_db_user($dbuser,$host);
	drop_db($dbname);
	$db->query("delete from wd_mysql where id='$id'");
	optlog($wdcp_uid,"ɾ�����ݿ�$dbname ",0,0);
	//if (!$q) go_back("����ʧ�ܣ�");
	str_go_url("���ݿ���ɾ��!",0);
}

if (isset($_POST['Submit_chgsid'])) {
	//print_r($_POST);
	$id=intval($_POST['id']);
	$sid=intval($_POST['sid']);
	if (!is_numeric($id) or !is_numeric($sid)) go_back("ID����");
	$db->query("update wd_mysql set sid='$sid' where id='$id'");
	optlog($wdcp_uid,"�޸����ݿ�վ��ID ",0,0);
	str_go_url("�޸ĳɹ�",0);
}

if (isset($_GET['act']) and $_GET['act']=="chgsid") {
	$id=intval($_GET['id']);
	if (!is_numeric($id)) go_back("ID����");
	if ($wdcp_gid==1)
		$q=$db->query("select * from wd_mysql where id='$id'");
	else
		$q=$db->query("select * from wd_mysql where uid='$wdcp_uid' and id='$id'");
	if ($db->num_rows($q)==0) go_back("���ݿ��û�������!");
	$r=$db->fetch_array($q);
	$dbuser=$r['dbname'];
	$id=$r['id'];
	$site_list=site_list($r['sid']);
	require_once(G_T("mysql/chgsid.htm"));
	G_T_F("footer.htm");
	exit;		
}

if (isset($_POST['Submit_chgpw'])) {
	//print_r($_POST);
	$id=intval($_GET['id']);
	if (!is_numeric($id)) go_back("ID����");
	$user=chop($_POST['dbuser']);
	$password=chop($_POST['password']);
	$cpassword=chop($_POST['cpassword']);
	if (strcmp($password,$cpassword)!=0) go_back("�������벻�ԣ����������룡");
	//check_string($password);
	$npass=md5($password);
	//chg_mysql_passwd($id,$password);
	//echo $user."|".$password;
	if ($wdcp_gid==1) {
		dbuser_chg_password($user,$password);
		$q=$db->query("update wd_mysql set dbpw='$npass' where id='$id'");
	}else{
		$q1=$db->query("select * wd_mysql where uid='$wdcp_uid' and id='$id'");
		if ($db->num_rows($q1)==0) go_back("���ݿ��û�������!");
		dbuser_chg_password($user,$password);
		$q=$db->query("update wd_mysql set dbpw='$npass' where uid='$wdcp_uid' and id='$id'");
	}
	optlog($wdcp_uid,"�޸����ݿ��û�$user���� ",0,0);
	//exit;
	if (!$q) go_back("�޸�ʧ�ܣ�");
	str_go_url("�����޸ĳɹ�!",0);	
}

if (isset($_GET['act']) and $_GET['act']=="chgpw") {
	$id=intval($_GET['id']);
	if (!is_numeric($id)) go_back("ID����");
	if ($wdcp_gid==1)
		$q=$db->query("select * from wd_mysql where id='$id'");
	else
		$q=$db->query("select * from wd_mysql where uid='$wdcp_uid' and id='$id'");
	if ($db->num_rows($q)==0) go_back("�û�������!");
	$r=$db->fetch_array($q);
	$dbuser=$r['dbuser'];
	$id=$r['id'];
	require_once(G_T("mysql/chgpw.htm"));
	G_T_F("footer.htm");
	exit;
}


$pagenum=20;
if (!isset($_GET['page'])) $start=0;
else	$start=(intval($_GET['page'])-1)*$pagenum;
if ($start<0) $start=0;

if (isset($_POST['Submit'])) {
	$dbname=chop($_POST['dbname']);
	$wh="dbname like '%$dbname%' and";
	$whg="where dbname like '%$dbname%'";
}elseif (isset($_GET['sid'])) {
	$sid=intval($_GET['sid']);
	$wh="sid='$sid' and";
	$whg="where sid='$sid'";
}else{
	$wh="";
	$whg="";	
}

if ($wdcp_gid==1) {
	$query=$db->query("select * from wd_mysql where $wh isuser=0");
	$sum=$db->num_rows($query);
	$query=$db->query("select * from wd_mysql where $wh isuser=0 order by id desc limit $start,$pagenum");
}else{
	$query=$db->query("select * from wd_mysql where $wh uid='$wdcp_uid' and isuser=0");
	$sum=$db->num_rows($query);
	$query=$db->query("select * from wd_mysql where $wh uid='$wdcp_uid' and isuser=0 order by id desc limit $start,$pagenum");
}
$list=array();
$i=0;
while ($r=$db->fetch_array($query)) {
	//echo "11";
	$list[$i]['id']=$r['id'];
	//$list[$i]['dbuser']=$r['dbuser'];
	//$list[$i]['password']=$r['password'];
	$list[$i]['dbname']=$r['dbname'];
	$list[$i]['dbsize']=$r['dbsize'];
	$list[$i]['rtime']=date("Y-m-d H:i",$r['rtime']);
	if ($r['state']==0)
		$list[$i]['state']="����";
	elseif ($r['state']==1)
		$list[$i]['state']="�ر�";
	else
		$list[$i]['state']="�ر�";
	$list[$i]['sid']=$r['sid'];
	$i++;
}
$page=new page(array('total'=>$sum,'perpage'=>$pagenum));
$pagelist=$page->show();
require_once(G_T("mysql/db_list.htm"));
//G_T_F("footer.htm");
footer_info();
?>
