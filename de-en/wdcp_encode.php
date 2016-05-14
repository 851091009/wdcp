<?php

/*
Author: JoyChou
Date: 2014.12.13 17:11
*/
function wdcp_encode($filename){

    $data = file_get_contents($filename); // ��ȡ�ļ�����

    $nine_bytes = '09574154574443504D';  // wdcp���ܺ��ǰ9���ֽ�����
    $str_nice_bytes =  pack('H*', $nine_bytes);  // 16����ת�ַ���
   
    // �ж��Ƿ���wdcp���ܵģ������ֱ���˳�
    if(strncmp($data, $str_nice_bytes, 9) == 0){
        echo '�ļ�'. $filename. '�Ѿ���wdcp���ܣ������ټ���' . '<br>';
        exit();
    }
    $gz_data = gzcompress($data);   // gzcompress����


    $length  = strlen($gz_data);
    $array_gz_data = unpack('C*', $gz_data);
    $secret = array(0xB8, 0x35, 0x6, 0x2, 0x88, 0x1, 0x5B, 0x7, 0x44, 0x0);

    foreach ($array_gz_data as $key => &$v) {
         
         $v = $secret [ 2 * ($length % 5) ] ^ ~$v;
         $v &=0xff;
         $v = pack('C*', $v);
         
         --$length;

    }

    $result =  join('',$array_gz_data);  //����ת��Ϊ�ַ���
    file_put_contents('encode_' . $filename ,$str_nice_bytes.$result);

}

wdcp_encode('add_user.php');
echo 'Encode Success';


?>