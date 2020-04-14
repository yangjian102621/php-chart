<?php
define("ROOT", dirname(__FILE__));	//系统根目录
define("DIR_OS", DIRECTORY_SEPARATOR);	//目录分隔符
include ROOT.DIR_OS.'ChartFactory.class.php';
$data_arr = array("百度"=>500,"谷歌"=>1000,"搜狐无线"=>800,"新浪"=>1200,"当当"=>666,
			"淘宝"=>333,"雅虎中国"=>999,"京东"=>500);
$title = '网络星空搜索引擎数据来源统计图';
$_config = array(
	'data' 	=> $data_arr,
	'title' => $title,
	't_fsize' => 18,
	't_font' => 0
);
//$_chart = ChartFactory::create('pie', $_config);
//$_chart->showChart();
//$_res = $_chart->saveChart(ROOT.DIR_OS."piechart.png", 'png');

$_title = "网络星空历年年招生统计图";
$_data_1 = array(
	'2005'  => '60',
	'2006' 	=> '90',
	'2007'  => '120',
	'2008'  => '160',
	'2009'  => '240',
	'2010'  => '300',
	'2011'  => '320',
	'2012'  => '400',
	'2013'	=> '540'
);
$axisy = array(100, 6);			//Y坐标抽参数 步长 => 点数
$_config_1 = array(
	'data' 	=> $_data_1,
	'axisy' => $axisy,
	'title' => $_title,
	't_fsize' => 18,
	't_font' => 0,
	'bg_size' => array(800, 600)
);
//$_chart = ChartFactory::create('broken', $_config_1);
//$_chart->showChart();
//$_res = $_chart->saveChart(ROOT.DIR_OS."brokenchart.png", 'png');

$_chart = ChartFactory::create('square', $_config_1);
$sapi_type = php_sapi_name();
if (isset($sapi_type) && substr($sapi_type, 0, 3) == 'cli') {
    return true;
} else {
    return false;
}
//$_chart->showChart();
$_res = $_chart->saveChart(ROOT.DIR_OS."squarechart2.png", 'png');

/**
 * check if the scrpit run in a terminal
 * @return bool
 */
function isCli()
{
    $sapi = php_sapi_name();
    return isset($sapi) && substr($sapi, 0, 3) == 'cli';
}

