import {Controller} from "stimulus";

export default class extends Controller {
    static values = {
        formUrl: String,
    };

    connect() {
        this.previousHTML = this.element.innerHTML;
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
            this.dispatch('swipe:create');
            this.element.innerHTML = this.response;
        }
    }
    revert() {
        this.element.innerHTML = this.previousHTML;
    }
}