import {Controller} from '@hotwired/stimulus';
import {useTransition} from "stimulus-use";

export default class extends Controller {
    static targets = ['dropdown', 'icon'];
    static values = {
        hiddenClass: String,
    }

    connect() {
        useTransition(this, {
            element: this.dropdownTarget,
            hiddenClass: this.hiddenClassValue ?? 'hidden',
            transitioned: false,
        });
    }

    dropdown(event) {
        event.preventDefault();

        this.toggleTransition();

        if (this.hasIconTarget) {
            this.iconTarget.classList.toggle('rotate-180');
        }
    }
}
