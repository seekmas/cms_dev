<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/
error_reporting(0);

require_once '../../vendor/upyun/upyun.class.php';
$upyun = new UpYun('site-file-36lean', 'mot', 'wujiayao123');

if (!empty($_FILES)) {

	$upyun = new UpYun('site-file-36lean', 'mot', 'wujiayao123');

	$filename = date('Ymdhis').$_FILES['Filedata']['name'];

	//var_dump( move_uploaded_file( $_FILES['Filedata']['tmp_name'] , './cache/' . $filename) );
	
	$fh = fopen( $_FILES['Filedata']['tmp_name'] , 'rb');

	$rsp = $upyun->writeFile('/'.$_POST['dir'].'/'.$filename , $fh, True);
	
	fclose($fh);
	
}
?>