<?php
header('Content-Type: application/json');

// One size fits all - for now
$w = 4;
$h = 5;
// Static colors - for now
$colorOrigin = [10, 140, 250];
$colorRowSteps = [20, 10, -30];
$colorColumnSteps = [15, 25, -20];

$list = array();

for ($x = 0; $x < $w; $x++) {
    for ($y = 0; $y < $h; $y++) {
        // the right position of the tile
        $tile = array("x" => $x, "y" => $y);
        $color = array(
            $colorOrigin[0] + $colorRowSteps[0] * $y + $colorColumnSteps[0] * $x, //red
            $colorOrigin[1] + $colorRowSteps[1] * $y + $colorColumnSteps[1] * $x, //green
            $colorOrigin[2] + $colorRowSteps[2] * $y + $colorColumnSteps[2] * $x  //blue
        );
        // color of the tile
        $tile['color'] = "rgb(" . $color[0] . ", " . $color[1] . ", " . $color[2] . ")";
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
