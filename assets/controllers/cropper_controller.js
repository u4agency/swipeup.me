import {Controller} from '@hotwired/stimulus';
import 'cropperjs/dist/cropper.css';
import Cropper from 'cropperjs';

export default class extends Controller {
    static targets = ['image'];

    connect() {
        this.cropper = new Cropper(this.imageTarget, {
            aspectRatio: 1, // Pour un recadrage 1:1
            viewMode: 1, // Afficher seulement la zone de recadrage
            guides: false, // Désactiver les lignes de guide
            background: false // Désactiver l'arrière-plan en pointillé
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
            const newFile = new File([blob], "cropped_image.png", {type: "image/png"});
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(newFile);

            this.dispatch('crop:save', {detail: {file: dataTransfer.files}});
        }, 'image/png');
    }
}