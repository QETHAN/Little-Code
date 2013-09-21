<?php
error_reporting(0);
header("Content-Type:application/json");
define('CODEBAK','codebak');//代码存储文件夹
$optype=trim($_GET['type']);
$key=trim($_GET['key']);
$num=trim($_GET['num']);

if (get_magic_quotes_gpc()){
	$content=stripslashes(trim($_POST['code']));
}else{
	$content=trim($_POST['code']);
}
switch($optype){
	case 'save':if(empty($content))die('No request code for nothing\'s been posted!');savecode($content);break;
	case 'get':showcode($key);break;
	case 'list':showlist($num);break;
	default:die('Illegal operations!');
}
function savecode($content){
	if(!(file_exists(CODEBAK)&&is_dir(CODEBAK))){
		mkdir(CODEBAK,0777);
	}
	$timestamp=time();
	$filename=CODEBAK.'/'.$timestamp.'.txt';
	file_put_contents($filename,$content);
	echo '{"ret":0,"status":"ok","id":"'.$timestamp.'"}';
}
function showcode($key){
	$filename=CODEBAK.'/'.$key.'.txt';
	if(file_exists($filename)){
		echo '{"ret":0,"status":"ok","code":"'.file_get_contents($filename).'"}';
	}else{
		die('404');
	}
}
?>
