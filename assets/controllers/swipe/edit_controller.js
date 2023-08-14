import {Controller} from '@hotwired/stimulus';
import loading from "../../utils/loading";

export default class extends Controller {
    static values = {
        url: String,
    };

    connect() {
        this.previousHTML = this.element.innerHTML;
    }

    async getEditForm(event) {
        event.preventDefault();

        event.currentTarget.disabled = true;
        const buttonHTML = event.currentTarget.innerHTML;
        event.currentTarget.innerHTML = loading;

        const response = await fetch(this.urlValue);

        if (response.status === 200) {
            this.dispatch('swipe:edit');
            this.element.innerHTML = await response.text();
        } else {
            event.currentTarget.innerHTML = buttonHTML;
            event.currentTarget.disabled = false;
        }
    }

    revert() {
        this.element.innerHTML = this.previousHTML;
    }
}
