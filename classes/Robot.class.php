<?php

class Robot
{
    const CMD_PLACE  = 'PLACE';
    const CMD_MOVE   = 'MOVE';
    const CMD_LEFT   = 'LEFT';
    const CMD_RIGHT  = 'RIGHT';
    const CMD_REPORT = 'REPORT';

    const FACE_NORTH = 'NORTH';
    const FACE_EAST  = 'EAST';
    const FACE_SOUTH = 'SOUTH';
    const FACE_WEST  = 'WEST';
    
    const ROTATE_LEFT  = 'LEFT';
    const ROTATE_RIGHT = 'RIGHT';

    protected $board;
    protected $x;
    protected $y;
    protected $face;
    protected $faceMap = [

        self::FACE_NORTH => self::FACE_EAST,
        self::FACE_EAST  => self::FACE_SOUTH,
        self::FACE_SOUTH => self::FACE_WEST,
        self::FACE_WEST  => self::FACE_NORTH
    ];

    public function __construct(Board $board)
    {
        $this->board = $board;
    }

    
    protected function getCommands($separator = null)
    {
        $commands = [
            self::CMD_PLACE,
            self::CMD_MOVE,
            self::CMD_LEFT,
            self::CMD_RIGHT,
            self::CMD_REPORT,
        ];
        return is_null($separator) ? $commands : implode($separator, $commands);
    }

    protected function getFaces($separator = null)
    {
        $faces = [
            self::FACE_NORTH,
            self::FACE_EAST,
            self::FACE_SOUTH,
            self::FACE_WEST,
        ];

        return is_null($separator) ? $faces : implode($separator, $faces);
    }

    protected function getRotations($separator = null)
    {
        $rotations = [
            self::ROTATE_LEFT,
            self::ROTATE_RIGHT,
        ];

        return is_null($separator) ? $rotations : implode($separator, $rotations);
    }
    protected function isAllowedFace($face)
    {
        return in_array($face, $this->getFaces());
    }
    protected function isAllowedRotation($rotation)
    {
        return in_array($rotation, $this->getRotations());
    }
    public function execute($command)
    {
        extract($this->getCommand($command));
        switch ($method) {
            case self::CMD_PLACE:
                $this->placeRobot($x, $y, $face);
                break;

            case self::CMD_MOVE:
                $this->moveRobot();
                break;

            case self::CMD_LEFT:
            case self::CMD_RIGHT:
                $this->rotateRobot($method);
                break;

            case self::CMD_REPORT:
                echo "<br><br> Output:" . $this->report() . PHP_EOL;
                echo "<br><br> by Nisha Dholakia";
                break;
        }
    }
    protected function getCommand($command)
    {
       $cmd1 = strtoupper(trim($command));
       preg_match(
            '/^' .
            '(?P<method>' . $this->getCommands('|') . ')' .
            '(\s' .
                '(?P<x>\d+)\s?,' .
                '(?P<y>\d+)\s?,' .
                '(?P<face>' . $this->getFaces('|') . ')' .
            ')?' .
            '$/',
            $cmd1,
            $args
        );
        
        $method = $args['method'] ?? null;
        $x = $args['x'] ?? 0;
        $y = $args['y'] ?? 0;
        $face = $args['face'] ?? self::FACE_NORTH;
       
        return compact('method', 'x', 'y', 'face');
    }

    public function placeRobot($x, $y, $face)
    {
        if (! $this->board->withinBoardBounds($x, $y)) {
            throw new InvalidArgumentException(sprintf('Coordinates (%d,%d) outside board boundaries.', $x, $y));
        }

        if (! $this->isAllowedFace($face)) {
            throw new InvalidArgumentException(sprintf('Face (%s) is not recognised.', $face));
        }

        $this->x = $x;
        $this->y = $y;
        $this->face = $face;
     }

    public function moveRobot()
    {
        if (! $this->isPlaced()) return;

        $x = $this->x;
        $y = $this->y;

        switch ($this->face) {
            case self::FACE_NORTH:
                $y += 1;
                break;

            case self::FACE_EAST:
                $x += 1;
                break;

            case self::FACE_SOUTH:
                $y -= 1;
                break;

            case self::FACE_WEST:
                $x -= 1;
                break;
        }
      
        if (! $this->board->withinBoardBounds($x, $y)) return;

        $this->x = $x;
        $this->y = $y;

    }

    public function rotateRobot($rotation)
    {
        if (! $this->isPlaced()) return;
        $this->face = $this->resolveFace($rotation);
    }

    public function report()
    {
        if (! $this->isPlaced()) return;
        return sprintf('%d,%d,%s', $this->x, $this->y, $this->face);

    }

    public function isPlaced()
    {
        return (! is_null($this->x) && ! is_null($this->y));
    }

    protected function resolveFace($rotation)
    {
        if (! $this->isAllowedRotation($rotation)) {
            throw new InvalidArgumentException(sprintf('Rotation (%s) is not recognised.', $rotation));
        }

        $clockwise = ($rotation === self::ROTATE_RIGHT);
        $mappings = $clockwise ? $this->faceMap : array_flip($this->faceMap);

        return $mappings[$this->face];
    }
}
