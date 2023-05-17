import {Controller} from '@hotwired/stimulus';
import axios from "axios";
import {useDispatch} from "stimulus-use";

export default class extends Controller {
    connect() {
        useDispatch(this);
    }

    async submitForm(event) {
        event.preventDefault();

        await axios.post(this.element.action,
            new FormData(this.element), {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(({data}) => {
                this.dispatch('submitted', {
                    detail: data
                });

                console.log(data);
            });

    }
}