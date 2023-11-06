import {Controller} from "@hotwired/stimulus";
import ga from "../services/ga";
import fb from "../services/pixel";

export default class extends Controller {
    static values = {
        ga: String,
        fb: String,
    }

    connect() {
        if (this.gaValue) {
            ga(this.gaValue);
        }
        if (this.fbValue) {
            fb(this.fbValue);
        }
    }
}