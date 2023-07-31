import {Controller} from "stimulus";

export default class extends Controller {
    static values = {
        formUrl: String,
    };

    connect() {
        this.response = null;

        this.fetchForm();
    }

    async fetchForm() {
        const response = await fetch(this.formUrlValue);

        this.response = await response.text()
    }

    addSwipe(event) {
        event.preventDefault();

        if (this.response) {
            this.element.innerHTML = this.response;
        }
    }
}