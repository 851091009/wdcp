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


if (isset($_POST['cmd'])) {
	$cmd=chop($_POST['cmd']);
	$s1=explode(" ",trim($cmd));
	//if (eregi("rm|dd|sudo",$s1[0])) go_back("��ΪΣ������,�����ڴ˲���");
	//if (eregi("/dev/|mkfs",$cmd)) go_back("��ΪΣ������,�����ڴ˲���");
	wdl_cmd_check($cmd);
		
	//demo
	//if (!eregi("ls|ifconfig|df|free",$s1[0])) go_back("��ʾϵͳ�Բ��ֹ����Ѿ�����!��ִ��ls,ifconfig��");
	if (@in_array($_SERVER["SERVER_ADDR"],$demo_ip) and !eregi("ls|ifconfig|df|free",$s1[0])) go_back("��ʾϵͳ�Բ��ֹ�����������!");//
	if ($wdcp_uid!=1 and eregi("useradd|userdel|usermod|passwd|rc\.d|init\.d",$cmd)) go_back("Ȩ�޴���");
	
	$cmd_tmp=WD_ROOT."/data/tmp/cmd.txt";
	@file_put_contents($cmd_tmp,$cmd);
	//echo file_get_contents($cmd_tmp);//
	exec("sudo /www/wdlinux/wdphp/bin/php /www/wdlinux/wdcp/task/wdcp_sr.php",$str,$re);//print_r($str);print_r($re);
	if (@file_exists($cmd_tmp)) @unlink($cmd_tmp);
	$msg=implode("\n",$str);

	
	
	/*
	$s2="";
	for ($i=1;$i<sizeof($s1);$i++)
		$s2.=$s1[$i]." ";
	$arg=substr($s2,0,strlen($s2)-1);
	exec("sudo wd_sys cmd '$s1[0]' '$arg'",$str,$re);//print_r($str);print_r($re);exit;
	if ($re==11) go_back("�������!");
	elseif ($re=0) go_back("����!");
	else;
	$msg="";
	optlog($wdcp_uid,"ִ������ $cmd",0,0);//
	/*
	for ($i=0;$i<sizeof($str);$i++) {
		if (eregi("wd_",$str[$i])) continue;
		$msg.=$str[$i]."\n";
		}
	*/
}
if (empty($msg)) $msg="";

require_once(G_T("sys/cmd.htm"));

G_T_F("footer.htm");
?>