import {Controller} from "stimulus";

export default class extends Controller {
    static targets = ['input', 'result'];
    static values = {
        url: String,
    };

    connect() {
        this.lastInput = null;
        this.searchTimeout = null;

        this.inputTarget.addEventListener('input', (event) => {
            this.onSearchInput(event)
        })
    }

    onSearchInput(event) {
        this.resultTarget.classList.replace("border-red-500", "border-transparent");
        this.resultTarget.classList.replace("border-green-500", "border-transparent");

        let eventValue = event.currentTarget.value;

        if (this.lastInput !== this.inputTarget.value && this.inputTarget.value !== '') {
            this.lastInput = this.inputTarget.value

            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
            }

            this.searchTimeout = setTimeout(function () {
                this.search(eventValue).then(function (r) {
                    this.resultTarget.classList.replace("border-transparent", r !== "0" ? "border-red-500" : "border-green-500");
                }.bind(this));
            }.bind(this), 200);
        }
    }

    async search(query) {
        const params = new URLSearchParams({
            swipeup: query
        });

        const response = await fetch(this.urlValue, {
            method: 'POST',
            body: params,
        });

        return await response.text();
    }
}