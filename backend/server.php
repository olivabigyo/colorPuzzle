<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

$request = json_decode(file_get_contents('php://input'));
$size = $request->payload;

$templates = array(
    '1x6' => array("w" => 1, "h" => 6),
    '3x4' => array("w" => 3, "h" => 4),
    '3x5' => array("w" => 3, "h" => 5),
    '4x5' => array("w" => 4, "h" => 5),
    '5x5' => array("w" => 5, "h" => 5),
);

$w = $templates[$size]['w'];
$h = $templates[$size]['h'];

// Static colors - for now
$colorOrigin = [120, 100, 50];
$colorRowSteps = [10, -5, -3];
$colorColumnSteps = [-15, -3, 3];

$list = array();

for ($y = 0; $y < $h; $y++) {
    for ($x = 0; $x < $w; $x++) {
        // the right position of the tile
        $tile = array("x" => $x, "y" => $y);
        $color = array(
            $colorOrigin[0] + $colorRowSteps[0] * $y + $colorColumnSteps[0] * $x, //red
            $colorOrigin[1] + $colorRowSteps[1] * $y + $colorColumnSteps[1] * $x, //green
            $colorOrigin[2] + $colorRowSteps[2] * $y + $colorColumnSteps[2] * $x  //blue
        );
        // color of the tile
        $tile['color'] = "hsl(" . $color[0] . ", " . $color[1] . "%, " . $color[2] . "%)";
        $list[] = $tile;
    }
}

// Randomize the tiles
shuffle($list);

$game = array("w" => $w, "h" => $h, "tiles" => $list);

echo json_encode(array(
    "ok" => true,
    "game" => $game
));
