'use strict';

const playground = document.getElementById('canvas');

const apiEndpoint = 'http://localhost/colorPuzzle/backend/server.php';

async function getGame() {
    try {
        const response = await fetch(apiEndpoint);

        if (!response.ok) {
            console.log(`Fetch returned with: ${response.status} (${response.statusText})`);
            return;
        }

        const data = await response.json();

        if (!data.game) {
            console.log('Response contains no game field');
            return;
        }
        console.log(data.game);
        initGame(data.game);

    } catch (exception) {
        console.log('Error: ' + exception);
    }
}

getGame();

function initGame(game) {
    const tilewidth = 400 / game.w;
    const tileheight = 500 / game.h;

    // Creating DOM elements for game object
    for (const tile of game.tiles) {
        const elem = document.createElement('div');
        elem.classList.add('tile');
        elem.style.width = `${tilewidth}px`;
        elem.style.height = `${tileheight}px`;
        elem.style.backgroundColor = tile.color;
        tile.elem = elem;
        playground.appendChild(elem);
    }

    for (let r = 0; r < game.h; r++) {
        for (let c = 0; c < game.w; c++) {
            const tile = game.tiles[r * game.w + c];
            tile.cx = c;
            tile.cy = r;
            tile.elem.style.top = `${r * tileheight}px`;
            tile.elem.style.left = `${c * tilewidth}px`;
        }
    }

    // Add Event listeners:
    // Tiles are selectable and change position
    let selected = null;

    for (const tile of game.tiles) {
        tile.elem.addEventListener('click', () => {
            // console.log('clicked');
            if (selected) {
                selected.elem.classList.toggle('selected');
                // clicked tile and selected tile change positions
                swapTiles(selected, tile);
                // reset selection
                selected = null;
                if (isWin(game)) {
                    console.log("You won");
                    setTimeout(() => { alert('Hurray! You won') }, 500);
                } else {
                    console.log("Not yet");
                }
            } else {
                // set selection
                selected = tile;
                tile.elem.classList.toggle('selected');
            }
        });
    }
}

function swap(a, b, ...keys) {
    for (const key of keys) {
        const x = a[key];
        a[key] = b[key];
        b[key] = x;
    }
}

function swapTiles(selected, tile) {
    swap(selected.elem.style, tile.elem.style, 'top', 'left');
    swap(selected, tile, 'cx', 'cy');
}

function isWin(game) {
    for (const tile of game.tiles) {
        if (tile.cx != tile.x || tile.cy != tile.y) {
            return false;
        }
    }
    return true;
}
