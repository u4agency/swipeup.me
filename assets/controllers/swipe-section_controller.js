import {Controller} from "stimulus";
import {v4 as uuid} from "uuid";

export default class extends Controller {
    static targets = ["mainSection", "swipeup", "background"];
    static values = {
        sectionUrl: String,
    };

    connect() {
        this.sectionCount = 0;
        this.sections = [];
        this.sections.push(uuid())

        this.addSection();
    }

    addSection() {
        this.sectionCount += 1;

        this.mainSectionTarget.insertAdjacentHTML('beforeend', `
                <div 
                    class="bg-white rounded-xl p-4 space-y-4"
                    
                >
                    <h2 class="text-center">
                        Section ${this.sectionCount}
                    </h2>
                   
                    <turbo-frame
                            id="swipe_section"
                            src="${this.sectionUrlValue}"
                    >
                        Loading...
                    </turbo-frame>
                </div>
`);
    }

    addSwipeUp(event) {
        this.swipeupTarget.value = event.detail.data.response.id;
    }

    addBackground(event) {
        this.backgroundTarget.value = event.detail.data.response.id;
    }
}