<?php
namespace chart\core;

/**
 * 统计图生成类统一接口。<br />
 * interface for chart make class.
 *
 * @author 	yangjian<yangjian102621@gmail.com>
 * @since 	2013.04.14
 * @version 	1.0
 */
interface IChart {

    /**
     * draw image
     * @return IChart
     */
    public function draw();

	/**
	 * show chart in browser
	 */
	public function showChart();

	/**
  	 * set save chart to file
  	 * @param     string 		$_filename    file for the chart.
  	 * @param     string        $_ext		  extension of image
  	 * @param  	  int  			$_quality  	  quality of image(only for jpeg image)
	 */
	public function saveChart( $_filename, $_ext, $_quality = 75 ); 

}