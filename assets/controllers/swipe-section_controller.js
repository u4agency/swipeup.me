import {Controller} from "stimulus";
import loading from "../utils/loading";

export default class extends Controller {
    static values = {
        formUrl: String,
        handle: Boolean,
    };

    connect() {
        this.previousHTML = this.element.innerHTML;

        this.fetchForm();

        if (this.handleValue) {
            this.addSwipe();
        }
    }

    async fetchForm() {
        const response = await fetch(this.formUrlValue);

        return await response.text()
    }

    buttonClick(event) {
        event.preventDefault();
        this.addSwipe();
    }

    async addSwipe() {
        this.element.getElementsByTagName('button')[0].disabled = true;
        this.element.getElementsByTagName('button')[0].innerHTML += loading;

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