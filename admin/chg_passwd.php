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
exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>�����޸�</title>
<style type="text/css">
<!--
body,td,th {
	font-size: 14px;
}
body {
	margin-left: 60px;
	margin-top: 30px;
}
-->
</style></head>

<body>
<a href=<?=$PHP_SELF;?>?t=mysql>mysql root�û�����</a>
<a href=<?=$PHP_SELF;?>?t=mysql_wdcp>mysql wdcp�û�����</a>
<br><br />
<?

if ($sqlrootpw_en==0) {
	echo "<br>mysql root �û�ʹ��Ĭ������ \"".$sqlrootpw."\" ,Ϊ��ȫ���,ǿ�ҽ����޸�.<a href=".$PHP_SELF."?t=mysql>���ھ͸�</a><br>";
}

if ($dbpw==="wdlinux.cn") {
	echo "<br>mysql wdcp �û�ʹ��Ĭ������ \"".$dbpw."\" ,Ϊ��ȫ���,ǿ�ҽ����޸�.<a href=".$PHP_SELF."?t=mysql_wdcp>���ھ͸�</a>";
}

if (isset($_POST['Submit_achg_passwd'])) {
		//demo
	if (in_array($_SERVER["SERVER_ADDR"],$demo_ip)) go_back("��ʾϵͳ�Բ��ֹ�����������!");
	

	$opass=md5($_POST['opass']);
	//echo "aa";
	if ($opass!==$adm_pass) go_back("ԭ���벻��!");
	$npass=chop($_POST['npass']);
	$cpass=chop($_POST['cpass']);
	$apss=md5($cpass);
	if (md5($npass)!==md5($cpass)) go_back("�������벻��!");
	$query=$db->query("update wd_sys set wd_value='$apss' where wd_name='adm_pass'");
	
	$sql="select * from wd_sys";
$q=@mysql_query($sql);
$msg="<?\n";
while ($r=mysql_fetch_array($q)) {
	$msg.="\$".$r['wd_name']."=\"".$r['wd_value']."\";\n";
}
$msg.="?>";

file_put_contents("../data/wd_sys.php",$msg);
	
	str_go_url("�����޸ĳɹ�!",1);
	exit;
}

//root
if (isset($_POST['Submit_chg_mysql'])) {
	if (in_array($_SERVER["SERVER_ADDR"],$demo_ip)) go_back("��ʾϵͳ�Բ��ֹ�����������!");

	$mpw=wdl_sqlroot_pw();
	$opass=chop($_POST['opass']);
	//echo $opass."|".$mpw;//exit;
	if (md5($opass)!==md5($mpw)) go_back("ԭ���벻��!");
	$npass=chop($_POST['npass']);
	$cpass=chop($_POST['cpass']);
	if (md5($npass)!==md5($cpass)) go_back("�������벻��!");
	mysql_connect("localhost","root","$mpw");
	mysql_query("use mysql;");
	$q=mysql_query("update user set password=password('$npass') where user='root';");
	if (!$q) go_back("�����޸�ʧ��!");
	mysql_query("flush privileges;");
	$nmpw=wdl_encrypt($npass,$mykey);
	//echo $npass."|".$nmpw;exit;
	$dbf="../data/dbr.inc.php";
	$str=<<<EOF
<?
\$sqlrootpw='$nmpw';
\$sqlrootpw_en='1';
?>
EOF;
	file_put_contents($dbf,$str);
	str_go_url("�����޸ĳɹ�!",1);
	exit;
}

//wdcp
if (isset($_POST['Submit_chg_mysql_wdcp'])) {
	if (in_array($_SERVER["SERVER_ADDR"],$demo_ip)) go_back("��ʾϵͳ�Բ��ֹ�����������!");

	$mpw=wdl_sqlroot_pw();
	$opass=chop($_POST['opass']);
	//echo $opass."|".$mpw;//exit;
	if (md5($opass)!==md5($dbpw)) go_back("ԭ���벻��!");
	$npass=chop($_POST['npass']);
	$cpass=chop($_POST['cpass']);
	if (md5($npass)!==md5($cpass)) go_back("�������벻��!");
	mysql_connect("localhost","root","$mpw");
	mysql_query("use mysql;");
	$q=mysql_query("update user set password=password('$npass') where user='wdcp';");
	if (!$q) go_back("�����޸�ʧ��!");
	mysql_query("flush privileges;");
	//$nmpw=encrypt($npass,$mykey);
	//echo $npass."|".$nmpw;exit;
	$dbf="../data/db.inc.php";
	$str=<<<EOF
<?
\$dbhost = 'localhost';
\$dbuser = 'wdcp';
\$dbpw = '$npass';
\$dbname = 'wdcpdb';
\$pconnect = 0;
\$dbcharset = 'gbk';
?>
EOF;
	file_put_contents($dbf,$str);
	str_go_url("�����޸ĳɹ�!",1);
	exit;
}

if ($_GET['t']=="login") {
?>
<form id="form_chg_passwd" name="form_chg_passwd" method="post" action="">
  <table width="325" border="1" cellpadding="0" cellspacing="0" bordercolor="#EEEEEE">
    <tr>
      <td width="68" height="38">&nbsp;</td>
      <td width="275"><strong>�޸ĺ�̨��¼����</strong></td>
    </tr>
    <tr>
      <td height="29">ԭ����:</td>
      <td><label>
        <input name="opass" type="password" id="opass" size="15" />
      </label></td>
    </tr>
    <tr>
      <td height="28">������:</td>
      <td><label>
        <input name="npass" type="password" id="npass" size="15" />
      </label></td>
    </tr>
    <tr>
      <td height="27">ȷ��:</td>
      <td><label>
        <input name="cpass" type="password" id="cpass" size="15" />
      </label></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><label>
        <input name="Submit_achg_passwd" type="submit" id="Submit_achg_passwd" value="ȷ��" />
      </label></td>
    </tr>
  </table>
</form>
<?
}
if ($_GET['t']=="mysql") {
?>
<form id="form_chg_mysql_passwd" name="form_chg_mysql_passwd" method="post" action="">
  <table width="325" border="1" cellpadding="0" cellspacing="0" bordercolor="#EEEEEE">
    <tr>
      <td width="68" height="38">&nbsp;</td>
      <td width="275"><strong>�޸�mysql root�û�����</strong></td>
    </tr>
    <tr>
      <td height="29">ԭ����:</td>
      <td><label>
        <input name="opass" type="password" id="opass" size="15" />
      </label></td>
    </tr>
    <tr>
      <td height="28">������:</td>
      <td><label>
        <input name="npass" type="password" id="npass" size="15" />
      </label></td>
    </tr>
    <tr>
      <td height="27">ȷ��:</td>
      <td><label>
        <input name="cpass" type="password" id="cpass" size="15" />
      </label></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><label>
        <input name="Submit_chg_mysql" type="submit" id="Submit_chg_mysql" value="ȷ��" />
      </label></td>
    </tr>
  </table>
</form>
<?
}
if ($_GET['t']=="mysql_wdcp") {
?>
<form id="form_chg_mysql_wdcp_passwd" name="form_chg_mysql_wdcp_passwd" method="post" action="">
  <table width="325" border="1" cellpadding="0" cellspacing="0" bordercolor="#EEEEEE">
    <tr>
      <td width="68" height="38">&nbsp;</td>
      <td width="275"><strong>�޸����ݿ�wdcp�û�����</strong></td>
    </tr>
    <tr>
      <td height="29">ԭ����:</td>
      <td><label>
        <input name="opass" type="password" id="opass" size="15" />
      </label></td>
    </tr>
    <tr>
      <td height="28">������:</td>
      <td><label>
        <input name="npass" type="password" id="npass" size="15" />
      </label></td>
    </tr>
    <tr>
      <td height="27">ȷ��:</td>
      <td><label>
        <input name="cpass" type="password" id="cpass" size="15" />
      </label></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><label>
        <input name="Submit_chg_mysql_wdcp" type="submit" id="Submit_chg_mysql_wdcp" value="ȷ��" />
      </label></td>
    </tr>
  </table>
</form>
<?
}
?>
</body>
</html>
