import {Controller} from "@hotwired/stimulus";
import Swal from "sweetalert2";

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['replacedContent'];
    static values = {
        title: String,
        text: String,
        icon: String,
        confirmationButtonText: String,
        submitAsync: Boolean,
        url: String,
        confirm: {
            type: Boolean,
            default: true
        }
    }

    onSubmit(event) {
        event.preventDefault();
        console.log()
        if (this.confirmValue) {
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
        } else {
            event.target.getElementsByTagName("button").disabled = true;
            this.submitForm();
        }
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

        if (this.replacedContentTarget) {
            this.replacedContentTarget.innerHTML = await response.text();
        }

        this.dispatch('async:submitted', {
            response,
        });
    }
}