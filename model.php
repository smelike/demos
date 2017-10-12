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
  $_SESSION['mysqli']=mysqli_connect($host,$name,$psd,$db) or die('连接数据库失败'); 
  mysqli_query($_SESSION['mysqli'],'set names '.$code);
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
	if(!$res||$res->num_rows==0){
		return false;
	}
	while ($row = mysqli_fetch_assoc($res)) {
		$data[] = $row;
	}
	return $data;
}
