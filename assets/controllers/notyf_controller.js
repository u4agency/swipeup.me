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
        if (this.flashesValue && typeof this.flashesValue === "object" && !Array.isArray(this.flashesValue)) {
            console.log("ok");
            for (const type in this.flashesValue) {
                console.log(type);
                this.flashesValue[type].forEach(message => {
                    notyf(type, message);
                })
            }
        }
    }
}
