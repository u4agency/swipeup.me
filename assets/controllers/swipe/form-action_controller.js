import {Controller} from '@hotwired/stimulus';
import notyf from "../../utils/notyf";
import loading from "../../utils/loading";

export default class extends Controller {
    static targets = ["button"];

    async submitForm(event) {
        event.preventDefault();

        this.buttonTarget.disabled = true;
        const innerButton = this.buttonTarget.innerHTML;
        this.buttonTarget.innerHTML += loading;

        const response = await fetch(this.element.action, {
            method: this.element.method,
            body: new FormData(this.element)
        });

        if (response.status === 200) {
            this.dispatch('async:submitted', {
                response,
            });
            notyf('success', await response.text())
        } else {
            notyf('error', await response.text())
            this.buttonTarget.disabled = false;
            this.buttonTarget.innerHTML = innerButton;
        }
    }
}
