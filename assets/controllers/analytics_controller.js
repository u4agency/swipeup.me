import {Controller} from "@hotwired/stimulus";
import fb from "../services/pixel";

export default class extends Controller {
    static values = {
        fb: String,
    }

    connect() {
        if (this.fbValue) {
            fb(this.fbValue);
        }
    }
}