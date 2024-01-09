import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static values = {
        link: {
            type: String,
            default: '',
        },
    };

    connect() {
        this.fetch();
    }

    async fetch() {
        const response = await fetch(this.linkValue);
        this.element.innerHTML = await response.text();
    }
}