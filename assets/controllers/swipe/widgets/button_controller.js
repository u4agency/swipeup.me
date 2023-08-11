import {Controller} from '@hotwired/stimulus';
import {useTransition} from "stimulus-use";

export default class extends Controller {
    static targets = ['content'];
    static values = {
        "radios": String,
    };

    connect() {
        useTransition(this, {
            element: this.contentTarget,
            enterActive: "transition ease-out duration-300",
            enterFrom: "transform opacity-0 scale-95",
            enterTo: "transform opacity-100 scale-100",
            leaveActive: "transition ease-in duration-300",
            leaveFrom: "transform opacity-100 scale-100",
            leaveTo: "transform opacity-0 scale-95",

            hiddenClass: 'hidden',
            transitioned: false,
        });

        this.dropdown();
    }

    dropdown() {
        const radios = this.element.querySelectorAll(`input[name="${this.radiosValue}"]`);

        radios.forEach(radio => {
            console.log(radio);
            if (radio.checked && radio.value === "gradient") {
                this.enter();
            } else {
                this.leave();
            }
        });
    }
}
