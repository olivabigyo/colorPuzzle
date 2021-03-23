
const tiles = document.querySelectorAll('.tile');

let r = 0;
let c = 0;

// Static coloring
// color of the (0,0) tile
const colorOrigin = [10, 140, 250];
// rgb color steps
const colorRowSteps = [20, 10, -30];
const colorColumnSteps = [15, 25, -20];

// Initialize tiles
for (const tile of tiles) {
    // position of the tile
    tile.style.top = `${r * 125}px`;
    tile.style.left = `${c * 100}px`;
    console.log(r, c);
    // color of the tile
    const color = [
        colorOrigin[0] + colorRowSteps[0] * r + colorColumnSteps[0] * c, //red
        colorOrigin[1] + colorRowSteps[1] * r + colorColumnSteps[1] * c, //green
        colorOrigin[2] + colorRowSteps[2] * r + colorColumnSteps[2] * c  //blue
    ];
    tile.style.backgroundColor = `rgb(${color[0]}, ${color[1]}, ${color[2]})`;

    c++;
    if (c >= 4) {
        r++; c = 0;
    }
}

// Add Event listeners:
// Tiles are selectable and change position
let selected = null;

for (const tile of tiles) {
    tile.addEventListener('click', () => {
        // console.log('clicked');
        if (selected) {
            selected.classList.toggle('selected');
            // clicked tile and selected tile change positions
            const t = tile.style.top;
            tile.style.top = selected.style.top;
            selected.style.top = t;
            const l = tile.style.left;
            tile.style.left = selected.style.left;
            selected.style.left = l;
            // reset selection
            selected = null;
        } else {
            // set selection
            selected = tile;
            tile.classList.toggle('selected');
        }
    });
}
