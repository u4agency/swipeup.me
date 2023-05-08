import {Controller} from "stimulus";
import axios from "axios";

export default class extends Controller {
    static targets = ['result'];
    static values = {
        url: String,
    };

    onSearchInput(event) {
        this.resultTarget.innerHTML = "🔄";

        this.search(event.currentTarget.value).then(r => {
            this.resultTarget.innerHTML = r ? "✅" : "❌"
        });
    }

    async search(query) {
        const params = new URLSearchParams({
            q: query
        });

        const response = await axios.get(`${this.urlValue}?${params.toString()}`);

        return await response.data.response;
    }
}