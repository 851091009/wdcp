<?php

/*
# WDlinux Control Panel,online management of Linux servers, virtual hosts
# Author:wdlinux QQ:12571192
# Url:http://www.wdlinux.cn/wdcp
# Last Updated 2011.05
*/

require_once "../inc/common.inc.php";
require_once "../inc/page_class.php";
require_once "../login.php";
if ($wdcp_gid!=1) exit;
//if ($wdcdn_gid!=1 or empty($_SESSION['admin'])) exit;

//$backup_ftp_conf="../data/backup_ftp_conf.php";
if (isset($_POST['Submit_ftp'])) {
	$ftp_addr=chop($_POST['ftp_addr']);
	$ftp_port=chop($_POST['ftp_port']);
	$ftp_user=chop($_POST['ftp_user']);
	$ftp_pass=chop($_POST['ftp_pass']);
	$ftp_dir=chop($_POST['ftp_dir']);
	if (empty($ftp_dir)) $ftp_dir="/";//
	$ftp_is=intval($_POST['ftp_is']);
	//if (empty($ftp_addr) or empty($ftp_port) or empty($ftp_user) or empty($ftp_pass)) go_back("�����д�");
	if (empty($ftp_addr) or empty($ftp_port) or empty($ftp_user)) go_back("�����д�");
	if (!is_numeric($ftp_port)) go_back("�˿ڴ���!");
	config_update("ftp_addr",$ftp_addr,"FTP��ַ");
	config_update("ftp_port",$ftp_port,"FTP�˿�");
	config_update("ftp_user",$ftp_user,"FTP�û���");
	config_update("ftp_pass",$ftp_pass,"FTP����");
	config_update("ftp_dir",$ftp_dir,"FTPĿ¼");
	config_update("ftp_is",$ftp_is,"����");
	config_updatef();
	str_go_url("����ɹ�!",0);	
}
//if (file_exists($backup_ftp_conf)) require_once "$backup_ftp_conf";
require_once(G_T("admin/bk_server.htm"));
?>