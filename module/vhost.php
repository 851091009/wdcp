<?php

if ($wdcp_gid==1) {
?>
<dl>
    <dt><a href="###" onclick="showHide('items2');" target="_self">��վ����</a></dt>
    <dd id="items2" style="display:block;">
			<ul>
<li><a href='vhost/vhost_adda.php' target='mainFrame'>������վ</a></li>
<li><a href='vhost/vhost_add.php' target='mainFrame'>�½�վ��</a></li>
<li><a href='vhost/vhost_list.php' target='mainFrame'>վ���б�</a></li>
<li><a href='vhost/subdomain.php' target='mainFrame'>��������</a></li>
<li><a href='vhost/htpasswd.php' target='mainFrame'>��֤����</a></li>
<li><a href='vhost/rewrite.php' target='mainFrame'>rewrite�������</a></li>
<li><a href='vhost/php.php' target='mainFrame'>php����</a></li>
          </ul>
		</dd>
</dl>
<?php

}else{
?>
<dl>
    <dt><a href="###" onclick="showHide('items2');" target="_self">վ�����</a></dt>
    <dd id="items2" style="display:block;">
			<ul>
<li><a href='vhost/vhost_adda.php' target='mainFrame'>������վ</a></li>
<li><a href='vhost/vhost_add.php' target='mainFrame'>�½�վ��</a></li>
<li><a href='vhost/vhost_list.php' target='mainFrame'>վ���б�</a></li>
<li><a href='vhost/subdomain.php' target='mainFrame'>��������</a></li>
<li><a href='vhost/htpasswd.php' target='mainFrame'>��֤����</a></li>
<li><a href='sys/filem.php' target='mainFrame'>�ļ�������</a></li>
          </ul>
		</dd>
</dl>
<?php

}
?>