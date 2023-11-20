import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static values = {
        dispatch: {
            type: String,
            default: 'modal:open',
        },
    };

    async toggle(event) {
        event.preventDefault();

        const response = await fetch(this.element.href);

        this.dispatch(this.dispatchValue, {
            detail: {
                content: await response.text()
            }
        });
    }
}