
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
for (const tile of tiles) {
    tile.addEventListener('click', () => {
        console.log('clicked');
    });
}
