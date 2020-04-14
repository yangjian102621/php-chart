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
     * create single chart instance
     * @param $clazz
     * @param $config
     * @return IChart
     * @throws ReflectionException
     */
	public static function create($clazz, $config )
    {
		if ( !isset(self::$chartPool[$clazz]) ) {
            $reflect = new ReflectionClass($clazz);
			self::$chartPool[$clazz] = $reflect->newInstance($config);
		}
		return self::$chartPool[$clazz];
	}
	
}