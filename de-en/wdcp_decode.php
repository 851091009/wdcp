<?php
/*
Authou: JoyChou
Date: 2014��12��7��14:50
All of wdcp version
*/
error_reporting(0);

function wdcp_decode($filename) {

    if (!file_exists($filename)) {
        echo '�ļ���������';
        exit(); //�ļ���������
    }
        
    $data = unpack('C*', substr(file_get_contents($filename), 9)) ;  //�ӵ�10���ַ�����ʼ����
    $nine_bytes = '09574154574443504D';  // wdcp���ܺ��ǰ9���ֽ�����
    $str_nice_bytes =  pack('H*', $nine_bytes);  // 16����ת�ַ���
   
    // �ж��Ƿ���wdcp���ܵģ��������ֱ���˳�
    if(strncmp(file_get_contents($filename), $str_nice_bytes, 9) != 0){
        echo '�ļ�'. $filename. '����wdcp����' . '<br>';
        // exit();
    }
    $key = array(0xB8, 0x35, 0x6, 0x2, 0x88, 0x1, 0x5B, 0x7, 0x44, 0x0);
    $j = count($data);
    foreach($data as $k => &$v) {
            $v = $key [ 2 * ($j % 5) ] ^ ~$v;
            // $v = sprintf('%u', $v);
            $v &= 0xFF;  //��ʱ$v��int����
          
            $v = pack('C*', $v); //pack������string�ַ���
            
            -- $j;
    }
    return gzuncompress(join('', $data));  // join ��һ��һά�����ֵת��Ϊ�ַ���
}


function Traversal_Files($path = '.'){

    if (!is_dir($path)) {
        echo '��������Ŀ¼';
        exit();
    }
    // //opendir()����һ��Ŀ¼���,ʧ�ܷ���false
    if ($current_dir = opendir($path)) {
        while (false !== ($file = readdir($current_dir))) { // readdir��ȡ��ǰĿ¼���ļ�����Ŀ¼���Լ�. ..
            $sub_dir = $path . DIRECTORY_SEPARATOR . $file;  // DIRECTORY_SEPARATOR \����/
            
            if ($file == '.' || $file == '..') {
                continue;
            }
            else if (is_dir($sub_dir)) {
                echo "<h3> Directory Name  $file </h3>";
                Traversal_Files($sub_dir); //�����Ŀ¼�����еݹ��жϡ�
            }
            else{
                 $file_ext = substr($file, strrpos($file,".")+1);

                 if ($file_ext == 'php' || $file_ext == 'PHP') {
                    
                    file_put_contents($sub_dir . '_decode', wdcp_decode($sub_dir));
                    unlink($sub_dir); 
                    rename($sub_dir . '_decode', $sub_dir);
                 }
            }
        }
    }

    closedir($current_dir); 
}


// �����޸�wdcpĿ¼����
Traversal_Files("D:\wdcp\mysql");

?>