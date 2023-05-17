import {Controller} from "stimulus";
import axios from "axios";
import slugify from "slugify";

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
        this.inputTarget.value = slugify(this.inputTarget.value, {
            replacement: '-',
            lower: true,
            remove: /[*+~()'"!:@]/g,
        });

        let eventValue = event.currentTarget.value;

        if (this.lastInput !== this.inputTarget.value && this.inputTarget.value !== '') {
            this.lastInput = this.inputTarget.value
            this.resultTarget.innerHTML = "🔄";

            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
            }

            this.searchTimeout = setTimeout(function () {
                this.search(eventValue).then(function (r) {
                    this.resultTarget.innerHTML = r ? "✅" : "❌";
                }.bind(this));
            }.bind(this), 200);
        }
    }

    async search(query) {
        const params = new URLSearchParams({
            q: query
        });

        const response = await axios.get(`${this.urlValue}?${params.toString()}`);

        return await response.data.response;
    }
}