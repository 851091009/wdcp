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


$dir=WD_ROOT."/data/rewrite";
if (!is_dir($dir))
	cdir($dir);

/*
if ($ws_dir=="/usr")
	$rewrite_dir="/etc/httpd/conf/rewrite";
else
	$rewrite_dir=$ws_dir."/conf/rewrite";
//echo $rewrite_dir;
if (!is_dir($rewrite_dir))
	//exec("sudo wd_app mkdir '$rewrite_dir'",$str,$re);
	wdl_sudo_app_mkdir($rewrite_dir);
*/
//echo $wdcp_uid."| wdcp_uid";
//print_r($_COOKIE);
//echo $wdcp_uid."|".$wdcp_user."|".$wdcp_gi;

if (isset($_POST['Submit'])) {
	wdl_demo_sys();
	$fn=chop($_POST['fn']);
	if (substr($fn,-5)!==".conf") go_back("�ļ�������,����.conf�ļ�����׺");
	//if (@file_exists($dir."/".$fn)) go_back("���ļ����Ѵ���!",0);
	//$c=chop($_POST['contents']);
	$c=stripslashes($_POST['contents']);
	//echo $fn."<br>";
	//echo $c;
	$tempfn=$dir."/".$fn;
	//file_put_contents($tempfn,str_replace("\\\\","\\",$c));
	file_put_contents($tempfn,$c);
	update_rewrite($fn);
	//echo $tempfn."|".$rewrite_dir;
	//exec("sudo wd_app cp '$tempfn' '$rewrite_dir'",$str,$re);//print_r($str);print_r($re);
	//$re=wdl_sudo_app_copy($tempfn,$rewrite_dir);
	optlog($wdcp_uid,"����rewrite���� $fn",0,0);
	if ($re==0)
		str_go_url("�ѱ���ɹ�!",0);
	else
		go_back("����ʧ��!");
	//echo "�ѱ���!<br>";
	exit;
}
if (isset($_POST['Submit_edit'])) {
	wdl_demo_sys();
	$fn=chop($_POST['fn']);
	if (substr($fn,-5)!==".conf") go_back("�ļ�������,����.conf�ļ�����׺");
	//if (@file_exists($dir."/".$fn)) go_back("���ļ����Ѵ���!",0);
	$c=stripslashes($_POST['contents']);
	//echo $fn."<br>";
	//echo $c;
	$tempfn=$dir."/".$fn;
	//file_put_contents($tempfn,str_replace("\\\\","\\",$c));
	file_put_contents($tempfn,$c);
	update_rewrite($fn);
	//exec("sudo wd_app cp '$tempfn' '$rewrite_dir'",$str,$re);
	//$re=wdl_sudo_app_copy($tempfn,$rewrite_dir);
	//echo "�ѱ���!<br>";
	optlog($wdcp_uid,"�޸�rewrite���� $fn",0,0);
	if ($re==0)
		str_go_url("�ѱ���ɹ�!",0);
	else
		go_back("����ʧ��!");
	exit;
}

if (isset($_GET['act']) and $_GET['act']=="del") {
	wdl_demo_sys();
	$fn=chop($_GET['f']);
	if (!file_exists($dir."/".$fn)) go_back("�ļ�������!",0);
	unlink($dir."/".$fn);
	update_rewrite_del($fn);
	//$tempfn=$rewrite_dir."/".$fn;
	//exec("sudo wd_app rm '$tempfn' no",$str,$re);
	//$re=wdl_sudo_app_rm($tempfn);
	//echo "<br>�ļ���ɾ��!<br><br>";
	optlog($wdcp_uid,"ɾ��rewrite���� $fn",0,0);
	if ($re==0)
		str_go_url("�ļ���ɾ��!",1);
	else
		go_back("ɾ��ʧ��!");
	exit;

}

if (isset($_GET['act']) and $_GET['act']=="add") {
	require_once(G_T("vhost/rewrite_add.htm"));
	//exit;
}

if (isset($_GET['act']) and $_GET['act']=="edit") {
	$fn=chop($_GET['f']);
	if (!file_exists($dir."/".$fn)) go_back($fn."�ļ�������!",0);
	$c=wdl_file_get_contents($dir."/".$fn);
	require_once(G_T("vhost/rewrite_edit.htm"));
	G_T_F("footer.htm");
	exit;
}


$od=opendir($dir);
$i=0;
while ($odf=readdir($od)) {
	if ($odf==="." or $odf==="..") continue;
	//echo $odf."<br>";
	$list[$i]['file']=$odf;
	$i++;
}
closedir($od);
require_once(G_T("vhost/rewrite.htm"));
//G_T_F("footer.htm");
footer_info();
?>