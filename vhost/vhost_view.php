<?php
/*
# WDlinux Control Panel, online management of Linux servers, virtual hosts
# Author:wdlinux QQ:12571192
# Url:http://www.wdlinux.cn/wdcp
# Last Updated 2011.03
*/

require_once "../inc/common.inc.php";
//require_once "../inc/vhost.inc.php";
require_once "../login.php";
//require_once "../inc/admlogin.php";
//if ($wdcp_gid!=1) exit;


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>վ����Ϣ�鿴</title>
</head>

<body>
<p>
  <?
$vid=intval($_GET['id']);
if (empty($vid)) go_back("�����д�!");
if ($wdcp_gid==1)
	$query=$db->query("select * from wd_host where id='$vid'");
else
	$query=$db->query("select * from wd_host where uid='$wdcp_uid' and id='$vid'");
$re=$db->fetch_array($query);
//print_r($re);
$id=$re['id'];
if ($re['domain']=="local") go_back("����");
?>
</p>
<table width="724" border="1" cellpadding="0" cellspacing="0" bordercolor="#EFEFEF">
    <tr>
      <td width="117" height="38">&nbsp;</td>
      <td width="597">վ��鿴</td>
    </tr>
    <tr>
      <td>����:</td>
      <td><label><?=$re['domain'];?></label></td>
    </tr>
    <tr>
      <td>������:</td>
      <td><label><?=$re['domains'];?></label></td>
    </tr>
    <tr>
      <td>֧�ַ�����:</td>
      <td><label><?=return_state($re['domainss'],0,"��","��");?>
      </label></td>
    </tr>
    <tr>
      <td>Ŀ¼:</td>
      <td><label><?=$re['vhost_dir'];?>
      </label></td>
    </tr>
    <tr>
      <td>Ĭ����ҳ:</td>
      <td><label><?=$re['dindex'];?></label></td>
    </tr>
    <tr>
      <td>������ҳ</td>
      <td><label><?=$re['errpage'];?>
      </label></td>
    </tr>
    <tr>
      <td>����������־:</td>
      <td><label><?=return_state($re['access_log'],0,"��","��");?>
      </label></td>
    </tr>
    <tr>
      <td>����������־:</td>
      <td><label><?=return_state($re['error_log'],0,"��","��");?>
      </label></td>
    </tr>
    <tr>
      <td>֧��rewrite:</td>
      <td><label><?=return_state($re['rewrite'],"","��",1);?></label>
</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><label></label></td>
    </tr>
	<?
	if ($re['ftpuser']=="") {
	?>
    <tr>
      <td>FTP�û�:</td>
      <td><label>�� <a href="ftp.php?act=add&id=<?=$id;?>" target=_blank>����</a></label></td>
    </tr>
	<? 
	}else {
	?>
    <tr>
      <td>FTP�û���:</td>
      <td><label>
        <?=$re['ftpuser'];?> <a href="ftp.php?act=edit&id=<?=$id;?>" target=_blank>�޸�����</a>
        </label></td>
    </tr>
	<? } ?>
    <tr>
      <td>&nbsp;</td>
      <td><label></label></td>
    </tr>
	<?
	if ($re['dbname']=="") {
	?>
    <tr>
      <td>���ݿ�:</td>
      <td><label>�� <a href="mysql.php?act=add&id=<?=$id;?>" target=_blank>����</a></label>
        </td>
    </tr>
	<?
	}else {
	?>
    <tr>
      <td>���ݿ��û���:</td>
      <td><label>
        <?=$re['dbuser'];?> <a href="mysql.php?act=edit&id=<?=$id;?>" target=_blank>�޸�����</a></label></td>
    </tr>
    <tr>
      <td>���ݿ���:</td>
      <td><label>
        <?=$re['dbname'];?>
      </label></td>
    </tr>
    <tr>
      <td height="30">����:</td>
      <td><label>
        <?=$re['dbcharset'];?>
        
      </label></td>
    </tr>
	<? } ?>
    <tr>
      <td height="31">&nbsp;</td>
      <td><label>
      </label></td>
    </tr>
  </table>
<?
G_T_F("footer.htm");
?>