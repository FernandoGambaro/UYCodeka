<?php

class cli {

	public static $pb_length = 50;
	public static $pb_start  = '[';
	public static $pb_end    = ']';
	public static $pb_bg     = ' ';
	public static $pb_fg     = '=';
	public static $pb_head	 = '>';

	/**
	 * Creates an ASCII progress bar, i.e.
	 * [====================== 87% ==============>        ]
	 *
	 * @param integer $step current step of progress bar
	 * @param integer $total total steps in progress bar
   * @param string $decorator a string with the design of the progress bar
	 * @param boolean $return set to TRUE to return output instead of printing
	 *
	 * @return string ASCII progress bar
	 */
	public static function progress_bar($step = 0, $total = 100, $decorator = '[=> ]', $return = FALSE)
	{
		$pb_start = (isset($decorator[0])) ? $decorator[0] : self::$pb_start;
		$pb_fg =    (isset($decorator[0])) ? $decorator[1] : self::$pb_start;
		$pb_head =  (isset($decorator[0])) ? $decorator[2] : self::$pb_start;
		$pb_bg =    (isset($decorator[0])) ? $decorator[3] : self::$pb_start;
		$pb_end =   (isset($decorator[0])) ? $decorator[4] : self::$pb_start;

		// Determine position of progress bar
		$position = floor(($step / $total) * self::$pb_length);

		// Remove 'head' character if progress is >= 100%
		$pb_head = (($step / $total) >= 1) ? $pb_fg : $pb_head;

		// Create progress bar
		$output = $pb_start.str_repeat($pb_fg, $position).$pb_head.
			str_repeat($pb_bg, self::$pb_length - $position).$pb_end."\r";

		// Overlay percent
		$output = substr($output, 0, floor(self::$pb_length / 2) - 2).
			sprintf(' % 3.0f%% ', ($step / $total) * 100).
			substr($output, floor((self::$pb_length / 2) + 4), strlen($output));

		if ($return === TRUE)
			return $output;

		echo $output;
	}

	public static function capture_start()
	{
		ob_start();
	}

	public static function capture_stop()
	{
		$output = ob_get_contents();

		return $output;
	}

	public static function color($text)
  {
    $color = new color($text);

    foreach (array_slice(func_get_args(), 1) as $style)
    {
      $color->$style();
    }

    return $color;
  }

}

class color {

  public $text = '';

  public static $colors = array
  (
    // Styles.
    'bold'      => array('[1m',  '[22m'),
    'italic'    => array('[3m',  '[23m'),
    'underline' => array('[4m',  '[24m'),
    'inverse'   => array('[7m',  '[27m'),

    // Grayscale.
    'white'     => array('[37m', '[39m'),
    'grey'      => array('[90m', '[39m'),
    'black'     => array('[30m', '[39m'),

    // Colors.
    'blue'      => array('[34m', '[39m'),
    'cyan'      => array('[36m', '[39m'),
    'green'     => array('[32m', '[39m'),
    'magenta'   => array('[35m', '[39m'),
    'red'       => array('[31m', '[39m'),
    'yellow'    => array('[33m', '[39m'),
  );

  public static function get($text)
  {
    return new color($text);
  }

  public function __construct($text = '')
  {
    $this->text = $text;
  }

  public function __toString()
  {
    return $this->text;
  }

  public function __call($method, $args)
  {
    if (isset(self::$colors[$method]))
      $this->text = self::style($this->text, $method);

    return $this;
  }

  public static function style($text, $style)
  {
    if (!isset(self::$colors[$style]))
      return $text;

    list($left, $right) = self::$colors[$style];
    return sprintf("%s%s%s", chr(27) . $left, $text, chr(27) . $right);
  }

}
