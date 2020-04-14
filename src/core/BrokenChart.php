<?php
namespace chart\core;

/**
 * 生成折线统计图<br />
 * class to draw broken chart.

 * @author 	yangjian<yangjian102621@gmail.com>
 * @since 	2013.04.14
 * @version 	1.0
 */
class BrokenChart implements IChart
{
	/* resource of Image */
	private $image = NULL;
	/* size of canvas （画布大小） */
	public $bg_size = array(600, 400);
	/* arrow size(坐标轴箭头大小) */
    public $arrow_size = array(5, 5);
	
	/* data of chart */
    public $data;
	/* title of chart */
    public $title;
	
	/* backcground color of canvas */
    public $bg_color = array(255, 255, 0);
	/* color oof title */
    public $t_color  = array(180, 0, 0);
	/* color of string */
    public $str_color = array(0, 0, 255);
	/* color of dot */
    public $dot_color = array(255, 0, 255);
	/* axis color (轴线颜色) */
    public $axis_color = array(0, 0, 0);
	/* 英文字体大小 */
    public $en_fsize = 3;
	/* 坐标轴标尺长度 */
    public $staff_width = 5;
	/* 折线点的半径 */
    public $dot_r = 10;
	
	/* title font */
    public $t_font = 1;
	/* string font */
    public $str_font = 0;
	/* font size of title */
    public $t_fsize = 20;
	/* font size of string */
    public $str_fsize = 11;
	private static $_FONT = array(
		0 => 'hanyi.ttf',
		1 => 'hanyi-xiu-ying.ttf'
	);
    public $font_dir = NULL;
	
	/* 画布左边距 */
    public $margin_left = 50;
	/* 画布右边距 */
    public $margin_right = 20;
	/* 画布上边距 */
    public $margin_top = 10;
	/* 画布下边距 */
    public	$margin_bottom = 50;
	/* Y轴距离顶部的距离 */
    public $axisY_top = 50;
	/* title space to chart (标题和图表的距离) */
    public $t2c_space = 20;
	/* unit of data (计数单位) */
    public $unit = '';
	/* y轴标点 步长 => 点数 */
    public $axisy = array(100, 10);
	
	/* constructor */
	public function __construct( $_config )
    {
		if ( !isset($_config['data']) ) die('chart data should not be empty!');
		$this->data = $_config['data'];
		asort($this->data);
		if ( isset($_config['axisy']) ) $this->axisy = $_config['axisy'];
		if ( isset($_config['title']) ) $this->title = $_config['title'];
		if ( isset($_config['bg_size']) ) $this->bg_size = $_config['bg_size'];
		if ( isset($_config['margin_left']) ) $this->margin_left = $_config['margin_left'];
		if ( isset($_config['margin_right']) ) $this->margin_right = $_config['margin_right'];
		if ( isset($_config['margin_top']) ) $this->margin_left = $_config['margin_top'];
		if ( isset($_config['margin_bottom']) ) $this->margin_bottom = $_config['margin_bottom'];
		if ( isset($_config['bg_color']) ) $this->bg_color = $_config['bg_color'];
		if ( isset($_config['str_color']) ) $this->str_color = $_config['str_color'];
		if ( isset($_config['t_color']) ) $this->t_color = $_config['t_color'];
		if ( isset($_config['dot_color']) ) $this->dot_color = $_config['dot_color'];
		if ( isset($_config['dot_r']) ) $this->dot_r = $_config['dot_r'];
		if ( isset($_config['t_fsize']) ) $this->t_fsize = $_config['t_fsize'];
		if ( isset($_config['str_fsize']) ) $this->str_fsize = $_config['str_fsize'];
		if ( isset($_config['unit']) ) $this->unit = $_config['unit'];
		if ( isset($_config['t_font']) ) $this->t_font = $_config['t_font'];
		if ( isset($_config['str_font']) ) $this->str_font = $_config['str_font'];
		if ( isset($_config['arrow_size']) ) $this->arrow_size = $_config['arrow_size'];
		if ( isset($_config['staff_width']) ) $this->staff_width = $_config['staff_width'];
		if ( isset($_config['axis_color']) ) $this->axis_color = $_config['axis_color'];
		if ( isset($_config['en_fsize']) ) $this->en_fsize = $_config['en_fsize'];
		if ( isset($_config['t2c_space']) ) $this->t2c_space = $_config['t2c_space'];

		$this->font_dir = dirname(__DIR__).'/fonts/';
	}

    /**
     * draw image
     */
	public function draw()
    {
        $this->image = $this->getImageCanvas($this->bg_size, $this->bg_color);
        $this->drawTitle();
        $this->drawBrokenLine();
        return $this;
    }

    /**
     * create an truecolor canvas(创建真彩色画布)
     * @param array $_size
     * @param array $_bg_color
     * @return false|resource
     */
	private function &getImageCanvas( $_size, $_bg_color = array(255, 255, 255) )
    {
		$_img =  imagecreatetruecolor($_size[0], $_size[1]);
		$_color = imagecolorallocate($_img, $_bg_color[0], $_bg_color[1], $_bg_color[2]);
		imagefill($_img, 0, 0, $_color);
		return $_img;
	}

	
	/**
	 * draw the title of the image
	 */
	private function drawTitle()
    {
		$_color = imagecolorallocate($this->image, $this->t_color[0], $this->t_color[1], $this->t_color[2]);
		$_font = $this->font_dir.self::$_FONT[$this->t_font];
		$_ttf_box = imagettfbbox($this->t_fsize, 0, $_font, $this->title );
		$_x = ($this->bg_size[0] - ($_ttf_box[2] - $_ttf_box[0]))/2;
		$_y = $this->margin_top + abs($_ttf_box[7]);
		imagettftext($this->image, $this->t_fsize, 0, $_x, $_y, $_color, $_font, $this->title);
		$this->axisY_top = $_y + $this->t2c_space;
	}
	
	/**
	 * draw the broken line
	 * */
	public function drawBrokenLine()
    {
		
		if( count($this->data) > 0 ) {
			$_color_axis = imagecolorallocate($this->image, $this->axis_color[0], $this->axis_color[2], $this->axis_color[2]);
			$_color_str = imagecolorallocate($this->image, $this->str_color[0], $this->str_color[1], $this->str_color[2]);
			$_color_dot = imagecolorallocate($this->image, $this->dot_color[0], $this->dot_color[1], $this->dot_color[2]);
			$_font = $this->font_dir.self::$_FONT[$this->str_font];

			// 绘制Y坐标轴
			imageline($this->image, $this->margin_left, $this->axisY_top, $this->margin_left, $this->bg_size[0], $_color_axis);
			// 绘制箭头
			imageline($this->image,
                $this->margin_left - $this->arrow_size[0],
                $this->axisY_top + $this->arrow_size[1],
                $this->margin_left, $this->axisY_top, $_color_axis);
			imageline($this->image,
                $this->margin_left + $this->arrow_size[0],
                $this->axisY_top + $this->arrow_size[1],
                $this->margin_left, $this->axisY_top, $_color_axis);
			
			// 绘制单位
			imagettftext($this->image,
                $this->str_fsize, 0,
                $this->margin_left + $this->arrow_size[0],
                $this->axisY_top, $_color_axis,
                $_font, '单位:'.$this->unit);
			
			// 绘制X坐标轴
			$_x_width = $this->bg_size[0] - $this->margin_right;
			imageline($this->image, 0,
                $this->bg_size[1] - $this->margin_bottom,
                $_x_width, $this->bg_size[1] - $this->margin_bottom, $_color_axis);
			// 绘制箭头
			imageline($this->image,
                $_x_width - $this->arrow_size[0],
                ($this->bg_size[1] - $this->margin_bottom - $this->arrow_size[1]),
                $_x_width,
                $this->bg_size[1] - $this->margin_bottom,
                $_color_axis);
			imageline($this->image,
                $_x_width - $this->arrow_size[0],
                ($this->bg_size[1] - $this->margin_bottom + $this->arrow_size[1]),
                $_x_width,
                $this->bg_size[1] - $this->margin_bottom,
                $_color_axis);
			
			//确定坐标原点
			$_x_0 = $this->margin_left;
			$_y_0 = $this->bg_size[1] - $this->margin_bottom;
			$_space = 10; 
			
			$_step_x = intval( ($_x_width-$_x_0 - $this->arrow_size[0]) /count($this->data) );
			$_step_y = intval( ($_y_0 -$this->axisY_top - $this->arrow_size[1] ) /$this->axisy[1] );		//坐标步长
			$_axis_h = $_step_y * $this->axisy[1];			//Y坐标轴的高度
			$_axis_max = $this->axisy[0] * $this->axisy[1];		//Y轴的最大值

			//给Y轴标点
			for ( $j = 1; $j <= $this->axisy[1]; ++$j ) {
				imageline($this->image, $_x_0,  $_y_0 - $_step_y * $j, $_x_0 - $this->staff_width, $_y_0 - $_step_y * $j, $_color_axis);
				//绘制数值
				imagestring ( $this->image , $this->en_fsize , $_x_0+$_space , $_y_0 - $_step_y * $j - imagefontheight($this->en_fsize)/2 , $this->axisy[0]*$j , $_color_str );
			}

			$_point = array();		//存储折线点

			$i = 1;
			foreach ( $this->data as $_key => $_val ) {
				//给X轴标点
				imageline($this->image, $_x_0 + $_step_x * $i, $_y_0 - $this->staff_width, $_x_0 + $_step_x*$i, $_y_0, $_color_axis);
		
				//绘制横坐标标度
				$_ttf_box = imagettfbbox($this->str_fsize, 0, $_font, $_key);
				$_x = $_x_0 + $_step_x * $i - ($_ttf_box[2] - $_ttf_box[0])/2;
				$_y = $_y_0 + abs($_ttf_box[7]) + $_space;
				imagettftext($this->image, $this->str_fsize, 0, $_x, $_y, $_color_str, $_font, $_key);

				//绘制折线
				$x1 = $_x_0 + $_step_x * $i;
				$y1 = $_y_0 - $_val/$_axis_max * $_axis_h;
				$_point[$i] = array($x1, $y1);		//存储所有的数据点
				if ( $i > 1 ) {
					imageline($this->image, $_point[$i-1][0], $_point[$i-1][1], $_point[$i][0], $_point[$i][1], $_color_dot);
				}
				//绘制数据点
				imagefilledellipse ( $this->image , $x1 , $y1 , $this->dot_r , $this->dot_r , $_color_dot );
				//绘制数据值
				imagestring($this->image, $this->str_fsize, $x1-($_ttf_box[2]-$_ttf_box[0])/2, $y1-abs($_ttf_box[7])-$this->dot_r, $_val, $_color_str);
				++$i;
			}
			unset($_point);
			
		}
	}

	/**
	 * show chart in browser
	 */
	public function showChart()
    {
        if ($this->image == null) {
            $this->draw();
        }
        header('Content-Type: image/jpeg');
        imagepng($this->image);
	}

	/**
  	 * set save chart to file
  	 * @param     string 		$_filename    file for the chart.
  	 * @param     string        $_ext		  extension of image
  	 * @param  	  int  			$_quality  	  quality of image(only for jpeg image)
  	 * @return 	  boolean    
	 */
	public function saveChart( $_filename, $_ext, $_quality = 75 )
    {
        if ($this->image == null) {
            $this->draw();
        }
		$_ext = strtolower($_ext);
		$_res = false;
        switch ( $_ext ) {
            case 'jpg':
                $_res = imagejpeg($this->image, $_filename, $_quality);
                break;

            case 'gif':
                $_res = imagegif($this->image, $_filename);
                break;

            case 'png':
                $_res = imagepng($this->image, $_filename);
                break;
        }
		return $_res;
	}
	
	public function __destruct()
    {
		if ($this->image != null) {
            imagedestroy($this->image);
        }
	}


}