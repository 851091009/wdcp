<?php

if ($wdcp_gid==1) {
?>
<dl>
    <dt><a href="###" onclick="showHide('items3');" target="_self">MYSQL����</a></dt>
    <dd id="items3" style="display:none;">
			<ul>
<li><a href='mysql/fast_add.php' target='mainFrame'>���ٴ���</a></li>
<li><a href='mysql/db_add.php' target='mainFrame'>�������ݿ�</a></li>
<li><a href='mysql/db_list.php' target='mainFrame'>���ݿ��б�</a></li>
<li><a href='mysql/user_add.php' target='mainFrame'>�½����ݿ��û�</a></li>
<li><a href='mysql/user_list.php' target='mainFrame'>���ݿ��û��б�</a></li>
<li><a href='<?=$phpmyadmin_dir;?>' target='mainFrame'>phpmyadmin</a></li>
<li><a href='mysql/chg_rootp.php' target='mainFrame'>�޸�root�û�����</a></li>
<li><a href='mysql/mysql.php' target='mainFrame'>mysql����</a></li>
          </ul>
		</dd>
</dl>
<?php

}else{
?>
<dl>
    <dt><a href="###" onclick="showHide('items3');" target="_self">MYSQL����</a></dt>
    <dd id="items3" style="display:none;">
			<ul>
<li><a href='mysql/fast_add.php' target='mainFrame'>���ٴ���</a></li>
<li><a href='mysql/db_add.php' target='mainFrame'>�������ݿ�</a></li>
<li><a href='mysql/db_list.php' target='mainFrame'>���ݿ��б�</a></li>
<li><a href='mysql/user_add.php' target='mainFrame'>�½����ݿ��û�</a></li>
<li><a href='mysql/user_list.php' target='mainFrame'>���ݿ��û��б�</a></li>
<li><a href='<?=$phpmyadmin_dir;?>' target='mainFrame'>phpmyadmin</a></li>
    </ul>
		</dd>
</dl>
<?php

}
?>