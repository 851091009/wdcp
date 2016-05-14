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
//if ($wdcp_gid!=1) exit;
$adminid=$wdcp_gid;

if (empty($_SERVER['QUERY_STRING'])) $_SESSION['site_dir']="";//

if (isset($_GET['act']) and $_GET['act']=="us") {
	$sid=intval($_GET['sid']);
	//echo $sid;
	$q=$db->query("select * from wd_site where uid='$wdcp_uid' and id='$sid'");
	if ($db->num_rows($q)==0) go_back("վ�����");
	$r=$db->fetch_array($q);
	$vdir=$r['vhost_dir'];
	$_SESSION['site_dir']=$vdir;
}

$site_select='<a href="'.$PHP_SELF.'?act=chg&site_dir=chg">ѡ��վ��</a>';
$site_list="";
if ($wdcp_gid!=1 and (empty($_SESSION['site_dir']) or $_GET['site_dir']=="chg")) {
	$q=$db->query("select * from wd_site where uid='$wdcp_uid'");
	if ($db->num_rows($q)>0){
		$site_list="ѡ��վ��:<br><br>";
		while ($r=$db->fetch_array($q)) {
			$site_list.='����'.$r['domain'].'&nbsp;&nbsp;&nbsp;Ŀ¼'.$r['vhost_dir'].' <a href="'.$PHP_SELF.'?act=us&sid='.$r['id'].'">����</a><br>';
		}
	}else
		$site_list="û��վ����Թ���";
}
//print_r($_SESSION['site_dir']);

//if (!isset($_GET['p'])) $cu_dir=getcwd();
if ($wdcp_gid==1){ 
	if (!isset($_GET['p'])) 
		//$cu_dir="/www/web/default";
		$cu_dir="/www/web";//
	else
		$cu_dir=chop($_GET['p']);
	if (eregi("wdcp",$cu_dir)) $cu_dir="/www/web";//$cu_dir="/www/web/default";
	$site_select="";
	//$home_dir="/www/web/default";
	$home_dir="/www/web";
}else{
	if (!isset($_GET['p']))
		$cu_dir=$_SESSION['site_dir'];
	else{
		$cu_dir=chop($_GET['p']);
		$site_dir_len=strlen($_SESSION['site_dir']);
		if (strcmp(substr($cu_dir,0,$site_dir_len),$_SESSION['site_dir'])!=0) go_back("��Ȩ����");
	}
	$home_dir=$_SESSION['site_dir'];
	if (eregi("wdcp",$cu_dir)) $cu_dir=$_SESSION['site_dir'];
}

$s1=explode("/",$cu_dir);
$cu_file=end($s1);
$s2=strlen($cu_file);
$pre_dir=substr($cu_dir,0,strlen($cu_dir)-($s2+1));
$is_trash_dir=0;
//echo substr($cu_dir,0,11);
if (substr($cu_dir,0,10)==="/www/trash") $is_trash_dir=1;
if (isset($_GET['act']) and $_GET['act']=="get_file") $get_file=1;
else $get_file=0;
if (isset($_GET['act']) and $_GET['act']=="create_dir") $create_dir=1;
else $create_dir=0;
if (isset($_GET['act']) and $_GET['act']=="create_file") $create_file=1;
else $create_file=0;
if (isset($_GET['act']) and $_GET['act']=="upload_file") $upload_file=1;
else $upload_file=0;

if (isset($_GET['act']) and $_GET['act']=="down") {
	$f=chop($_GET['f']);
	$p=chop($_GET['p']);
	$fp=$p."/".$f;
	if (!@file_exists($fp)) js_close("�ļ�������!");//go_back("�ļ�������!");
	if (!@is_readable($fp)) js_close("û��Ȩ��!");
	$file = fopen($fp,"r"); // ���ļ�
	// �����ļ���ǩ
	Header("Content-type: application/octet-stream");
	Header("Accept-Ranges: bytes");
	Header("Accept-Length: ".filesize($fp));
	Header("Content-Disposition: attachment; filename=".$f);
	// ����ļ�����
	echo @fread($file,@filesize($fp));
	@fclose($file);
	exit();
}


if (isset($_POST['Submit_gf'])) {
	wdl_demo_sys();
	$durl=chop($_POST['get_url']);
	if (empty($durl)) go_back("��ַ����Ϊ��!");
	if (strcmp(substr($durl,0,7),"http://")!=0) go_back("��ַ��ʽ����");//
	$durl_tmp=WD_ROOT."/data/tmp/durl.txt";
	$msg=$cu_dir."|".$durl;
	//echo $msg;
	@file_put_contents($durl_tmp,$msg);
	exec("sudo /www/wdlinux/wdphp/bin/php /www/wdlinux/wdcp/task/wdcp_sr.php",$str,$re);//print_r($str);print_r($re);
	if (@file_exists($durl_tmp)) @unlink($durl_tmp);
	optlog($wdcp_uid,"�����ļ� $durl",0,0);//	
	if ($re==0)
		str_go_url("�ļ����ں�̨������!",1);
	else
		str_go_url("����ʧ��!",1);
}

if (isset($_POST['Submit_cdir'])) {
	wdl_demo_sys();
	$dirname=chop($_POST['dirname']);
	if (empty($dirname)) go_back("Ŀ¼������Ϊ��");
	if (eregi("/",$dirname)) go_back("��֧�ֶ༶Ŀ¼");
	if (@is_dir($cu_dir."/".$dirname)) go_back("Ŀ¼�Ѵ���");
	$cdir_tmp=WD_ROOT."/data/tmp/cdir.txt";
	$msg=$cu_dir."|".$dirname;
	@file_put_contents($cdir_tmp,$msg);
	exec("sudo /www/wdlinux/wdphp/bin/php /www/wdlinux/wdcp/task/wdcp_sr.php",$str,$re);//print_r($str);print_r($re);
	if (@file_exists($cdir_tmp)) @unlink($cdir_tmp);
	optlog($wdcp_uid,"����Ŀ¼ $dirname",0,0);//	
	if ($re==0)
		str_go_url("Ŀ¼�����ɹ�!",1);
	else
		str_go_url("����ʧ��!",1);	
}

if (isset($_POST['Submit_cfile'])) {
	wdl_demo_sys();
	$filename=chop($_POST['filename']);
	if (empty($filename)) go_back("�ļ�������Ϊ��");
	if (@is_file($cu_dir."/".$filename)) go_back("�ļ��Ѵ���");
	$cfile_tmp=WD_ROOT."/data/tmp/cfile.txt";
	$msg=$cu_dir."|".$filename;
	@file_put_contents($cfile_tmp,$msg);
	exec("sudo /www/wdlinux/wdphp/bin/php /www/wdlinux/wdcp/task/wdcp_sr.php",$str,$re);//print_r($str);print_r($re);
	if (@file_exists($cfile_tmp)) @unlink($cfile_tmp);
	optlog($wdcp_uid,"�����ļ� $filename",0,0);//	
	if ($re==0)
		str_go_url("�ļ������ɹ�!",1);
	else
		str_go_url("�ļ�ʧ��!",1);	
}

if (isset($_POST['Submit_upfile'])) {
	//print_r($_FILES['upfile']['name']);exit;
	wdl_demo_sys();
	$up_dir="/www/wdlinux/wdcp/data/tmp/";
	$s=0;
	for ($i=0;$i<sizeof($_FILES['upfile']['name']);$i++) {
		//echo $_FILES['upfile']['tmp_name'][$i]."|".$_FILES['upfile']['name'][$i]."<br>";
		if (empty($_FILES['upfile']['name'][$i])) continue;
		//if (eregi("\ ",$_FILES['upfile']['name'])) echo "aa";exit;
		@move_uploaded_file($_FILES['upfile']['tmp_name'][$i],str_replace(" ","_",$up_dir.$_FILES['upfile']['name'][$i]));
		$flist.=str_replace(" ","_",$_FILES['upfile']['name'][$i])." ";
		$s++;
	}
	//echo $flist;exit;
	$ufile_tmp=WD_ROOT."/data/tmp/ufile.txt";
	$msg=$cu_dir."|".$flist;
	@file_put_contents($ufile_tmp,$msg);
	//echo $msg;
	exec("sudo /www/wdlinux/wdphp/bin/php /www/wdlinux/wdcp/task/wdcp_sr.php",$str,$re);//print_r($str);print_r($re);
	if (@file_exists($ufile_tmp)) @unlink($ufile_tmp);
	optlog($wdcp_uid,"�ϴ� $s ���ļ�",0,0);//	
	if ($re==0)
		str_go_url("�ɹ��ϴ� $s ���ļ�!",1);
	else
		str_go_url("�ϴ�ʧ��!",1);//
}

if (isset($_POST['Submit_act'])) {
	//$num=@array_keys($_POST['num']);
	//if (sizeof($num)==0) go_back("��ѡ���ļ�!");
	$num=$_POST['num'];//print_r($num);
	if (empty($num)) go_back("��ѡ���ļ���Ŀ¼");//
	$act_more=isset($_POST['act_more'])?1:0;//echo $act_more;go_back("");
	$act=chop($_POST['act']);
	if ($act===0) go_back("��ѡ�����:���,ɾ��,�޸ĵ�!");
	$act_name=chop($_POST['act_name']);
	$flist="";
	for ($i=0;$i<sizeof($num);$i++) {
		//echo $num[$i]."<br>";
		$flist.=$num[$i]." ";
	}
	//echo "|".$flist."|<br>";exit;
	if ($act=="tar") {
		wdl_demo_sys();
		//echo "tar";
		/*
		if (empty($act_name)) {
			exec("sudo wd_app tar '$cu_dir' no '$flist'",$str,$re);
			optlog($wdcp_uid,"����ļ� $flist",0,0);//
			check_re($re,1,"����!/�Ѵ��");
		}else{
			exec("sudo wd_app tar '$cu_dir' '$act_name' '$flist'",$str,$re);
			optlog($wdcp_uid,"����ļ� $flist",0,0);//
			check_re($re,1,"����!/�Ѵ��");
		}
		*/
		$tar_tmp=WD_ROOT."/data/tmp/tar.txt";
		if (empty($act_name))
			$msg="$cu_dir|no|".chop($flist);
		else
			$msg="$cu_dir|$act_name|".chop($flist);
		@file_put_contents($tar_tmp,$msg);
		exec("sudo /www/wdlinux/wdphp/bin/php /www/wdlinux/wdcp/task/wdcp_sr.php",$str,$re);//print_r($str);print_r($re);
		if (@file_exists($tar_tmp)) @unlink($tar_tmp);
		optlog($wdcp_uid,"����ļ� $flist",0,0);//
		check_re($re,1,"����!/�Ѵ��");
	}elseif ($act=="del") {
		//demo
		wdl_demo_sys();
		
		//exec("sudo wd_app del '$cu_dir' '$flist'",$str,$re);//print_r($str);print_r($re);exit;
		$del_tmp=WD_ROOT."/data/tmp/del.txt";
		$msg="$cu_dir|".chop($flist);
		@file_put_contents($del_tmp,$msg);
		exec("sudo /www/wdlinux/wdphp/bin/php /www/wdlinux/wdcp/task/wdcp_sr.php",$str,$re);//print_r($str);print_r($re);	
		if (@file_exists($del_tmp)) @unlink($del_tmp);
		optlog($wdcp_uid,"ɾ���ļ� $flist",0,0);//
		check_re($re,1,"����!/ɾ���ɹ�");
	}elseif ($act=="move") {
		wdl_demo_sys();
		if (empty($act_name)) go_back("������Ҫ�ƶ�����Ŀ¼!");
		//echo "move";
		$move_tmp=WD_ROOT."/data/tmp/move.txt";
		$msg="$cu_dir|$act_name|".chop($flist);
		//echo $msg;
		@file_put_contents($move_tmp,$msg);
		exec("sudo /www/wdlinux/wdphp/bin/php /www/wdlinux/wdcp/task/wdcp_sr.php",$str,$re);//print_r($str);print_r($re);	
		if (@file_exists($move_tmp)) @unlink($move_tmp);
		optlog($wdcp_uid,"�ƶ��ļ� $flist ",0,0);//
		check_re($re,1,"����!/�ƶ��ɹ�");		
	}elseif ($act=="copy") {
		wdl_demo_sys();
		if (empty($act_name)) go_back("������Ҫ���Ƶ���Ŀ¼!");
		//echo "move";
		$copy_tmp=WD_ROOT."/data/tmp/copy.txt";
		$msg="$cu_dir|$act_name|".chop($flist);
		//echo $msg;
		@file_put_contents($copy_tmp,$msg);
		exec("sudo /www/wdlinux/wdphp/bin/php /www/wdlinux/wdcp/task/wdcp_sr.php",$str,$re);//print_r($str);print_r($re);	
		if (@file_exists($copy_tmp)) @unlink($copy_tmp);
		optlog($wdcp_uid,"�����ļ� $flist ",0,0);//
		check_re($re,1,"����!/���Ƴɹ�");	
	}elseif ($act=="perm") {
		//echo "perm";
		//demo
		wdl_demo_sys();
		if (empty($act_name)) go_back("��������Ӧ��Ȩ��,��777,755");
		if (!is_numeric($act_name)) go_back("�����д�,��������777,755");
		//
		//for ($i=0;$i<strlen($act_name);$i++) {
		for ($i=0;$i<3;$i++) {
			$perm_num=array("4","2","1","5","6","7");
			if (!in_array($act_name[$i],$perm_num)) go_back("�����д�,��������777,755");
			//echo $act_name[$i];
			//exec("sudo wd_app perm '$cu_dir' '$act_more' '$act_name' '$flist'",$str,$re);
			//optlog($wdcp_uid,"�޸��ļ�Ȩ�� $flist",0,0);//
			//check_re($re,1,"����!/����Ȩ�޳ɹ�!");
		}
		$perm_tmp=WD_ROOT."/data/tmp/perm.txt";
		$msg="$act_name|$act_more|$cu_dir|".chop($flist);
		@file_put_contents($perm_tmp,$msg);
		exec("sudo /www/wdlinux/wdphp/bin/php /www/wdlinux/wdcp/task/wdcp_sr.php",$str,$re);
		if (@file_exists($perm_tmp)) @unlink($perm_tmp);
		optlog($wdcp_uid,"�޸��ļ�Ȩ�� $flist",0,0);//
		check_re($re,1,"����!/����Ȩ�޳ɹ�!");
	}elseif ($act=="ower") {
		//demo
		wdl_demo_sys();
		//echo "user";
		if (empty($act_name)) go_back("�������û������û�ID");
		//exec("sudo wd_app ower '$cu_dir' '$act_more' '$act_name' '$flist'",$str,$re);
		$ower_tmp=WD_ROOT."/data/tmp/ower.txt";
		$msg="$act_name|$act_more|$cu_dir|".chop($flist);
		@file_put_contents($ower_tmp,$msg);
		exec("sudo /www/wdlinux/wdphp/bin/php /www/wdlinux/wdcp/task/wdcp_sr.php",$str,$re);//print_r($str);print_r($re);
		if (@file_exists($ower_tmp)) @unlink($ower_tmp);
		optlog($wdcp_uid,"�޸��ļ��û� $flist",0,0);//
		if ($re==12) go_back("���û�������!");
		check_re($re,1,"����!/���������߳ɹ�!");
	}elseif ($act=="group") {
		//demo
		wdl_demo_sys();

		//echo "group";
		if (empty($act_name)) go_back("��������������ID");
		//exec("sudo wd_app owerg '$cu_dir' '$act_more' '$act_name' '$flist'",$str,$re);
		$group_tmp=WD_ROOT."/data/tmp/group.txt";
		$msg="$act_name|$act_more|$cu_dir|".chop($flist);
		@file_put_contents($group_tmp,$msg);
		exec("sudo /www/wdlinux/wdphp/bin/php /www/wdlinux/wdcp/task/wdcp_sr.php",$str,$re);//print_r($str);print_r($re);
		if (@file_exists($group_tmp)) @unlink($group_tmp);
		optlog($wdcp_uid,"�޸��ļ����� $flist",0,0);//
		if ($re==12) go_back("���û��鲻����!");
		check_re($re,1,"����!/����������ɹ�!");
	}else
		go_back("ûѡ��Ҫ��ʲô����");
	//echo "OK";
}

if (isset($_POST['Submit_edit'])) {
	//demo
	wdl_demo_sys();
		
	$tn=time();
	$tmpdir=WD_ROOT."/data/".$tn;
	$fn=chop($_POST['fn']);
	
	//����ļ�,���Ʊ༭�����ļ�
	if ($wdcp_uid!=1 and ereg("rc\.d|init\.d",$fn)) go_back("���ļ������޸Ĳ���");
	
	$content=stripslashes(chop($_POST['contents']));
	@file_put_contents($tmpdir,$content);
	//echo "|".$tmpdir."|<br>";
	//echo "|".$fn."|<br>";
	//exec("sudo wd_app cp '$tmpdir' '$fn'",$str,$re);
	//exec("sudo wd_app test",$str,$re);
	//unlink($tmpdir);
	//print_r($str);print_r($re);exit;
	$cp_tmp=WD_ROOT."/data/tmp/cp.txt";
	$msg="$tmpdir|$fn";
	@file_put_contents($cp_tmp,$msg);
	exec("sudo /www/wdlinux/wdphp/bin/php /www/wdlinux/wdcp/task/wdcp_sr.php",$str,$re);//print_r($str);print_r($re);
	if (@file_exists($cp_tmp)) @unlink($cp_tmp);
	optlog($wdcp_uid,"�޸��ļ� $fn",0,0);//
	if ($re==0) 
		str_go_url("���޸ĸ���!","filem.php?p=".$pre_dir);
	else
		go_back("�޸�����!");
	exit;
	//echo $tmpdir."<br>";
	//echo $fn."<br>";
}

if (isset($_GET['act']) and $_GET['act']=="del") {
	//demo
	wdl_demo_sys();
		
	if ($is_trash_dir==1) go_back("����վ���ݲ����ڴ�ɾ��!");
	$t=chop($_GET['t']);
	$f=chop($_GET['p']);
	//echo $t."|".$f."<br>";exit;
	//exec("sudo wd_app del '$pre_dir' '$f'",$str,$re);//print_r($str);print_r($re);exit;

	$del_tmp=WD_ROOT."/data/tmp/del.txt";
	$msg="$pre_dir|$f";
	@file_put_contents($del_tmp,$msg);
	exec("sudo /www/wdlinux/wdphp/bin/php /www/wdlinux/wdcp/task/wdcp_sr.php",$str,$re);//print_r($str);print_r($re);	
	if (@file_exists($del_tmp)) @unlink($del_tmp);
	optlog($wdcp_uid,"ɾ���ļ� $f",0,0);//
	check_re($re,1,"����!/��ɾ��");
}

if (isset($_GET['act']) and  chop($_GET['act'])=="tar") {
	if (!@file_exists($cu_dir)) go_back("�ļ�������!");
	$t1=explode(".",$cu_file);
	$t2=end($t1);
	//echo "tara xvf '$pre_dir' '$cu_file'";
	//optlog($wdcp_uid,"��ѹ�ļ� $cu_file",0,0);//
	/*
	if ($t2=="tar") {
		exec("sudo wd_app tara xvf '$pre_dir' '$cu_file'",$str,$re);//print_r($str);print_r($re);exit;
		check_re($re,1,"����!/��ѹ���!");
	}elseif ($t2=="gz" or $t2=="tgz"){
		exec("sudo wd_app tara zxvf '$pre_dir' '$cu_file'",$str,$re);//print_r($str);print_r($re);exit;
		check_re($re,1,"����!/��ѹ���");
	}elseif ($t2=="bz2"){
		exec("sudo wd_app tara jxvf '$pre_dir' '$cu_file'",$str,$re);//print_r($str);print_r($re);exit;
		check_re($re,1,"����!/��ѹ���");
	}elseif ($t2=="zip") {
		exec("sudo wd_app zip '$pre_dir' '$cu_file'",$str,$re);
		check_re($re,1,"����!/��ѹ���");
	}else{
		go_back("�ļ����ʹ���!");
		exit;
	}
	*/
	$untar_tmp=WD_ROOT."/data/tmp/untar.txt";
	$msg="$pre_dir|$cu_file";
	@file_put_contents($untar_tmp,$msg);
	exec("sudo /www/wdlinux/wdphp/bin/php /www/wdlinux/wdcp/task/wdcp_sr.php",$str,$re);//print_r($str);print_r($re);
	if (@file_exists($untar_tmp)) @unlink($untar_tmp);
	optlog($wdcp_uid,"��ѹ�ļ� $cu_file",0,0);//
	check_re($re,1,"����!/��ѹ���");
}



if (isset($_GET['act']) and ($_GET['act']=="edit") and $_GET['t']=="f") {
	//echo $cu_dir;
	if (!@file_exists($cu_dir)) go_back("�ļ�������!");
	//if (!is_readable($cu_dir)) go_back("�ļ����ɶ�!");
	//exec("sudo wd_app mab '$cu_dir'",$str,$re);
	//echo $cu_dir;print_r($str);print_r($re);exit;
	//if ($re==0) go_back("�������ļ������޸�!");
	
	if (@is_executable($cu_dir)) {//go_back("ִ���ļ����ɱ༭");
	//�ж϶������ļ�
		$allow_code=array("3533","8372","3510","3532");
		$fp = @fopen($cu_dir, "rb");
		$bin = @fread($fp,2);
		@fclose($fp);
		$str_info  = @unpack("C2chars", $bin);
		$type_code = intval($str_info['chars1'].$str_info['chars2']);
		//echo $type_code;//exit;
		if ($type_code==12769 or $type_code==0) go_back("�������ļ�,�����Ա༭");
	}
	
	
	$str=@file_get_contents($cu_dir);
	//preg_match("/charset=(.*)('|\") /isU",$str,$s1);
	preg_match("/charset=(.*)('|\"| )/isU",$str,$s1);
	//print_r($s1);
	if (empty($s1[1])) {
		$charset="gb2312";
		$title="�ļ��޸�/�༭";
		$cu_title="��ǰ�ļ�";
		$bu_save="����";
		$bu_reset="����";
		$bu_return="����";
	}else{
		$charset=$s1[1];
		//echo $charset;
		$title=mb_convert_encoding("�ļ��޸�/�༭","$charset","GBK");
		$cu_title=mb_convert_encoding("��ǰ�ļ�","$charset","GBK");
		$bu_save=mb_convert_encoding("����","$charset","GBK");
		$bu_reset=mb_convert_encoding("����","$charset","GBK");
		$bu_return=mb_convert_encoding("����","$charset","GBK");
	}
	//echo $title;
	require_once(G_T("sys/filem_edit.htm"));
	exit;
}

//if (!shell_is_dir($cu_dir)) $cu_dir=getcwd();
//if (!is_dir($cu_dir)) $cu_dir=getcwd();
if (!@is_dir($cu_dir)) $cu_dir="/www/web/default";
if (eregi("wdcp",$cu_dir)) $cu_dir="/www/web/default";
if (empty($pre_dir)) $pre_dir="/";


/*
echo get_cfg_var("open_basedir");
function open_dir($dir) {
	if (get_cfg_var("open_basedir")==="") {
		echo "11";
		return php_open_dir($dir);}
	else {
		echo "22";
		return shell_open_dir($dir);}
}
*/
//define(open_dir,php_open_dir);
//define(open_dir,shell_open_dir);
//$str=shell_open_dir($cu_dir);
//print_r($str);
//exit;
//�ļ��� ���� ӵ���� Ȩ�� ʱ�� ��С
//echo gmdate("Y-m-d H:i",$ctime);echo "<br>";
//echo $cu_dir."<br>";
//echo $pre_dir."<br>";
//$predir=
//echo getcwd()."\\..";
//echo '<a href="'.$PHP_SELF.'?p='.$pre_dir.'">��һ��Ŀ¼</a><br>';


$str=php_open_dir($cu_dir);//print_r($str);
$list=array();
for ($i=0;$i<sizeof($str);$i++) {
	//echo $str[$i]."<br>";
	$s1=explode("|",$str[$i]);
	$s11=$cu_dir."/".$s1[0];
	if ($cu_dir=="/") $s11="/".$s1[0];
	//if (shell_is_dir($s11)) {
	if (is_dir($s11)) {
		$a1='<a href="'.$PHP_SELF.'?p='.$s11.'&act=list&t=d"><font color="#0000FF">'.$s1[0].'</font></a>';
		$mlink='<a href="'.$PHP_SELF.'?p='.$s11.'&act=list&t=d">��</a>';
		$dlink=$PHP_SELF.'?p='.$s11.'&act=del&t=d';
	}else{
		$a1='<a href="'.$PHP_SELF.'?p='.$s11.'&act=edit&t=f">'.$s1[0].'</a>';
		$mlink='<a href="'.$PHP_SELF.'?p='.$s11.'&act=edit&t=f">��</a>';
		$m11=explode(".",$s1[0]);
		$m12=".".end($m11);
		if (eregi(".gz|.gif|.bin|.jpg|.bmp|.zip",$m12)) {
			$a1=$s1[0];
			$mlink="";
		}
		$dlink=$PHP_SELF.'?p='.$s11.'&act=del&t=f';
	}
	if ($s1[1]=="file") {
		$a2="�ļ�";
		$down='<a href="'.$PHP_SELF.'?act=down&f='.$s1[0].'&p='.$cu_dir.'" target=_blank>����</a>';	
	}else{
		$a2="Ŀ¼";
		$down="";
	}
	//tar1=
	if (substr($s1[0],-3)==".gz" or substr($s1[0],-4)==".tgz" or substr($s1[0],-4)==".tar" or substr($s1[0],-4)==".zip")
		$tar='<a href="'.$PHP_SELF.'?p='.$s11.'&act=tar&t=f">��ѹ</a>';
	else
		$tar="";
	
	$list[$i]['id']=$i;
	$list[$i]['a1']=$a1;
	$list[$i]['a2']=$a2;
	$list[$i]['s10']=$s1[0];
	$list[$i]['s12']=$s1[2];
	$list[$i]['s13']=$s1[3];
	$list[$i]['s14']=$s1[4];
	$list[$i]['s15']=$s1[5];
	$list[$i]['s16']=$s1[6];
	$list[$i]['mlink']=$mlink;
	$list[$i]['dlink']=$dlink;
	$list[$i]['tar']=$tar;
	$list[$i]['down']=$down;
}
//array_multisort($isdir,SORT_DESC,$time,SORT_DESC,$fileArr);  
//array_multisort($list[]['a1'],SORT_DESC,$list);
require_once(G_T("sys/filem.htm"));

//G_T_F("footer.htm");
footer_info();
?>