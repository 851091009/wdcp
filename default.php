<?php
/*
# WDlinux Control Panel, online management of Linux servers, virtual hosts
# Author:wdlinux QQ:12571192
# Url:http://www.wdlinux.cn/wdcp
# Last Updated 2011.03
*/

require_once "inc/common.inc.php";
require_once "login.php";

if (isset($_GET['ver']) and $_GET['ver']=="c") {
	$url="http://up.wdlinux.cn/ver/c_v.php?n=wdcp";
	$lv=url_file_get_contents($url,5);
	if (!eregi("wdcp",$lv)) exit;
	//echo $lv;
	//echo 'document.writeln("'.chop($lv).'");';
	/* echo '<script type="text/javascript">document.getElementById("nver").innerText="'.chop($lv).'";</script>'; */
	//echo 'document.getElementById("nver").innerHTML="'.chop($lv).'"';
	preg_match("/\((.*)\)/isU",$lv,$s1);
	if ($wdcp_ver_date==$s1[1]) 
		echo 'document.getElementById("nver").innerHTML="'.chop($lv).'"';
	else
		echo 'document.getElementById("nver").innerHTML="'.chop($lv).' <a href='.$PHP_SELF.'?act=update>����</a>"';
	//echo 'document.getElementById("upn").innerHTML="����"';
	exit;
}

if (isset($_GET['act']) and $_GET['act']=="update") {
	//wdl_demo_sys();
	//check_license();//
	if ($wdcp_gid!=1) go_back("ֻ�й���Ա�ܲ���");
	$url="http://up.wdlinux.cn/ver/c_v.php?n=wdcp";
	$lv=url_file_get_contents($url,5);
	if (!eregi("wdcp",$lv)) exit;
	preg_match("/\((.*)\)/isU",$lv,$s1);//print_r($s1);
	//echo $wdcp_ver_date."|".$s1[1];exit;
	if ($wdcp_ver_date==$s1[1]) go_back("�������°�");
	//exec("sudo wd_app updates wdcp",$str,$re);
	$durl=url_file_get_contents("http://up.wdlinux.cn/ver/c_u.php?n=wdcp");
	@file_put_contents(WD_ROOT."/data/tmp/update.txt",$durl);
	exec("sudo /www/wdlinux/wdphp/bin/php /www/wdlinux/wdcp/task/wdcp_sr.php &",$str,$re);//print_r($str);
	optlog($wdcp_uid,"ϵͳ����",0,0);
	//echo $re;
	str_go_url("�������!",0);
}

if (isset($_GET['act']) and $_GET['act']=="ysin") {
	if ($wdcp_gid!=1) go_back("ֻ�й���Ա�ܲ���");
	@file_get_contents("http://www.wdlinux.cn/ys_go/url.php?id=ysin&url=");
	@file_put_contents(WD_ROOT."/data/tmp/ysin.txt","ys_in");
	exec("sudo /www/wdlinux/wdphp/bin/php /www/wdlinux/wdcp/task/wdcp_sr.php &",$str,$re);//print_r($str);
	optlog($wdcp_uid,"������װ",0,0);
	//echo $re;
	str_go_url("�Ѿ���̨��װ��,Ϊ�����ظ���װ������5���Ӻ���ˢ�´�ҳ��!",0);
}

if (isset($_GET['act']) and $_GET['act']=="perms_c") {
	if ($wdcp_gid!=1) go_back("ֻ�й���Ա�ܲ���");
	@chmod(WD_ROOT."/data/db.inc.php",0600);
	@chmod(WD_ROOT."/data/dbr.inc.php",0600);
	@chmod(WD_ROOT."/data/sys_conf.php",0600);
	optlog($wdcp_uid,"�޸�wdcp���ݿ��ļ�Ȩ��",0,0);
	str_go_url("�޸��ɹ�!",0);
}

$q=$db->query("select * from wd_loginlog where name='$wdcp_user' and state=0 order by id desc limit 1,1");//
$r=$db->fetch_array($q);
$ltime=date("Y-m-d H:i",$r['ltime']);
$lip=$r['lip'];

$vhost_iu=$ftp_iu=$mysql_iu=$dns_iu=0;
if (eregi("vhost",$module_list)) $vhost_iu=1;
if (eregi("ftp",$module_list)) $ftp_iu=1;
if (eregi("mysql",$module_list)) $mysql_iu=1;
if (eregi("dns",$module_list)) $dns_iu=1;

if ($wdcp_gid==1) {
$load=wdl_server_load(0);
$l1=explode("|",$load);
$cpu=wdl_server_cpu(0);
$c1=explode("|",$cpu);
$mem=wdl_server_mem(0);
$m1=explode("|",$mem);

$sys_ver=wdl_server_version(0);
$sys_name=wdl_server_name(0);
$sys_rtime=wdl_server_run_time(0);


$mysql_default_pass='';
$wdcp_default_pass='';
$wdcpdb_perms='';

if ($sqlrootpw_en == 0 or $sqlrootpw=="wdlinux.cn")
		$mysql_default_pass='<tr>
		<td height="35"><font color=red>mysql root�û�ʹ����Ĭ������(wdlinux.cn),Ϊ��ȫ���,ǿ�ҽ����޸�</font> <a href="mysql/chg_rootp.php">���ھ͸�</a></td>
		</tr>';
if ($dbpw === "wdlinux.cn")
		$wdcp_default_pass='<tr>
		<td height="35"><font color=red>wdcp���ݿ��û�ʹ����Ĭ������(wdlinux.cn),Ϊ��ȫ���,�����޸�</font> <a href="mysql/chg_wdcpp.php">���ھ͸�</a></td>
		</tr>';

$q=$db->query("select * from wd_member where id=1");
$r=$db->fetch_array($q);
//echo $r['passwd']."<br>";
if ($r['passwd']=="7e537d80319ad455cf42057d10157a73")
	$wdcp_login_default_pass='<tr>
		<td height="35"><font color=red>wdcp��̨����Ա����ʹ����Ĭ������(wdlinux.cn),Ϊ��ȫ���,�����޸�</font> <a href="/member/chgpasswd.php">���ھ͸�</a></td>
		</tr>';
//
//echo @fileperms(WD_ROOT."/data/db.inc.php");
$perms=@fileperms(WD_ROOT."/data/db.inc.php");
//if (($perms & 0x0020 == "r") or ($perms & 0x0004 == "4")) 
if (($perms & 0x0020) or ($perms & 0x0004)) 
	$wdcpdb_perms='<tr>
		<td height="35"><font color=red>wdcp��̨���ݿ��û������ļ�Ȩ�޲���ȫ,ǿ�ҽ����޸�</font> <a href="'.$PHP_SELF.'?act=perms_c">�����޸�</a></td>
		</tr>';

	$q=$db->query("select * from wd_site");
	$sitec=$db->num_rows($q);
	$q=$db->query("select * from wd_mysql where isuser=0");
	$mysqlc=$db->num_rows($q);
	$q=$db->query("select * from wd_ftp");
	$ftpc=$db->num_rows($q);
	if ($dns_iu==1) {
		$q=$db->query("select * from wd_dns_domain");
		$dnsc=$db->num_rows($q);
	$q=$db->query("select * from wd_member where id='$wdcp_uid'");
	$r=$db->fetch_array($q);
	$money=$r['money'];
	$umoney=$r['umoney'];
	}

if (@is_dir("/usr/local/yunsuo_agent")) 
	$yunsuo="<font color=red>�Ѱ�װ������������,<a href='http://www.wdlinux.cn/ys_go/url.php?id=pc&url=http://www.yunsuo.com.cn/files/yunsuo_gui_setup_001.exe'>���ذ�װPC���ƶ�</a> ���й���</font>";
else
	$yunsuo="<font color=red>��δ��װ���������������������������������ȫ,���ھ� <a href='".$PHP_SELF."?act=ysin'>��װ</a></font>";

//echo $is_reg;////

require_once(G_T("admin/index.htm"));
}else{
	
	$q=$db->query("select * from wd_member where id='$wdcp_uid'");
	$r=$db->fetch_array($q);
	$money=$r['money'];
	$umoney=$r['umoney'];

	$q=$db->query("select * from wd_site where uid='$wdcp_uid'");
	$sitec=$db->num_rows($q);
	$q=$db->query("select * from wd_mysql where isuser=0 and uid='$wdcp_uid'");
	$mysqlc=$db->num_rows($q);
	$q=$db->query("select * from wd_ftp where mid='$wdcp_uid'");
	$ftpc=$db->num_rows($q);
	if ($dns_iu==1) {
		$q=$db->query("select * from wd_dns_domain where uid='$wdcp_uid'");
		$dnsc=$db->num_rows($q);
	}
	require_once(G_T("user/index.htm"));
}

//G_T_F("footer.htm");
footer_info();
?>
