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

if (isset($_GET['act']) and $_GET['act']=="top") {
//exec("sudo wd_sys top",$str,$re);
$str=wdl_sudo_sys_top();
$msg="";
for ($i=0;$i<sizeof($str);$i++) 
	$msg.=$str[$i]."\n";
require_once(G_T("sys/top.htm"));
}


if (!isset($_GET['act'])) {
$str=array();
/*
exec("sudo wd_sys tops",$str,$re);
//print_r($str);exit;
$s1=explode(",",$str[0]);
//print_r($s1);
if (sizeof($s1)==3)
	$m=str_replace(":","Сʱ ",str_replace("min","",chop($s1[0])))."��";
else
	$m=str_replace("day","��",chop($s1[0])).str_replace(":","Сʱ ",chop($s1[1]))."��";
//print_r($str);
$s1=explode(",",$str[1]);
//print_r($s1);
//$msg.="ϵͳ���� 1����:".chop($s1[0])." 5����:".chop($s1[1])." 15����:".chop($s1[2])."<br><br>";
//print_r($str);
//$msg.="�ڴ�ʹ����:<br>";
//$msg.="�� �� ��:".$str[0]."&nbsp;&nbsp;&nbsp;&nbsp;ʹ���ڴ�:".$str[1]."&nbsp;&nbsp;&nbsp;&nbsp;�����ڴ�:".$str[2]."<br><br>";
//$msg.="�����ڴ�:".$str[5]."&nbsp;&nbsp;&nbsp;&nbsp;��ʹ��:".$str[6]."&nbsp;&nbsp;&nbsp;&nbsp;�����ڴ�:".$str[7]."<br><br>";
//echo "aa";
*/
$load=wdl_server_load(0);
$l1=explode("|",$load);
$mem=wdl_server_mem(0);
//echo $mem;
$m1=explode("|",$mem);
$availfree=$m1[2]+$m1[3]+$m1[4];
$availuse=$m1[0]-$availfree;

$run_time=wdl_server_run_time(0);
//$run_time=urlencode("��");
//echo $run_time;
$ct1=explode("��",$run_time);
$ct2=explode("Сʱ",$ct1[1]);
$ct3=str_replace("��","",$ct2[1]);
//echo $ct[0]."|".$ct2[0]."|".$ct2[1];

$c1=chop($l1[0]);
$c2=chop($l1[1]);
$c3=chop($l1[2]);

$mem1=$m1[0];
$mem2=$m1[1];
$mem3=$m1[2];
$mem5=$m1[5];
$mem6=$m1[6];
$mem7=$m1[7];

if ($_GET['acd'] == "rt") {
	$arr=array();
	$arr=array('run_d'=>"$ct1[0]",'run_h'=>"$ct2[0]",'run_m'=>"$ct3",'load1'=>"$c1",'load2'=>"$c2",'load3'=>"$c3",'mem1'=>"$mem1",'mem2'=>"$mem2",'mem3'=>"$mem3",'real_mem'=>"$mem1",'use_mem'=>"$availuse",'free_mem'=>"$availfree",'sum_swap'=>"$mem5",'use_swap'=>"$mem6",'free_swap'=>"$mem7");
	//print_r($arr);
	$jarr=json_encode($arr);
	echo $_GET['callback'],'(',$jarr,')';
	exit;
}

require_once(G_T("sys/top_res.htm"));
}

//G_T_F("footer.htm");
footer_info();
?>