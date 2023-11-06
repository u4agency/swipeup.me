import { gtag, install } from 'ga-gtag';

export default function ga(id) {
    install(id);

    gtag('js', new Date());
    gtag('config', id);
}