<?php
require_once "inc/common.inc.php";
require_once "login.php";
$utt="";
if ($dns_is==1){
	if ($wdcp_gid==5)
		$utt="�ͷ�����Ա";
	elseif ($wdcp_gid==6)
		$utt="�������(��˾)";
	elseif ($wdcp_gid==7)
		$utt="�������(����)";
	else;
	$wdcp_user=$utt."".$wdcp_user;
}
require_once(G_T("top.htm"));
?>

