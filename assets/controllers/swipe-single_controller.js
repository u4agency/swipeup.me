import {Controller} from "stimulus";

export default class extends Controller {
    static targets = ["swipeup", "background"];

    connect() {
        console.log('swipe-single_controller')
    }

    addSwipeUp(event) {
        this.swipeupTarget.value = event.detail.data.response.id;
    }

    addBackground(event) {
        console.log(event.detail.data.response.id)
        this.backgroundTarget.value = event.detail.data.response.id;
    }
}