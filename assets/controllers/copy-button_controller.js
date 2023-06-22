import {Controller} from "stimulus";

export default class extends Controller {
    static values = {
        copyText: String,
    }

    connect() {
        this.element.addEventListener('click', (e) => {
            this.copyText(e)
        });
    }

    copyText(e) {
        navigator.clipboard.writeText(this.copyTextValue)
            .then(r => e.target.innerHTML = 'Copié !');
    }
}