<?php

if ($wdcp_gid==1) {
?>
<dl>
    <dt><a href="###" onclick="showHide('dns1');" target="_self">����DNSϵͳ</a></dt>
    <dd id="dns1" style="display:block;">
			<ul>
				<li><a href="dns/sys_set.php" target="mainFrame">DNS����������</a></li>
				<li><a href="dns/domain_add.php" target="mainFrame">��������</a></li>
				<li><a href="dns/domain_list.php" target="mainFrame">�����б�</a></li>
				<?php if ($dns_ptr_is==1) { ?>
				<li><a href="dns/ptr_list.php" target="mainFrame">PTR�б�</a></li> 
				<?php } ?>
				<?php if ($dns_url_is==1) { ?>
				<li><a href="dns/url_list.php" target="mainFrame">URL�б�</a></li>
				<?php } ?>
				<li><a href="dns/domain_check.php" target="mainFrame">�������</a></li>
				<li><a href="dns/change_ip.php" target="mainFrame">��������</a></li>
				<li><a href="dns/query_count_day.php" target="mainFrame">��ѯͳ��</a></li>
				<li><a href="dns/attack_log.php" target="mainFrame">�������</a></li>
				<li><a href="dns/monitor.php" target="mainFrame">�������</a></li>
				<li><a href="dns/monitor_log.php" target="mainFrame">��ؼ�¼</a></li>
				<li><a href="dns/mail_tp.php" target="mainFrame">�ʼ�ģ��</a></li>
				<li><a href="dns/dns_product.php" target="mainFrame">DNS��Ʒ����</a></li>
				<?php if ($wddns_is==1 or $dns_key_num>=3) { ?>
				<li><a href="dns/dns_group.php" target="mainFrame">DNS����</a></li>
				<?php } ?>
          </ul>
		</dd>
</dl>
<dl>
    <dt><a href="###" onclick="showHide('dns2');" target="_self">�������</a></dt>
    <dd id="dns2" style="display:block;">
			<ul>
				<li><a href="admin/amoney.php" target="mainFrame">���ۿ�</a></li>
				<li><a href="admin/pay_log.php" target="mainFrame">֧����¼</a></li>
				<li><a href="admin/buy_log.php" target="mainFrame">�����¼</a></li>
				<li><a href='admin/pay_set.php' target='mainFrame'>֧���ӿ�</a></li>
				<li><a href='memberd/account.php' target='mainFrame'>������Ϣ</a></li>
				<li><a href='memberd/accounts.php' target='mainFrame'>����ͳ��</a></li>
          </ul> 
		</dd>
</dl>
<?php
union_menu();
}elseif ($wdcp_gid==5) {
?>
<dl>
    <dt><a href="###" onclick="showHide('dns1');" target="_self">����DNS����</a></dt>
    <dd id="dns1" style="display:block;">
			<ul>
				<li><a href="dns/domain_add.php" target="mainFrame">��������</a></li>
				<li><a href="dns/domain_list.php" target="mainFrame">�����б�</a></li>
				<li><a href="dns/ptr_list.php" target="mainFrame">PTR�б�</a></li>
				<li><a href="dns/domain_check.php" target="mainFrame">�������</a></li>
				<li><a href="dns/query_count_day_u.php" target="mainFrame">��ѯͳ��</a></li>
				<li><a href="dns/monitor.php" target="mainFrame">崻����</a></li>
				<li><a href="dns/monitor_log.php" target="mainFrame">��ؼ�¼</a></li>
          </ul>
		</dd>
</dl>
<?php
union_menu();
}else{
?>
<dl>
    <dt><a href="###" onclick="showHide('dns1');" target="_self">����DNS����</a></dt>
    <dd id="dns1" style="display:block;">
			<ul>
				<li><a href="dns/domain_add.php" target="mainFrame">��������</a></li>
				<li><a href="dns/domain_list.php" target="mainFrame">�����б�</a></li>
				<li><a href="dns/domain_check.php" target="mainFrame">�������</a></li>
				<li><a href="dns/change_ip.php" target="mainFrame">��������</a></li>
				<li><a href="dns/query_count_day_u.php" target="mainFrame">��ѯͳ��</a></li>
				<li><a href="dns/monitor.php" target="mainFrame">崻����</a></li>
				<li><a href="dns/monitor_log.php" target="mainFrame">��ؼ�¼</a></li>
          </ul>
		</dd>
</dl>
<?php
union_menu();
}
?>