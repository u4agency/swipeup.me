import './styles/swipe.css';
import 'animate.css';
import fullpage from 'fullpage.js' ;

new fullpage('#fullpage', {
    //options here
    autoScrolling:true,
    scrollHorizontally: true
});

let palette = require('image-palette');
let pixels = require('image-pixels');

async function ipalette() {
    let {colors} = palette(await pixels(require(`./images/wyssual_logo.png`)), 3);

    let dom = document.getElementById('spotlight');
    dom.style.backgroundImage = `linear-gradient(45deg, rgba(${colors[0][0]},${colors[0][1]},${colors[0][2]},${colors[0][3]}), rgba(${colors[1][0]},${colors[1][1]},${colors[1][2]},${colors[1][3]}) 50%, rgba(${colors[2][0]},${colors[2][1]},${colors[2][2]},${colors[2][3]}))`;
    // dom.style.backgroundImage = "linear-gradient(45deg, #00dc82, #36e4da 50%, #0047e1)";
    return colors;
}

ipalette()