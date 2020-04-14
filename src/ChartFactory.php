<?php
namespace chart;
use chart\core\IChart;
use ReflectionClass;
use ReflectionException;

/**
 * 图表生成工厂类
 * The factory class to make chart
 *
 * @author 	yangjian<yangjian102621@gmail.com>
 * @since  	2013.04.14
 * @version 	1.0
 */
class ChartFactory {
	
	/* instance array of chart class */
	private static $chartPool = array();

    /**
     * create chart instance
     * @param $clazz
     * @param $config
     * @return IChart
     * @throws ReflectionException
     */
	public static function create($clazz, $config )
    {
//		$_className = ucfirst($_key).'Chart';
//		$_DIR = dirname(__FILE__).DIR_OS;
//		$_classFile = $_DIR.'src'.DIR_OS.$_className.'.class.php';
//		include $_DIR.'src'.DIR_OS.'IChart.class.php';
//		include $_classFile;
		//如果该实例已经创建, 则直接返回实例.
		if ( !isset(self::$chartPool[$clazz]) ) {
            $reflect = new ReflectionClass($clazz);
			self::$chartPool[$clazz] = $reflect->newInstance($config);
		}
		return self::$chartPool[$clazz];
	}
	
}