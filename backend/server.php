<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

$request = json_decode(file_get_contents('php://input'));
$size = $request->size;
$pins = $request->pins;

$templates = array(
    '1x6' => array("w" => 1, "h" => 6),
    '2x4' => array("w" => 2, "h" => 4),
    '2x6' => array("w" => 2, "h" => 6),
    '3x4' => array("w" => 3, "h" => 4),
    '3x5' => array("w" => 3, "h" => 5),
    '3x6' => array("w" => 3, "h" => 6),
    '4x5' => array("w" => 4, "h" => 5),
    '5x6' => array("w" => 5, "h" => 6),
    '6x7' => array("w" => 6, "h" => 7),
    '7x8' => array("w" => 7, "h" => 8),
);

$w = $templates[$size]['w'];
$h = $templates[$size]['h'];

class ColorVector
{
    public $hue;
    public $sat;
    public $lit;

    function __construct($h, $s, $l)
    {
        $this->hue = $h;
        $this->sat = $s;
        $this->lit = $l;
    }

    public function __toString()
    {
        return "hsl(" . $this->hue . ", " . $this->sat . "%, " . $this->lit . "%)";
    }

    public static function generateVertex()
    {
        $hue = rand(0, 360);
        $saturation = rand(70, 100);
        $lightness = rand(30, 70);
        return  new ColorVector($hue, $saturation, $lightness);
    }
}


// Static colors - for now
// $colorOrigin = new ColorVector(120, 100, 50);
$colorOrigin = ColorVector::generateVertex();
$colorRowSteps = new ColorVector(rand(-20, 20), -5, -3);
$colorColumnSteps = new ColorVector(rand(-20, 20), -3, 3);

$list = array();

for ($y = 0; $y < $h; $y++) {
    for ($x = 0; $x < $w; $x++) {
        // the right position of the tile
        $tile = array("x" => $x, "y" => $y);
        $color = new ColorVector(
            $colorOrigin->hue + $colorRowSteps->hue * $y + $colorColumnSteps->hue * $x,
            $colorOrigin->sat + $colorRowSteps->sat * $y + $colorColumnSteps->sat * $x,
            $colorOrigin->lit + $colorRowSteps->lit * $y + $colorColumnSteps->lit * $x
        );
        // color of the tile
        // $tile['color'] = "" . $color;  //converting to string
        $tile['color'] = strval($color);  //converting to string too
        $list[] = $tile;
    }
}

// Save the first element
$first = array_shift($list);
// Mark as pinned
$first['pinned'] = true;
// In case of two pins
if ($pins == 2) {
    $last = array_pop($list);
    $last['pinned'] = true;
}
// Randomize the tiles
shuffle($list);
// Put back the first element
array_unshift($list, $first);
if ($pins == 2) {
    array_push($list, $last);
}

$game = array("w" => $w, "h" => $h, "tiles" => $list);

echo json_encode(array(
    "ok" => true,
    "game" => $game
));
