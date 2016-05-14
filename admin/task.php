<?php

/*
# WDlinux Control Panel, online management of Linux servers, virtual hosts
# Author:wdlinux QQ:12571192
# Url:http://www.wdlinux.cn/wdcp
# Last Updated 2011.03
*/

require_once "../inc/common.inc.php";
require_once "../login.php";
require_once "../inc/admlogin.php";
if ($wdcp_gid!=1) exit;

//
if (!@isset($task_iss)) {
	config_update("task_iss",1,"task");//////
	config_updatef();//
	$sql="INSERT INTO `wd_task` (`id`, `name`, `file`, `d1`, `d2`, `d3`, `d4`, `d5`, `ut`, `state`) VALUES
(1, '�����ļ��Զ�����', '/www/wdlinux/wdcp/task/wdcp_conf_backup.php', '5', '1', '*', '*', '*', 0, 1),
(2, '�Զ��ͷ��ڴ�', '/www/wdlinux/wdcp/task/wdcp_release_mem.php', '5', '5', '*', '*', '*', 1, 1),
(3, 'mysql����', '/www/wdlinux/wdcp/task/wdcp_mysql_backup.php', '35', '1', '*', '*', '*', 0, 1),
(4, 'mysql��Сͳ��', '/www/wdlinux/wdcp/task/wdcp_mysql_size_c.php', '15', '1', '*', '*', '*', 0, 1),
(5, '��վ����', '/www/wdlinux/wdcp/task/wdcp_site_backup.php', '5', '2', '*', '*', '*', 0, 1),
(6, 'FTP����', '/www/wdlinux/wdcp/task/wdcp_ftp_backup.php', '5', '3', '*', '*', '*', 0, 1);";
	$q=$db->query("select * from wd_task");
	if ($db->num_rows($q)==0) runquery($sql);
}

if (isset($_GET['act']) and $_GET['act']=="update") {
	wdl_demo_sys();
	$task_tmp=WD_ROOT."/data/tmp/task.txt";
	@touch($task_tmp);
	exec("sudo /www/wdlinux/wdphp/bin/php /www/wdlinux/wdcp/task/wdcp_sr.php",$str,$re);//print_r($str);print_r($re);exit;
	if (@file_exists($task_tmp)) @unlink($task_tmp);
	optlog($wdcp_uid,"���¼ƻ�����",0,0);//
	str_go_url("���³ɹ�!",0);	
}

if (isset($_GET['act']) and $_GET['act']=="rep") {
	$q=$db->query("select * from wd_task where id=1");
	if ($db->num_rows($q)>0)
		$q=$db->query("update wd_task set name='�����ļ�����',file='/www/wdlinux/wdcp/task/wdcp_conf_backup.php' where id=1");
	else
		$db->query("insert into wd_task(id,name,file,d1,d2,d3,d4,d5,ut,state) values(1,'�����ļ�����','/www/wdlinux/wdcp/task/wdcp_conf_backup.php','5','1','*','*','*','0','1')");
	$q=$db->query("select * from wd_task where id=2");
	if ($db->num_rows($q)>0)
		$q=$db->query("update wd_task set name='�Զ��ͷ��ڴ�',file='/www/wdlinux/wdcp/task/wdcp_release_mem.php' where id=2");
	else
		$db->query("insert into wd_task(id,name,file,d1,d2,d3,d4,d5,ut,state) values(2,'�Զ��ͷ��ڴ�','/www/wdlinux/wdcp/task/wdcp_release_mem.php','5','1','*','*','*','0','1')");
	$q=$db->query("select * from wd_task where id=3");
	if ($db->num_rows($q)>0)	
		$q=$db->query("update wd_task set name='mysql����',file='/www/wdlinux/wdcp/task/wdcp_mysql_backup.php' where id=3");
	else
		$db->query("insert into wd_task(id,name,file,d1,d2,d3,d4,d5,ut,state) values(3,'mysql����','/www/wdlinux/wdcp/task/wdcp_mysql_backup.php','5','1','*','*','*','0','1')");
	$q=$db->query("select * from wd_task where id=4");
	if ($db->num_rows($q)>0)
		$q=$db->query("update wd_task set name='mysql��Сͳ��',file='/www/wdlinux/wdcp/task/wdcp_mysql_size_c.php' where id=4");
	else
		$db->query("insert into wd_task(id,name,file,d1,d2,d3,d4,d5,ut,state) values(4,'mysql��Сͳ��','/www/wdlinux/wdcp/task/wdcp_mysql_size_c.php','5','1','*','*','*','0','1')");
	$q=$db->query("select * from wd_task where id=5");
	if ($db->num_rows($q)>0)
		$q=$db->query("update wd_task set name='��վ����',file='/www/wdlinux/wdcp/task/wdcp_site_backup.php' where id=5");
	else
		$db->query("insert into wd_task(id,name,file,d1,d2,d3,d4,d5,ut,state) values(5,'��վ����','/www/wdlinux/wdcp/task/wdcp_site_backup.php','5','1','*','*','*','0','1')");
	$q=$db->query("select * from wd_task where id=6");
	if ($db->num_rows($q)>0)
		$q=$db->query("update wd_task set name='FTP����',file='/www/wdlinux/wdcp/task/wdcp_ftp_backup.php' where id=6");
	else
		$db->query("insert into wd_task(id,name,file,d1,d2,d3,d4,d5,ut,state) values(6,'FTP����','/www/wdlinux/wdcp/task/wdcp_ftp_backup.php','5','1','*','*','*','0','1')");		
}

if (isset($_GET['act']) and $_GET['act']=="stop") {
	$id=intval($_GET['id']);
	$name=chop($_GET['name']);
	$q=$db->query("update wd_task set state=1 where id='$id'");
	optlog($wdcp_uid,"�ر�����$name",0,0);//
	str_go_url("�ѹر�!",0);
}

if (isset($_GET['act']) and $_GET['act']=="start") {
	$id=intval($_GET['id']);
	$name=chop($_GET['name']);
	$q=$db->query("update wd_task set state=0 where id='$id'");
	optlog($wdcp_uid,"��������$name",0,0);//
	str_go_url("������!",0);
}

if (isset($_GET['act']) and $_GET['act']=="del") {
	$id=intval($_GET['id']);
	$q=$db->query("select * from wd_task where id='$id'");
	if ($db->num_rows($q)==0) go_back("���񲻴���");
	$r=$db->fetch_array($q);
	$name=$r['name'];
	$q=$db->query("delete from wd_task where id='$id'");
	optlog($wdcp_uid,"ɾ������$name",0,0);//
	str_go_url("��ɾ��!",0);	
}

if (isset($_POST['Submit_add'])) {
	//print_r($_POST);
	$id=intval($_POST['id']);
	$name=chop($_POST['name']);
	$prog=chop($_POST['prog']);
	if (!@file_exists($prog)) go_back("�ļ�������");
	//if (substr($prog,-4)==".php") $prog="/www/wdlinux/wdphp/bin/php ".$prog;
	if (!empty($_POST['mins']))
		$min=implode(",",$_POST['mins']);
	else
		$min="*/".intval($_POST['min']);
	if (!empty($_POST['hours']))
		$hour=implode(",",$_POST['hours']);
	else
		$hour="*/".intval($_POST['hour']);
	if (!empty($_POST['days']))
		$day=implode(",",$_POST['days']);
	else
		$day="*";
	if (!empty($_POST['months']))
		$month=implode(",",$_POST['months']);
	else
		$month="*";
	//print_r($_POST['week']);
	if (!empty($_POST['weeks']))
		$week=implode(",",$_POST['weeks']);
	else
		$week="*";
	//echo $week;exit;
	$q=$db->query("insert into wd_task(name,file,d1,d2,d3,d4,d5,ut,state) values('$name','$prog','$min','$hour','$day','$month','$week',1,0)");
	optlog($wdcp_uid,"���Ӽƻ����� $name",0,0);
	str_go_url("����ɹ���",0);
}

if (isset($_GET['act']) and $_GET['act']=="add") {
	require_once(G_T("admin/task_add.htm"));
	exit;	
}

if (isset($_POST['Submit_edit'])) {
	//print_r($_POST);
	$id=intval($_POST['id']);
	$name=chop($_POST['name']);
	$prog=chop($_POST['prog']);
	if (!@file_exists($prog)) go_back("�ļ�������");
	//if (!@is_executable($f))
	//if (substr($prog,-4)==".php") $prog="/www/wdlinux/wdphp/bin/php ".$prog;
	if (!empty($_POST['mins']))
		$min=implode(",",$_POST['mins']);
	elseif ($_POST['min']=="-1")
		go_back("�������ô���");
	else
		$min="*/".intval($_POST['min']);
	if (!empty($_POST['hours']))
		$hour=implode(",",$_POST['hours']);
	elseif ($_POST['hour']=="-1")
		go_back("Сʱ���ô���");
	else
		$hour="*/".intval($_POST['hour']);
	if (!empty($_POST['days']))
		$day=implode(",",$_POST['days']);
	else
		$day="*";
	if (!empty($_POST['months']))
		$month=implode(",",$_POST['months']);
	else
		$month="*";
	//print_r($_POST['week']);
	if (!empty($_POST['weeks']))
		$week=implode(",",$_POST['weeks']);
	else
		$week="*";
	//echo $week;exit;
	$q=$db->query("update wd_task set d1='$min',d2='$hour',d3='$day',d4='$month',d5='$week',name='$name',file='$prog' where id='$id'");
	optlog($wdcp_uid,"�޸ļƻ����� $name",0,0);
	str_go_url("����ɹ���",0);
}


if (isset($_GET['act']) and $_GET['act']=="edit") {
	$id=intval($_GET['id']);
	$q=$db->query("select * from wd_task where id='$id'");
	if ($db->num_rows($q)==0) go_back("��������!");
	$r=$db->fetch_array($q);
	$name=$r['name'];
	$file=$r['file'];
	$id=$r['id'];
	//echo "00";
	if (eregi("/",$r['d1'])){
		$min=str_replace("*/","",$r['d1']);
		$minc=return_min($min);
		
	}else{
		$mins=$r['d1'];
		$minc=return_min("-1");
		}
	if (eregi("/",$r['d2'])){
		$hour=str_replace("*/","",$r['d2']);
		$hourc=return_hour($hour);
		$hours="";
	}else{
		$hours=$r['d2'];
		//echo $hours;
		$hourc=return_hour("-1");
	}
	if ($r['d3']=="*")
		$day=1;
	else
		$days=$r['d3'];
		
	if ($r['d4']=="*")
		$month=1;
	else
		$months=$r['d4'];
	if ($r['d5']=="*")
		$week=1;
	else
		$weeks=$r['d5'];
	//$prog=str_replace("/www/wdlinux/wdphp/bin/php ","",$r['file']);
	//echo $prog."aa";
	$prog=$r['file'];
	require_once(G_T("admin/task_edit.htm"));
	exit;
}

//fun
function return_weeks($i) {
	global $weeks;
	if (empty($weeks)) return;
	$s1=explode(",",$weeks);
	if (in_array($i,$s1))
		return 'checked';
}

function return_days($i) {
	global $days;
	if (empty($days)) return;
	$s1=explode(",",$days);
	if (in_array($i,$s1))
		return 'checked';
}

function return_months($i) {
	global $months;
	if (empty($months)) return;
	$s1=explode(",",$months);
	if (in_array($i,$s1))
		return 'checked';
}
function return_hours($i) {
	global $hours;
	if ($hours=="") return;
	$s1=explode(",",$hours);
	if (in_array($i,$s1))
		return 'checked';
}
function return_mins($i) {
	global $mins;
	if (empty($mins)) return;
	$s1=explode(",",$mins);
	if (in_array($i,$s1))
		return 'checked';
}
function return_min($i) {
	//echo $i;
	if ($i=="-1") 
		return '<option value="-1" selected="selected">ָ��</option>
			  <option value="1">ÿ1����</option>
              <option value="5">ÿ5����</option>
              <option value="10">ÿ10����</option>
              <option value="15">ÿ15����</option>
              <option value="30">ÿ30����</option>';
	elseif($i==1)
		return '<option value="1" selected="selected">ÿ1����</option>
              <option value="5">ÿ5����</option>
              <option value="10">ÿ10����</option>
              <option value="15">ÿ15����</option>
              <option value="30">ÿ30����</option>';
	elseif ($i==5)
		return '<option value="1">ÿ1����</option>
              <option value="5" selected="selected">ÿ5����</option>
              <option value="10">ÿ10����</option>
              <option value="15">ÿ15����</option>
              <option value="30">ÿ30����</option>';
	elseif ($i==10)
		return '<option value="1">ÿ1����</option>
              <option value="5">ÿ5����</option>
              <option value="10" selected="selected">ÿ10����</option>
              <option value="15">ÿ15����</option>
              <option value="30">ÿ30����</option>';
	elseif ($i==15)
		return '<option value="1">ÿ1����</option>
              <option value="5">ÿ5����</option>
              <option value="10">ÿ10����</option>
              <option value="15" selected="selected">ÿ15����</option>
              <option value="30">ÿ30����</option>';
	else
		return '<option value="1">ÿ1����</option>
              <option value="5">ÿ5����</option>
              <option value="10">ÿ10����</option>
              <option value="15">ÿ15����</option>
              <option value="30" selected="selected">ÿ30����</option>';
}
function return_hour($i) {
	if ($i=="-1")
		return '<option value="-1" selected="selected">ָ��</option>
			  <option value="1">ÿСʱ</option>
              <option value="3">ÿ��Сʱ</option>
              <option value="5">ÿ��Сʱ</option>';
	elseif ($i==1)
		return '<option value="1" selected="selected">ÿСʱ</option>
              <option value="3">ÿ��Сʱ</option>
              <option value="5">ÿ��Сʱ</option>';
	elseif ($i==3)
		return '<option value="1">ÿСʱ</option>
              <option value="3" selected="selected">ÿ��Сʱ</option>
              <option value="5">ÿ��Сʱ</option>';
	else
		return '<option value="1">ÿСʱ</option>
              <option value="3">ÿ��Сʱ</option>
              <option value="5" selected="selected">ÿ��Сʱ</option>';
}

$q=$db->query("select * from wd_task order by id");
$i=0;
$list=array();
$n_list=array(
'conf_backup' => "�����ļ�����",
'release_mem' => "�Զ��ͷ���",
'mysql_backup' => "mysql����",
'mysql_size_c' => "mysqlʹ��ͳ",
'site_backup' => "��վ����",
'ftp_backup' => "FTP����");

while ($r=$db->fetch_array($q)) {
	$list[$i]['id']=$r['id'];
	//$list[$i]['name']=$n_list[$r['file']];
	$list[$i]['name']=$r['name'];
	if (eregi("/",$r['d1']))
		$list[$i]['min']="ÿ".str_replace("*/","",$r['d1'])."����";
	else
		$list[$i]['min']="ÿСʱ��".$r['d1']."����";
	if (eregi("/",$r['d2']))
		$list[$i]['hour']="ÿ".str_replace("*/","",$r['d2'])."Сʱ";
	else
		$list[$i]['hour']="ÿ���".$r['d2']."��";
	if ($r['d3']=="*")
		$list[$i]['day']="ÿ��";
	else
		$list[$i]['day']="ÿ�µ�".$r['d3']."��";

	if ($r['d4']=="*")
		$list[$i]['month']="ÿ��";
	else
		$list[$i]['month']=$r['d4']."��";

	if ($r['d5']=="*")
		$list[$i]['week']="ÿ��";
	else
		$list[$i]['week']="ÿ�ܵ�".$r['d5'];
	if ($r['state']==0) {
		$list[$i]['state']="����";
		$list[$i]['act']='<a href="'.$PHP_SELF.'?act=stop&id='.$r['id'].'&name='.$r['name'].'">�ر�</a>';
	}else{
		$list[$i]['state']="�ر�";
		$list[$i]['act']='<a href="'.$PHP_SELF.'?act=start&id='.$r['id'].'&name='.$r['name'].'">����</a>';
	}
	$i++;
}
require_once(G_T("admin/task.htm"));
//G_T_F("footer.htm");
footer_info();
?>