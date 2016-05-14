<?php
/*
# WDlinux Control Panel, online management of Linux servers, virtual hosts
# Author:wdlinux QQ:12571192
# Url:http://www.wdlinux.cn/wdcp
# Last Updated 2011.03
*/

require_once "../inc/common.inc.php";
require_once "../login.php";
//require_once "../inc/admlogin.php";
//if ($wdcp_gid!=1) exit;


if (isset($_POST['Submit_add'])) {
	$id=chop($_POST['sid']);
	$sdomain=chop($_POST['sdomain']);
	if (!eregi("[a-z0-9]{1,50}",$sdomain)) go_back("�����д�!");
	$domain=chop($_POST['domain']);
	$domain=$sdomain.".".$domain;
	$domains=str_replace("http://","",chop($_POST['domains']));
	
	//if (!eregi("[a-z0-9]{1,50}",$domain)) go_back("�����д�!");
	$dir=chop($_POST['dir']);
	$dir_index=chop($_POST['dir_index']);
	$err400=chop($_POST['err400']);
	$err401=chop($_POST['err401']);
	$err403=chop($_POST['err403']);
	$err404=chop($_POST['err404']);
	$err405=chop($_POST['err405']);
	$err500=chop($_POST['err500']);
	$cdir=chop($_POST['cdir']);
	if (!empty($cdir))
		if (!@is_dir($cdir)) go_back("�Զ���Ŀ¼������,��������ȷ�����Ѵ��ڵ�Ŀ¼");
		else
			$dir=$cdir;
	
	wdl_vhostdir_check($dir);
	
	$sql="insert into wd_site(id,uid,domain,domains,sdomain,vhost_dir,dir_index,err400,err401,err403,err404,err405,err500,err503,rtime,state) values(NULL,'$wdcp_uid','$domain','$domains','$id','$dir','$dir_index','$err400','$err401','$err403','$err404','$err405','$err500','$err503','$ctime',0);";
	$q=$db->query($sql);
	$id=$db->insert_id();
	update_vhost($id);
	web_reload();
	optlog($wdcp_uid,"���Ӷ������� $domain",0,0);//
	//web_reload();
	//exit;//
	if (!$q) go_back("������������ʧ��!");
	str_go_url("����������ӳɹ�!",0);
	exit;
}


if (isset($_GET['act']) and ($_GET['act']=="off")) {
	$id=intval($_GET['id']);
	$domain=chop($_POST['domain']);
	if ($wdcp_gid==1)
		$q=$db->query("select * from wd_site where id='$id'");
	else
		$q=$db->query("select * from wd_site where uid='$wdcp_uid' and id='$id'");
	if ($db->num_rows($q)==0) go_back("ID�����ڣ�");
	update_vhost_del($id);
	$re=$db->query("update wd_site set state=1 where id='$id'");
	web_reload();
	optlog($wdcp_uid,"��ͣվ�� $domain",0,0);//
	//if ($re==0) 
	str_go_url("�ѹر�!",0);
	//else
		//go_back("�رմ���!");
	exit;
}


if (isset($_GET['act']) and ($_GET['act']=="on")) {
	$id=intval($_GET['id']);
	$domain=chop($_GET['domain']);
	if ($wdcp_gid==1)
		$q=$db->query("select * from wd_site where id='$id'");
	else
		$q=$db->query("select * from wd_site where uid='$wdcp_uid' and id='$id'");
	if ($db->num_rows($q)==0) go_back("ID�����ڣ�");
	update_vhost($id);
	$re=$db->query("update wd_site set state=0 where id='$id'");
	web_reload();
	optlog($wdcp_uid,"����վ�� $domain",0,0);//
	str_go_url("�ѿ���!",0);
	exit;
}

if (isset($_GET['act']) and $_GET['act']=="del") {
	$id=intval($_GET['id']);
	$domain=chop($_GET['domain']);
	if ($wdcp_gid==1)
		$sql=$db->query("select * from wd_site where id='$id'");
	else
		$sql=$db->query("select * from wd_site where uid='$wdcp_uid' and id='$id'");
	if ($db->num_rows($sql)==0) go_back("ID����");
	//$re=$db->fetch_array($sql);
	//$vf=chop($re['domain']).".conf";
	//$tempfn=$ws_vhost."/".$vf;
	////exec("sudo wd_app rm '$tempfn' no",$str,$re);
	//$re=wdl_sudo_app_rm($tempfn);
	update_vhost_del($id);
	web_reload();
	//web_reload();
	if ($re!=0) go_back("��������ɾ��ʧ��!");
	//echo "delete from wd_host where id='$id'";
	$q=$db->query("delete from wd_site where id='$id'");
	if (!$q) go_back("��������ɾ������!");
	optlog($wdcp_uid,"ɾ���������� $domain",0,0);//
	//echo $re['domain']." ɾ���ɹ�!<br><br>";
	str_go_url("����������ɾ��!",0);
	exit;
}


if (isset($_GET['act']) and $_GET['act']=="add") {
	$id=intval($_GET['id']);
	if (empty($id)) go_back("��ѡ��������!");
	$q=$db->query("select * from wd_site where id='$id'");
	$re=$db->fetch_array($q);
	if ($re['id']=="") go_back("��������!");
	$vdir=$re['vhost_dir']."/public_html";
	if (!is_dir($vdir))
		$vdir=$re['vhost_dir'];
	//echo $vdir;exit;
	$fd=opendir($vdir);
	$msg="";
	while ($buffer=readdir($fd)) {
		$vd=$vdir."/".$buffer;
		//echo $buffer."<br>";
		if (!is_dir($vd)) continue;
		if ($buffer=="." or $buffer=="..") continue;
			$msg.='<option value="'.$vd.'">'.$vd.'</option>\n';
	}
	closedir($fd);
	require_once(G_T("vhost/sdomain_add.htm"));
	G_T_F("footer.htm");
	exit;
}
$id=isset($_GET['sid'])?intval($_GET['sid']):0;
if (isset($_GET['sid']))
	if ($wdcp_gid==1)
		$q=$db->query("select * from wd_site where sdomain='$id' order by id");
	else
		$q=$db->query("select * from wd_site where uid='$wdcp_uid' and sdomain='$id' order by id");
else
	if ($wdcp_gid==1)
		$q=$db->query("select * from wd_site where sdomain!=0 order by id");
	else
		$q=$db->query("select * from wd_site where uid='$wdcp_uid' and sdomain!=0 order by id");
if ($id=="")
	$add="";
else
	$add='<a href="'.$PHP_SELF.'?act=add&id='.$id.'" class="red">����</a>';

$ii=1;
$i=0;
$list=array();
while ($r=$db->fetch_array($q)) {

	if ($r['state']=="0") {
		$s11="����";
		$s12='<a href="'.$PHP_SELF.'?act=off&id='.$r['id'].'&domain='.$r['domain'].'">��</a>';
	}else{
		$s11="�ر�";
		$s12='<a href="'.$PHP_SELF.'?act=on&id='.$r['id'].'&domain='.$r['domain'].'">��</a>';	
	}

	$list[$i]['id']=$r['id'];
	$list[$i]['domain']=$r['domain'];
	$list[$i]['domains']=$r['domains'];
	$list[$i]['vhost_dir']=$r['vhost_dir'];
	$list[$i]['dir_index']=$r['dir_index'];
	$list[$i]['act']=$s12;
	$list[$i]['time']=date("Y-m-d",$r['rtime']);
	$i++;
}
require_once(G_T("vhost/sdomain_list.htm"));
//G_T_F("footer.htm");
footer_info();
?>

