import {Controller} from '@hotwired/stimulus';
import loading from "../../utils/loading";

export default class extends Controller {
    static values = {
        url: String,
    };

    async getSwipes(event) {
        event.preventDefault();

        this.element.innerHTML = loading;

        const response = await fetch(this.urlValue);

        this.element.innerHTML = await response.text();
    }
}
