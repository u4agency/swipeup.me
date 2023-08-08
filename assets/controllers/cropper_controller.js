import {Controller} from '@hotwired/stimulus';
import 'cropperjs/dist/cropper.css';
import Cropper from 'cropperjs';

export default class extends Controller {
    static targets = ['image', 'input'];
    static values = {
        ratio: {
            type: Number,
            default: 1,
        },
        width: {
            type: Number,
            default: 320,
        },
        height: {
            type: Number,
            default: 320,
        },
    }

    connect() {
        Cropper.noConflict();

        this.cropper = new Cropper(this.imageTarget, {
            aspectRatio: this.ratioValue,
            viewMode: 1,
            guides: false,
            background: false,
        });
    }

    saveCropped(event) {
        event.preventDefault();

        const canvas = this.cropper.getCroppedCanvas({
            width: this.widthValue,
            height: this.heightValue,
        });

        this.imageTarget.src = canvas.toDataURL();

        canvas.toBlob(blob => {
            const newFile = new File([blob], "cropped_image.webp", {type: "image/webp"});
            const dataTransfer = new DataTransfer();

            dataTransfer.items.add(newFile);

            this.dispatch('crop:save', {detail: {file: dataTransfer.files}});
        }, 'image/webp');
    }
}