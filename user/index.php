<?
exit;//
require_once "../data/db.inc.php";
require_once "../inc/fun.inc.php";
require_once "../inc/db_mysql.class.php";
$db = new dbstuff;
$db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect, true, $dbcharset);
$mykey=wdl_encrypt_key();

if (isset($_POST['Submit_login'])) {
	$username=addslashes(chop($_POST['username']));
	$password=md5(chop($_POST['password']));
	$q=$db->query("select * from wd_host where ftpuser='$username' and ftppasswd='$password'");
	//echo "select * from wd_host where ftpuser='$username' and ftppasswd='$password'";
	//echo "11";
	//echo $db->num_rows($q);
	if ($db->num_rows($q)==1) {
		setcookie('username',"$username");
		//echo $_COOKIE['username']."|22";exit;
		/* echo '<script language="javascript">location.href="user.php"</script>';*/
		go_url(0);
		
	}else {
		//echo $_COOKIE['username']."|33";
		/* echo '<script language="javascript">alert("�û������������!");location.href="user.php"</script>'; */
		go_back("�û����������");
	}
} 


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>wdcpϵͳ�û��������</title>
<style type="text/css">
<!--
.STYLE1 {color: #CC0000}
-->
</style>
</head>

<body>
<?
if (!isset($_COOKIE['username'])) {
?>
<br><Br><Br>
<form id="form1" name="form1" method="post" action="">
  <table width="403" height="153" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#EFEFEF">
    <tr>
      <td height="42" colspan="2"><div align="center"><strong>wdcpϵͳ�û���¼</strong></div></td>
    </tr>
    <tr>
      <td width="151" height="31"><div align="center">FTP�û���:</div></td>
      <td width="252"><label>
        <input name="username" type="text" id="username" />
      </label></td>
    </tr>
    <tr>
      <td height="42"><div align="center">����:</div></td>
      <td><label>
        <input name="password" type="password" id="password" />
      </label></td>
    </tr>
    <tr>
      <td height="36">&nbsp;</td>
      <td><label>
        <input name="Submit_login" type="submit" id="Submit_login" value="ȷ��" />
      </label></td>
    </tr>
  </table>
</form>
<br><br><br><Br><Br>
<?
require_once "../footer.php";
exit;
}
$username=$_COOKIE['username'];
if (empty($username)) exit;
?>
wdcpϵͳ�û��������:&nbsp;
<a href="<?=$PHP_SELF;?>?act=domain">�����</a>
<a href="<?=$PHP_SELF;?>?act=ftp">FTP�����޸�</a>
<a href="<?=$PHP_SELF;?>?act=mysql">mysql�����޸�</a>
<a href="../phpmyadmin" target="_blank">phpmyadmin</a>
<a href="<?=$PHP_SELF;?>?act=logout">�˳�</a>
<br><br>
<?

if (isset($_GET['act']) and $_GET['act']=="logout") {
	js_close("�رմ���");
	exit;
}

if (isset($_POST['Submit_ftp'])) {
	$opass=chop($_POST['opass']);
	$cpass=chop($_POST['npass']);
	$ncpass=chop($_POST['cnpass']);
	if ($cpass!==$ncpass) go_back("�������벻ͬ!");
	$q=$db->query("select * from wd_host where ftpuser='$username'");
	$re=$db->fetch_array($q);
	if (md5($opass)===$re['ftppasswd']) {
		$npass=md5($npass);
		$re1=wdl_sudo_app_user_chgpass($username,$cpass);
		print_r($re1);
		if ($re1==0) {
			$passwd=md5($passwd);
			$query = $db->query("update wd_host set ftppasswd='$npass' where ftpuser='$username'");
			str_go_url("�����޸ĳɹ�!",0);
		}else
			go_back("�����޸�ʧ��!");
	}else
		go_back("ԭ���벻��!");
}

if (isset($_POST['Submit_mysql'])) {
	$opass2=chop($_POST['opass2']);
	$cpass2=chop($_POST['npass2']);
	$ncpass2=chop($_POST['cnpass2']);
	if ($cpass2!==$ncpass2) go_back("�������벻ͬ!");
	$q=$db->query("select * from wd_host where ftpuser='$username'");
	$re=$db->fetch_array($q);
	//echo md5(opass2)."|".$re['dbpasswd'];exit;
	if (md5($opass2)===$re['dbpasswd']) {
		$dbuser=$re['dbuser'];
		require_once "../data/dbr.inc.php";
		$sql="use mysql;\n";
		$sql.="update user set password=password('$ncpass2') where user='$dbuser';\n";
		$sql.="flush privileges;";
		$sqlroot=wdl_sqlroot_pw();
		$link=@mysql_connect("localhost","root",$sqlroot) or go_back("mysql root�������");
		crunquery($sql);
		$nnpass=md5($cpass2);
		$db->query("update wd_host set dbpasswd='$nnpass' where ftpuser='$username'");
		str_go_url("�����޸ĳɹ�!",0);
		exit;		
	}else
		go_back("ԭ���벻��!");
}

if (isset($_POST['Submit_domain'])) {
	$domains=chop($_POST['domains']);
	if (!eregi("[a-z0-9]{1,50}\.[a-z]{2,3}",$domains)) go_back("�����д�!");
	$q=$db->query("update wd_host set domains='$domains',ups=1 where ftpuser='$username'");
	if (!$q) go_back("�����ʧ��!");
	str_go_url("������ɹ�!,�����Ա��˷���Ч!",0);
	exit;
}

if (isset($_GET['act']) and $_GET['act']=="ftp") {
?>
<form id="form2" name="form2" method="post" action="">
  <table width="403" height="177" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#EFEFEF">
    <tr>
      <td height="42" colspan="2"><div align="center"><strong>FTP�û������޸�</strong></div></td>
    </tr>
    <tr>
      <td width="151" height="31"><div align="center">ԭ����:</div></td>
      <td width="252"><label>
        <input name="opass" type="password" id="opass" />
      </label></td>
    </tr>
    <tr>
      <td height="34"><div align="center">������:</div></td>
      <td><label>
        <input name="npass" type="password" id="npass" />
      </label></td>
    </tr>
    <tr>
      <td height="32"><div align="center">ȷ��������:</div></td>
      <td><label>
        <input name="cnpass" type="password" id="cnpass" />
      </label></td>
    </tr>
    <tr>
      <td height="36">&nbsp;</td>
      <td><label>
        <input name="Submit_ftp" type="submit" id="Submit_ftp" value="ȷ��" />
      </label></td>
    </tr>
  </table>
</form>
<p>
  <?
}

if (isset($_GET['act']) and $_GET['act']=="mysql") {
?>
</p>
<form id="form3" name="form3" method="post" action="">
  <table width="403" height="177" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#EFEFEF">
    <tr>
      <td height="42" colspan="2"><div align="center"><strong>mysql���ݿ������޸�</strong></div></td>
    </tr>
    <tr>
      <td width="151" height="31"><div align="center">ԭ����:</div></td>
      <td width="252"><label>
        <input name="opass2" type="password" id="opass2" />
      </label></td>
    </tr>
    <tr>
      <td height="34"><div align="center">������:</div></td>
      <td><label>
        <input name="npass2" type="password" id="npass2" />
      </label></td>
    </tr>
    <tr>
      <td height="32"><div align="center">ȷ��������:</div></td>
      <td><label>
        <input name="cnpass2" type="password" id="cnpass2" />
      </label></td>
    </tr>
    <tr>
      <td height="36">&nbsp;</td>
      <td><label>
        <input name="Submit_mysql" type="submit" id="Submit_mysql" value="ȷ��" />
      </label></td>
    </tr>
  </table>
</form>
<p>
<? } 
if (isset($_GET['act']) and $_GET['act']=="domain") {
$q=$db->query("select id,domains from wd_host where ftpuser='$username'");
$re=$db->fetch_array($q);
?>
</p>
<form id="form4" name="form4" method="post" action="">
  <table width="680" height="169" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#EFEFEF">
    <tr>
      <td height="37" colspan="2"><div align="center"><strong>�����</strong></div></td>
    </tr>
    <tr>
      <td width="95" height="30"><div align="center">����</div></td>
      <td width="302"><label><input name="domains" type="text" id="domains" value="<?=$re['domains'];?>" size="80" />
      </label></td>
    </tr>
    
    <tr>
      <td height="36">&nbsp;</td>
      <td><label>
        <input name="Submit_domain" type="submit" id="Submit_domain" value="ȷ��" />
      </label></td>
    </tr>
    <tr>
      <td height="63" colspan="2"><span class="STYLE1">��ע:</span><br />
        1 ����http://��ͷ,��bbs.wdlinux.cn<br />
        2 
      ����������ö���(,)�ָ�,��www.wdlinux.cn,bbs.wdlinux.cn</td>
    </tr>
  </table>
</form>
<? } ?>
<p>
  <?
require_once "../footer.php";
?>
</p>
