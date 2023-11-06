import {Controller} from "@hotwired/stimulus";
import Swal from "sweetalert2";

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static values = {
        title: String,
        text: String,
        icon: String,
        confirmationButtonText: String,
        submitAsync: Boolean,
        url: String,
    }

    onSubmit(event) {
        event.preventDefault();

        Swal.fire({
            title: this.titleValue || null,
            text: this.textValue || null,
            icon: this.iconValue || null,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: this.confirmationButtonTextValue || 'Oui',
            cancelButtonText: 'Annuler',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return this.submitForm();
            }
        });
    }

    async submitForm() {
        if (!this.submitAsyncValue) {
            this.element.submit();

            return;
        }

        if (this.urlValue) {
            window.location.replace(this.urlValue);

            return;
        }

        const response = await fetch(this.element.action, {
            method: this.element.method,
            body: new FormData(this.element)
        });

        this.dispatch('async:submitted', {
            response,
        });
    }
}