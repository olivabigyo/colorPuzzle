
const tiles = document.querySelectorAll('.tile');

let r = 0;
let c = 0;

// Positioning
for (const tile of tiles) {
    tile.style.top = `${r * 125}px`;
    tile.style.left = `${c * 100}px`;
    console.log(r, c);
    c++;
    if (c >= 4) {
        r++; c = 0;
    }
}

// Add Event listeners
// Tiles are selectable
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
