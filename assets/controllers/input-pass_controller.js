import {Controller} from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ['input', 'picture']

    handleUpdate(e) {
        this.pictureTargets.forEach((p) => {
            p.classList.remove('border-double', 'border-4', 'border-black');
        })
        e.currentTarget.classList.add('border-double', 'border-4', 'border-black');

        this.inputTarget.value = e.currentTarget.dataset.value;
    }
}