<?php

class Board{
	

	public $height;
	public $width;

	public function __construct($h, $w){
		$this->height = $h;
		$this->width = $w;
	}

	public function withinBoardBounds($x, $y)
    {
        return (0 <= $x && $x < $this->width) && (0 <= $y && $y < $this->height);
    }

}