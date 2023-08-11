import {Controller} from '@hotwired/stimulus';
import loading from "../../utils/loading";

export default class extends Controller {
    static values = {
        url: String,
    };

    connect() {
        console.log("Edit connected!");
    }

    async getEditForm(event) {
        event.preventDefault();

        const response = await fetch(this.urlValue);

        this.element.innerHTML = await response.text();
    }
}
