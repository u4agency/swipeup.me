import {Controller} from "stimulus";

export default class extends Controller {
    static values = {
        formUrl: String,
    };

    connect() {
        this.previousHTML = this.element.innerHTML;

        this.fetchForm();
    }

    async fetchForm() {
        const response = await fetch(this.formUrlValue);

        return await response.text()
    }

    async addSwipe(event) {
        event.preventDefault();

        const response = await this.fetchForm();

        if (response) {
            this.dispatch('swipe:create');
            this.element.innerHTML = response;
        }
    }

    revert() {
        this.element.innerHTML = this.previousHTML;
    }
}