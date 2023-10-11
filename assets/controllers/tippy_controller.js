import {Controller} from '@hotwired/stimulus';
import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css';

export default class extends Controller {
    static values = {
        text: String,
    };

    connect() {
        tippy(this.element, {
            content: this.textValue,
            placement: 'bottom',
        });
    }
}