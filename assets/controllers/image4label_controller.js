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
        isLoadedActivated: {
            type: Boolean,
            default: true,
        }
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
            if (this.isLoadedActivatedValue) this.imagePreviewTarget.src = imgTarget.src;
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
        } else {
            if (file) {
                this.updateInput({detail: {file: [file]}})
            }
        }
    }

    updateInput(event) {
        let [file] = event.detail.file;
        let fileType = mime.getType(file.name).split("/")[0];

        if (fileType === "image") {
            if (file) {
                this.fileInputTarget.files = event.detail.file;

                this.imagePreviewTarget.src = URL.createObjectURL(file);
                this.imagePreviewTarget.classList.remove('hidden');
            }
        } else {
            if (file) {
                let video = document.createElement('video');
                video.src = URL.createObjectURL(file);

                video.addEventListener('loadeddata', () => {
                    video.currentTime = 0; // ou un autre moment si vous préférez
                });

                video.addEventListener('seeked', () => {
                    let canvas = document.createElement('canvas');
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    let ctx = canvas.getContext('2d');
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                    this.imagePreviewTarget.src = canvas.toDataURL();
                    this.imagePreviewTarget.classList.remove('hidden');
                });
            }
        }
    }
}