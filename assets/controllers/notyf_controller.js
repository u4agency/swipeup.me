import {Controller} from '@hotwired/stimulus';
import notyf from "../utils/notyf";


export default class extends Controller {
    static values = {
        flashes: {
            type: Object,
            default: {},
        },
    }

    connect() {
        if (typeof this.flashesValue === "object") {
            for (const type in this.flashesValue) {
                this.flashesValue[type].forEach(message => {
                    notyf(type, message);
                })
            }
        }
    }
}
