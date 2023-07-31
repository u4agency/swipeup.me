import Hotjar from '@hotjar/browser';

const siteId = 3362111;
const hotjarVersion = 6;

// Initializing with `debug` option:
Hotjar.init(siteId, hotjarVersion, {
    debug: true
});