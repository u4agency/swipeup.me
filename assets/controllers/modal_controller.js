import {Controller} from '@hotwired/stimulus';
import {useClickOutside, useTransition} from "stimulus-use";

export default class extends Controller {
    static targets = ['boxToClose', 'modal'];
    static values = {
        closeOutside: {
            type: Boolean,
            default: true,
        }
    };

    connect() {
        if (this.closeOutsideValue) {
            useClickOutside(this, {element: this.modalTarget});
        }

        useTransition(this, {
            element: this.boxToCloseTarget,
            enterActive: 'fade-enter-active',
            enterFrom: 'fade-enter-from',
            enterTo: 'fade-enter-to',
            leaveActive: 'fade-leave-active',
            leaveFrom: 'fade-leave-from',
            leaveTo: 'fade-leave-to',
            hiddenClass: 'hidden',
        });
    }

    clickOutside(event) {
        event.preventDefault();

        this.close();
    }

    close() {
        document.body.classList.remove("overflow-hidden");

        this.leave();
    }

    open() {
        document.body.classList.add("overflow-hidden");

        this.enter();
    }

    openDispatch(event) {
        this.modalTarget.innerHTML = event.detail.content;

        this.open();
    }

    toggle() {
        this.toggleTransition();
    }
}