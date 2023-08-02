import {Controller} from '@hotwired/stimulus';
import {getContent} from "../utils/modal/crop_image";

export default class extends Controller {
    static targets = ['fileInput', 'imagePreview'];

    connect() {
        this.windowsLoaded();
    }

    windowsLoaded() {
        const aTarget = this.element.getElementsByTagName('a')[0];
        if (aTarget) {
            const imgTarget = aTarget.getElementsByTagName('img')[0];
            aTarget.classList.add('hidden');
            this.imagePreviewTarget.src = imgTarget.src;
        }
    }

    updateLabel() {
        let [file] = this.fileInputTarget.files;

        if (file) {
            let content = getContent(file);

            this.dispatch('modal:open', {detail: {content}});
        }
    }

    updateInput(event) {
        this.fileInputTarget.files = event.detail.file;

        let [file] = event.detail.file;

        this.imagePreviewTarget.src = URL.createObjectURL(file);
    }
}