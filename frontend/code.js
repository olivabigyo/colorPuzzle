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
                swap(selected, tile);
                // reset selection
                selected = null;
            } else {
                // set selection
                selected = tile;
                tile.elem.classList.toggle('selected');
            }
        });
    }
}

function swap(selected, tile) {
    const t = tile.elem.style.top;
    tile.elem.style.top = selected.elem.style.top;
    selected.elem.style.top = t;
    const l = tile.elem.style.left;
    tile.elem.style.left = selected.elem.style.left;
    selected.elem.style.left = l;
}
