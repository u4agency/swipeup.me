import {Controller} from '@hotwired/stimulus';
import loading from "../utils/loading";


export default class extends Controller {
    static values = {
        classActive: {
            type: String, default: 'bg-blue-500',
        }, widgetType: String, specificWidgetUrl: String,
    };
    static targets = ['specificWidget', 'radio'];

    connect() {
        this.radios = this.radioTarget.getElementsByTagName('input');
        this.label = this.radioTarget.getElementsByTagName('label');

        this.hideRadio();

        this.label[0].classList.add('mr-4');

        for (let radio of this.radios) {
            if (radio.checked) {
                this.element.querySelector(`label[for="${radio.id}"]`).classList.add(this.classActiveValue);
            }

            radio.addEventListener('click', (event) => {
                this.clickRadio(event);
            });
        }
    }

    hideRadio() {
        for (let radio of this.radios) {
            radio.classList.add('hidden');
        }
    }

    clickRadio(event) {
        for (let label of this.label) {
            label.classList.remove(this.classActiveValue);
        }

        if (event.target.checked) {
            this.element.querySelector(`label[for="${event.target.id}"]`)
                .classList.add(this.classActiveValue);
        }

        if (event.target.value === "") {
            this.specificWidgetTarget.innerHTML = "";
            return;
        }

        this.getSpecificWidget(event);
    }

    async getSpecificWidget(event) {
        this.specificWidgetTarget.innerHTML = loading;

        const response = await fetch(`${this.specificWidgetUrlValue}?widgetName=${event.target.value}&widget=${this.widgetTypeValue}`);

        if (response.status !== 200) {
            this.specificWidgetTarget.innerHTML = "Une erreur est survenue."
            return;
        }

        this.specificWidgetTarget.innerHTML = await response.text()
    }
}
