<?php
use chart\ChartFactory;
use chart\core\BrokenChart;
use chart\core\PieChart;

/**
 * Class ${NAME}
 * @author yangjian
 */
require_once("./vendor/autoload.php");

$title = "http://r9it.com 日访问 IP 统计折线图";
$data = array(
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
$config = array(
    'data' 	=> $data,
    'axisy' => $axisy,
    'title' => $title,
    't_fsize' => 18,
    't_font' => 0,
    'bg_size' => array(800, 600)
);

if (isCli()) {
    printf("----------- Try to generate Broken Line Chart... -------------\n");
}
$chart = ChartFactory::create(BrokenChart::class, $config);
if (!isCli()) {
    $chart->showChart();
}
$chart->saveChart("image/brokenchart.png", 'png');
if (isCli()) printf("Done.\n");

if (isCli()) {
    printf("----------- Try to generate Pie Chart... -------------\n");
}
$data = array("百度"=>500,"谷歌"=>1000,"搜狐无线"=>800,"新浪"=>1200,"当当"=>666,
    "淘宝"=>333,"雅虎中国"=>999,"京东"=>500);
$config = array(
    'data' 	=> $data,
    'title' => 'R9IT.COM 搜索引擎数据来源统计图',
    't_fsize' => 18,
    't_font' => 0
);
$chart = ChartFactory::create(PieChart::class, $config);
$chart->saveChart("image/piechart.png", 'png');
if (isCli()) {
    printf("Done.\n");
}
/**
 * check if the scrpit run in a terminal
 * @return bool
 */
function isCli()
{
    $sapi = php_sapi_name();
    return isset($sapi) && substr($sapi, 0, 3) == 'cli';
}