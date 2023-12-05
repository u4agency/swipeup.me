import {Controller} from '@hotwired/stimulus';
import {getContent} from "../utils/modal/crop_image";
import notyf from "../utils/notyf";
import mime from 'mime';

export default class extends Controller {
    static targets = ['fileInput', 'imagePreview'];
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
        this.windowsLoaded();
    }

    windowsLoaded() {
        this.supportedTypes = this.fileInputTarget.accept.split(", ");

        const aTarget = this.element.getElementsByTagName('a')[0];

        if (aTarget) {
            const imgTarget = aTarget.getElementsByTagName('img')[0];
            aTarget.classList.add('hidden');
            this.imagePreviewTarget.src = imgTarget.src;
        }
    }

    updateLabel(event) {
        let [file] = this.fileInputTarget.files;

        if (file && !this.supportedTypes.some(prefix => mime.getType(file.name).startsWith(prefix.split('/')[0]))) {
            notyf("error", "Le fichier envoyé n'est pas une fichier valide.");
            return;
        }

        let fileType = mime.getType(file.name).split("/")[0];

        if (fileType === "image") {
            if (file) {
                let content = getContent(file, {
                    height: this.heightValue,
                    width: this.widthValue,
                    ratio: this.ratioValue,
                    type: event.params.type,
                });

                this.dispatch('modal:open', {detail: {content}});
            }
        }
    }

    updateInput(event) {
        this.fileInputTarget.files = event.detail.file;

        let [file] = event.detail.file;

        this.imagePreviewTarget.src = URL.createObjectURL(file);
    }
}