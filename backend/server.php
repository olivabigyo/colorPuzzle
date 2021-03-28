<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

$request = json_decode(file_get_contents('php://input'));
$size = $request->size;
$pins = $request->pins;

$template_size = array(
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

$w = $template_size[$size]['w'];
$h = $template_size[$size]['h'];

$template_pin = array(
    '1' => array("fromfirstrow" => 1, "fromlastrow" => 0),
    '2' => array("fromfirstrow" => 1, "fromlastrow" => 1),
    '3' => array("fromfirstrow" => $w, "fromlastrow" => $w),
);

$frow = $template_pin[$pins]['fromfirstrow'];
$lrow = $template_pin[$pins]['fromlastrow'];

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

// Randomized colors
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

// Save the elements to be marked as pinned
// Remove the first or first row of elements and mark
for ($x = 0; $x < $frow; $x++) {
    $firstRow[] = array_shift($list);
    $firstRow[$x]['pinned'] = true;
}

// Remove the last or last row of elements and mark
for ($x = 0; $x < $lrow; $x++) {
    $lastRow[] = array_pop($list);
    $lastRow[$x]['pinned'] = true;
}

// Randomize the rest of tiles
shuffle($list);

// Put back the saved and marked elements
array_unshift($list, ...$firstRow);
$lastRowOK = array_reverse($lastRow);
array_push($list, ...$lastRowOK);

// create game object
$game = array("w" => $w, "h" => $h, "tiles" => $list);

// create response json
echo json_encode(array(
    "ok" => true,
    "game" => $game
));
