export function getContent(file) {
    return `
    <div
        data-controller="cropper"
    >
        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
            <div class="mt-3 text-center sm:mt-0 sm:text-left">
                <span class="text-base font-semibold leading-6 text-gray-900" id="modal-title">
                    Redimensionner l'image
                </span>
                <div class="mt-2">
                    <img 
                        data-cropper-target="image"
                        src="${URL.createObjectURL(file)}"
                    >
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
            <button data-action="click->cropper#saveCropped click->modal#close"
                    type="button"
                    class="mt-3 inline-flex w-full justify-center rounded-md bg-swipe-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-swipe-700 sm:mt-0 sm:w-auto">
                Redimensionner
            </button>
            <button data-action="click->modal#close"
                    type="button"
                    class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                Annuler
            </button>
        </div>
    </div>
`;
}