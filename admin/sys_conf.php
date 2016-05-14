<?php

/*
# WDlinux Control Panel,online management of Linux servers, virtual hosts
# Wdcdn system
# Author:wdlinux QQ:12571192
# Url:http://www.wdlinux.cn/wdcdn
# Last Updated 2011.05
*/

require_once "../inc/common.inc.php";
require_once "../login.php";
require_once "../inc/admlogin.php";
if ($wdcp_gid!=1) exit;
//if ($wdcp_gid!=1 or empty($_SESSION['admin'])) exit;

/*
$conf=WD_ROOT."/data/sys_conf.php";

if (!file_exists($conf)) {
	$templates_dir="templates";
	$is_flow=0;
	$cookie_time=1800;
}else
	require_once "$conf";
*/


//echo $wdcp_user."|".$wdcp_uid."|".$wdcp_gid."|".$wdcp_us."|".$wdcp_lt;

if (isset($_GET['act']) and $_GET['act']=="wls") {
	$web_logs_is=chop($_GET['web_logs_is']);
	$wls_tmp=WD_ROOT."/data/tmp/wls.txt";
	@file_put_contents($wls_tmp,$web_logs_is);
	exec("sudo /www/wdlinux/wdphp/bin/php /www/wdlinux/wdcp/task/wdcp_sr.php",$str,$re);
	if (@file_exists($wls_tmp)) @unlink($wls_tmp);
	if ($web_logs_is=="on")
		config_update("web_logs_is",0,"webĬ����־�޸�");
	else
		config_update("web_logs_is",1,"webĬ����־�޸�");
	config_updatef();
	if ($web_logs_is=="on")
		optlog($wdcp_uid,"����webĬ����־",0,0);
	else
		optlog($wdcp_uid,"�ر�webĬ����־",0,0);
	str_go_url("����ɹ�!",0);		
}

if (isset($_POST['Submit'])) {
	wdl_demo_sys();
	first_install();
	$templates_dir=chop($_POST['templates_dir']);
	$cookie_time=chop($_POST['cookie_time']);
	$is_lc=intval($_POST['is_lc']);
	$is_ll=intval($_POST['is_ll']);

	//$page_num=intval($_POST['page_num']);
	$web_home=chop($_POST['web_home']);
	$web_home_is=intval($_POST['web_home_is']);
	//web_home_check($web_home);
	if (!@is_dir($web_home)) is_dir_check($web_home);
	$backup_home=chop($_POST['backup_home']);
	$trash_home=chop($_POST['trash_home']);
	$site_dir_del_is=intval($_POST['site_dir_del_is']);
	$ftp_dir_del_is=intval($_POST['ftp_dir_del_is']);
	$phpmyadmin_dir=chop($_POST['phpmyadmin_dir']);
	$mysql_quota_is=intval($_POST['mysql_quota_is']);
	$web_port=chop($_POST['web_port']);
	if (substr($web_port,0,2)!=80) go_back("80����ɾ��Ҳ�����滻");
	$web_ip=chop($_POST['web_ip']);
	/*
	if ($web_eng==1 and $web_port!="80") {
		//echo "11";
		$p1=explode(",",$web_port);
		$pm="";
		for ($i=1;$i<sizeof($p1);$i++) {
			if (empty($p1[$i]) or !is_numeric($p1[$i])) continue;
			$pm.="Listen ".$p1[$i]."\n";
		}
		//echo $pm;exit;
		$port_conf="/www/wdlinux/apache/conf/vhost/port.conf";
		if (!@is_writable($port_conf)) file_is_write($port_conf);
		@file_put_contents($port_conf,$pm);
	}
	*/
	make_apache_port($web_eng,$web_port,$web_ip);
	
	$module_list=chop($_POST['module_list']);
	$manager_ip=chop($_POST['manager_ip']);
	$manager_url=chop($_POST['manager_url']);
	$api_ip=chop($_POST['api_ip']);
	$api_pass=chop($_POST['api_pass']);
	$is_reg=intval($_POST['is_reg']);
	
	config_update("templates_dir",$templates_dir,"ģ��Ŀ¼");
	config_update("cookie_time",$cookie_time,"cookie����ʱ��");
	config_update("is_lc",$is_lc,"��֤���¼");
	config_update("is_ll",$is_ll,"���������������");
	//config_update("web_eng",$web_eng,"web��������");
	config_update("web_home",$web_home,"webhomeĿ¼");
	config_update("backup_home",$backup_home,"����Ŀ¼");
	config_update("trash_home",$trash_home,"����վĿ¼");
	config_update("site_dir_del_is",$site_dir_del_is,"ɾ��վ��Ŀ¼");
	config_update("ftp_dir_del_is",$ftp_dir_del_is,"ɾ��FTPĿ¼");
	config_update("phpmyadmin_dir",$phpmyadmin_dir,"phpmyadmin dir");
	config_update("mysql_quota_is",$mysql_quota_is,"mysql����ʹ��");//20130217
	config_update("web_port",$web_port,"web����˿�");
	config_update("web_ip",$web_ip,"web����IP");
	config_update("module_list",$module_list,"Ӧ��ģ��");
	config_update("manager_ip",$manager_ip,"��̨����IP");
	config_update("manager_url",$manager_url,"��̨��������");
	config_update("api_ip",$api_ip,"API�ӿڷ���IP");
	config_update("api_pass",$api_pass,"API�ӿڵ�¼Key");
	config_update("is_reg",$is_reg,"����ע��");
	config_updatef();
	optlog($wdcp_uid,"�޸�ϵͳ����",0,0);
	str_go_url("����ɹ�!",0);	
}

if (isset($_POST['Submit3'])) {
	wdl_demo_sys();
	$web_logs_home=chop($_POST['web_logs_home']);
	if (!@is_dir($web_logs_home)) is_dir_check($web_logs_home);
	$web_logs_logrotate=intval($_POST['web_logs_logrotate']);
	$web_logs_gz=intval($_POST['web_logs_gz']);
	$web_logs_day=intval($_POST['web_logs_day']);
	$site_logs_is=intval($_POST['site_logs_is']);
	
	config_update("web_logs_home",$web_logs_home,"web��־Ŀ¼");
	config_update("web_logs_logrotate",$web_logs_logrotate,"web��־�и�");
	config_update("web_logs_gz",$web_logs_gz,"web��־ѹ��");
	config_update("web_logs_day",$web_logs_day,"web��־���������ļ���");
	config_update("site_logs_is",$site_logs_is,"վ��Ŀ¼");

	config_updatef();

	$wlogs=WD_ROOT."/data/tmp/web_logs.txt";
	@touch($wlogs);
	exec("sudo /www/wdlinux/wdphp/bin/php /www/wdlinux/wdcp/task/wdcp_sr.php",$str,$re);
	if (@file_exists($wlogs)) @unlink($wlogs);
	optlog($wdcp_uid,"�޸�web��־����",0,0);
	str_go_url("����ɹ�!",0);	
}

//��̨�˿�
if (isset($_POST['Submita'])) {
	wdl_demo_sys();
	$o_port=intval($_POST['o_port']);
	$admin_port=intval($_POST['admin_port']);
	if ($admin_port==80) go_back("80�˿�Ҳʹ�ã���̨����ʹ��80�˿�");
	//$str=preg_replace("/^Listen (.*)$/imU","Listen $admin_port",$str,1);
	//$str=preg_replace("/^\<VirtualHost \*:(.*)\>/imU","<VirtualHost *:$admin_port>",$str,1);
	//@file_put_contents($wdapache_conf,$str);
	//echo $str;exit;
	$a_port=WD_ROOT."/data/tmp/a_port.txt";
	@file_put_contents($a_port,$o_port."|".$admin_port);
	exec("sudo /www/wdlinux/wdphp/bin/php /www/wdlinux/wdcp/task/wdcp_sr.php &",$str,$re);//print_r($str);print_r($re);exit;
	if (@file_exists($a_port)) @unlink($a_port);
	optlog($wdcp_uid,"�޸ĺ�̨�˿�",0,0);
	str_go_url("�޸ĳɹ�!",0);		
}

//ftp�˿�
if (isset($_POST['Submit4'])) {
	wdl_demo_sys();
	$o_port=intval($_POST['fo_port']);
	$f_port=intval($_POST['f_port']);
	//$str=preg_replace("/^Listen (.*)$/imU","Listen $admin_port",$str,1);
	//$str=preg_replace("/^\<VirtualHost \*:(.*)\>/imU","<VirtualHost *:$admin_port>",$str,1);
	//@file_put_contents($wdapache_conf,$str);
	//echo $str;exit;
	$f_port_f=WD_ROOT."/data/tmp/f_port.txt";
	@file_put_contents($f_port_f,$o_port."|".$f_port);
	exec("sudo /www/wdlinux/wdphp/bin/php /www/wdlinux/wdcp/task/wdcp_sr.php &",$str,$re);//print_r($str);print_r($re);//exit;
	if (@file_exists($f_port_f)) @unlink($f_port_f);
	optlog($wdcp_uid,"�޸�FTP�˿�",0,0);
	str_go_url("�޸ĳɹ�!",0);		
}

//web�л�
if (isset($_POST['Submit5'])) {
	wdl_demo_sys();
	$web_eng=intval($_POST['web_eng']);
	//echo $web_eng;////
	if ($web_eng==1 and !@is_dir("/www/wdlinux/apache")) str_go_url("��⵽ϵͳû�а�װapache",0);
	elseif ($web_eng==2 and !@is_dir("/www/wdlinux/nginx_php")) str_go_url("��⵽ϵͳû�а�װnginx��php-cgi",0);//
	elseif ($web_eng==3 and (!@is_dir("/www/wdlinux/nginx") or !@is_dir("/www/wdlinux/apache"))) str_go_url("��⵽ϵͳû��ͬʱ��װnginx��apache",0);
	elseif ($web_eng>3 or $web_eng<=0) go_back("����");
	else;
		//go_back("����");
	//echo $web_eng;
	config_update("web_eng",$web_eng,"web��������");
	config_updatef();
	$web_eng_tmp=WD_ROOT."/data/tmp/web_etmp.txt";
	if ($web_eng==1){
		@file_put_contents($web_eng_tmp,"apache");
		make_apache_port($web_eng,$web_port,$web_ip);
	}elseif ($web_eng==2)
		@file_put_contents($web_eng_tmp,"nginx");
	elseif ($web_eng==3){
		@file_put_contents($web_eng_tmp,"na");
		$port_conf="/www/wdlinux/apache/conf/vhost/port.conf";
		if (@file_exists($port_conf)) @unlink($port_conf);
	}else;
	exec("sudo /www/wdlinux/wdphp/bin/php /www/wdlinux/wdcp/task/wdcp_sr.php",$str,$re);//print_r($str);print_r($re);//exit;
	if (@file_exists($web_eng_tmp)) @unlink($web_eng_tmp);	
	optlog($wdcp_uid,"web���������л�",0,0);
	str_go_url("�л��ɹ�!",0);
}

//��̨�˿�
$wdapache_conf="/www/wdlinux/wdapache/conf/httpd.conf";
$str=@file_get_contents($wdapache_conf);
preg_match("/^Listen (.*)$/imU",$str,$s1);
$admin_port=$s1[1];

//FTP�˿�//
$pureftp_conf="/www/wdlinux/etc/pure-ftpd.conf";
$str=@file_get_contents($pureftp_conf);
preg_match("/^Bind\s(.*)$/imU",$str,$s1);
if (empty($s1)) $f_port=21;
else $f_port=$s1[1];


//web��־״̬
if ($web_logs_is==1)
	$web_logs_is_title='<a href="'.$PHP_SELF.'?act=wls&web_logs_is=on">����webĬ����־</a>';
else
	$web_logs_is_title='<a href="'.$PHP_SELF.'?act=wls&web_logs_is=off">�ر�webĬ����־</a>';

require_once(G_T("admin/sys_conf.htm"));
//require_once(G_T_F("footer.htm"));
//G_T_F("footer.htm");
footer_info();
?>
