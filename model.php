<?php
/**
 * con [连接数据库]
 * [字符串] $name [数据库服务器登录用户名]
 * [字符串] $psd [数据库服务器登录密码]
 * [字符串] $db [数据库名]
 * [字符串] $host [数据库服务器地址]
 * [字符串] $code [数据传输编码]
 */
function con($name,$psd,$db,$host='localhost',$code='utf8'){
  $_SESSION['mysqli']=mysqli_connect($host,$name,$psd,$db) or die('连接数据库失败'); //连接数据库服务器并选择数据库
  mysqli_query($_SESSION['mysqli'],'set names '.$code); //设置数据传输编码
}
function getList($table,$where='',$order='id desc',$limit='',$field='*'){
	$sql="select $field from $table";
	if($where!=''){
		$sql .= " where $where";
	}
	if($order!=''){
		$sql .= " order by $order";
	}
	if($limit!=''){
		$sql .= " limit $limit";
	}
	$res = mysqli_query($_SESSION['mysqli'],$sql);
	// var_dump($res);
	if(!$res||$res->num_rows==0){
		return false;
		// exit;
	}
	while ($row = mysqli_fetch_assoc($res)) {
		$data[] = $row;
	}
	// var_dump(mysqli_fetch_assoc($res));
	return $data;
}

/**
 * getOne [获取一条数据]
 * [字符串] $table [表名]
 * [字符串] $where [条件]
 * [字符串] $field [要查询的字段名]
 */
function getOne($table,$where,$field='*'){
	$sql="select $field from $table where $where"; //组装sql语句
	$result=mysqli_query($_SESSION['mysqli'],$sql); //执行sql,返回值为false或对象
	if(!$result||$result->num_rows==0){ //如果错误或者没找到数据
		return false; //返回假并跳出函数
	}
	return mysqli_fetch_assoc($result); //如果正确找到数据返回解析后的结果
}
/**
 * add [添加数据]
 * [字符串] $table [表名]
 * [数组] $data [数组的键对应数据表的字段名,数组的值对应字段的值]
 */
function add($table,$data){
	$feild=''; //声明保存要添加内容的字段的字符串变量
	$values=''; //声明保存对应字段的添加的内容的字符串变量
	if(!is_array($data)) //如果传的参数不是数组
	{
		return false; //返回假并跳出函数
	}
	foreach ($data as $key => $value) { //遍历数组参数
		$feild.=$key.','; //将键拼接上逗号后拼接进字段字符串
		$values.="'".$value."',"; //将值拼接上逗号后拼接进内容字符串
	}
	$feild=rtrim($feild,','); //调用函数rtrim去掉字符串右边的逗号(,)
	$values=rtrim($values,','); //调用函数rtrim去掉字符串右边的逗号(,)
	$sql="insert into $table ($feild) values ($values)"; //组装sql语句
	$result=mysqli_query($_SESSION['mysqli'],$sql); //执行sql,返回值为false或受影响的条数
	if($result) //如果成功
	{
		return mysqli_insert_id($_SESSION['mysqli']); //获取新增的那一条数据的id
	}
	else //如果失败
	{
		return false; //返回假并跳出函数
	}
}


/**
 * edit [编辑数据]
 * [字符串] $table [表名]
 * [数组] $data [数组的键对应数据表的字段名,数组的值对应字段的值]
 * [字符串] $where [条件]
 */
function edit($table,$data,$where){
	$set=''; //声明字符串变量
	if(!is_array($data)) //如果传的参数不是数组
	{
		return false; //返回假并跳出函数
	}
	foreach ($data as $key => $value) { //遍历数组参数
		$set.=$key."='".$value."',"; //将键拼接上等于号再拼接上值(值要加引号,数字可以不加)后再拼接逗号后拼接进字段字符串
	}
	$set=rtrim($set,','); //调用函数rtrim去掉字符串右边的逗号(,)
	$sql="update $table set $set where $where"; //组装sql语句
	return mysqli_query($_SESSION['mysqli'],$sql); //执行sql,返回值为false或受影响的条数
}
/****************封装******************/
/*
add 	添加数据
[字符串]	$table [表名]
[数组]		$data [键值对 相对应]
*/
// $data['name'] = $_POST['title'];
// function add($table,$data){
// 	$feild=''; //声明保存要添加内容的字段的字符串变量
// 	$values=''; //声明保存对应字段的添加的内容的字符串变量
// 	if(!is_array($data)) //如果传的参数不是数组
// 	{
// 		return false; //返回假并跳出函数
// 	}
// 	foreach ($data as $key => $value) { //遍历数组参数
// 		$feild.=$key.','; //将键拼接上逗号后拼接进字段字符串
// 		$values.="'".$value."',"; //将值拼接上逗号后拼接进内容字符串
// 	}
// 	$feild=rtrim($feild,','); //调用函数rtrim去掉字符串右边的逗号(,)
// 	$values=rtrim($values,','); //调用函数rtrim去掉字符串右边的逗号(,)
// 	$sql="insert into $table ($feild) values ($values)"; //组装sql语句
// 	// var_dump($sql);
// 	$result=mysqli_query($_SESSION['mysqli'],$sql); //执行sql,返回值为false或受影响的条数
// 	// var_dump($result);
// 	if($result) //如果成功
// 	{
// 		return mysqli_insert_id($_SESSION['mysqli']); //获取新增的那一条数据的id
// 	}
// 	else //如果失败
// 	{
// 		return false; //返回假并跳出函数
// 	}
// }

/**
 * [uploadImg 上传图片]
 * @param  [字符串] $name     [file类型的input标签name值]
 * @param  [自然数] $size     [允许上传的图片最大大小]
 * @param  [数组]  $type     [允许上传的图片类型]
 * @param  [字符串] $rootPath [图片保存根目录]
 * @return [字符串]           [图片保存路径]
$_FILES['myFile']['name'] 客户端文件的原名称。
$_FILES['myFile']['type'] 文件的 MIME 类型，需要浏览器提供该信息的支持，例如"image/gif"。
$_FILES['myFile']['size'] 已上传文件的大小，单位为字节。
$_FILES['myFile']['tmp_name'] 文件被上传后在服务端储存的临时文件名，一般是系统默认。
 */
function uploadImg($name,$size="2*1024*1024",$type=array('image/png','image/jpeg','image/gif','image/jpg'),$rootPath='uploads/'){
	$mes=''; //声明变量保存错误提示信息
	switch ($_FILES[$name]['error']) //根据错误号赋值错误提示信息
	{
		case '1':
			$mes='图片大小超过了php配置的图片限制';
			break;
		case '2':
			$mes='图片大小超过了浏览器限制';
			break;
		case '3':
			$mes='图片部分被上传';
			break;
		case '4':
			$mes='没有找到要上传的图片';
			break;
		case '5':
			$mes='服务器临时图片夹丢失';
			break;
		case '6':
			$mes='图片写入服务器临时图片夹出错';
			break;
	}

	if(!getimagesize($_FILES[$name]['tmp_name']))//如果用函数getimagesize获取不到图片的信息,表示不是真正的图片文件
	{
		$mes='不是真正的图片';
	}
	//$size=2*1024*1024;//设定允许上传文件的最大大小
	// if($_FILES[$name]['size']>$size)//如果上传文件的大小操作设定值
	// {
	// 	$mes='图片大小超过了网站的限定值';
	// }
	//$type=array('image/png','image/jpeg','image/gif');//设定允许上传的文件类型
	if(!in_array($_FILES[$name]['type'],$type))//如果上传的文件类型不在允许的类型数组中
	{
		$mes='图片类型不允许2';
	}
	if($mes!='') //如果错误提示信息不为空
	{
		echo "<script>alert('".$mes."');history.go(-1);</script>";//给对应错误提示并返回上一页
		exit;
	}
	//获取文件后缀名
	$fileArr=explode('.',$_FILES[$name]['name']);//用函数explode将字符串通过符号'.'分割成数组
	$maxIndex=count($fileArr)-1;//计算数组的最大索引
	$extension=$fileArr[$maxIndex];//获取文件的扩展名
	//获得新文件路径
	//$rootPath='view/uploads/';//设定文件上传根目录
	if(!file_exists($rootPath)) //如果文件(夹)不存在
	{	//mkdir(path,mode,recursive,context)
		//path	必需。规定要创建的目录的名称。
		//mode	必需。规定权限。默认是 0777。
		//recursive	必需。规定是否设置递归模式。
		//context	必需。规定文件句柄的环境。Context 是可修改流的行为的一套选项。
		mkdir($rootPath,0777); //创建对应文件(夹),并设置权限为全部可读可写可操作
	}
	$file=$rootPath.time().rand(100000,999999).'.'.$extension;//生成新文件保存的路径
	$mes=move_uploaded_file($_FILES[$name]['tmp_name'],$file);//将临时文件移动到指定路径保存
	if($mes)//如果保存成功
	{
		return $file;//将文件路径返回
	}
	else//如果保存不成功
	{
		echo "<script>alert('图片上传失败');history.go(-1);</script>";//给错误提示并返回上一页
		exit;
	}
}


?>
