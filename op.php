<?php
/*header('content-type','charset=utf-8');*/
define(CODEBAK,'codebak');//代码存储文件夹
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
	echo '文件保存成功！请求码:<em>'.$timestamp.'</em>-><a href="javascript:;" id="copyobj">复制URL</a>';
}
function showcode($key){
	$filename=CODEBAK.'/'.$key.'.txt';
	if(file_exists($filename)){
		echo file_get_contents($filename);
	}else{
		die('404');
	}
}
function showlist($num){
	if(!(file_exists(CODEBAK)&&is_dir(CODEBAK))){
		mkdir(CODEBAK,0777);
	}
	if(empty($num))$num=10;
	if(is_numeric($num)){
		$files=scandir(CODEBAK);//scandir(dirname(__FILE__));
		$count=count($files);//print_r($files);
		for($i=0;$i<$num;$i++){
			$currIndex=$count-1-$i;
			if(1){
				echo "<li><a href=\"javascript:getFromUrl(".str_replace('.txt','',$files[$currIndex]).")\">".str_replace('.txt','',$files[$currIndex])."</a></li>";
			}
		}
	}
}
?>
