import {Controller} from '@hotwired/stimulus';
import 'cropperjs/dist/cropper.css';
import Cropper from 'cropperjs';

export default class extends Controller {
    static targets = ['image'];

    connect() {
        this.cropper = new Cropper(this.imageTarget, {
            aspectRatio: 1,
            viewMode: 1,
            guides: false,
            background: false,
        });
    }

    saveCropped(event) {
        event.preventDefault();

        const canvas = this.cropper.getCroppedCanvas({
            width: 320,
            height: 320,
        });

        this.imageTarget.src = canvas.toDataURL();

        canvas.toBlob(blob => {
            const newFile = new File([blob], "cropped_image.webp", {type: "image/webp"});
            const dataTransfer = new DataTransfer();
            console.log(blob, newFile, dataTransfer);
            dataTransfer.items.add(newFile);

            this.dispatch('crop:save', {detail: {file: dataTransfer.files}});
        }, 'image/webp');
    }
}