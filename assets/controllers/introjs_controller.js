import {Controller} from "@hotwired/stimulus";
import introJs from "intro.js";
import "intro.js/introjs.css";
import "intro.js/themes/introjs-modern.css";

export default class extends Controller {
    static targets = ["element"];
    static values = {
        steps: {
            type: Array,
            default: [],
        },
        dispatch: String,
    }

    connect() {
        this.introJs = introJs().setOptions({
            exitOnOverlayClick: false,
            exitOnEsc: false,
            nextLabel: "Suivant",
            prevLabel: "Retour",
            skipLabel: "Passer",
            doneLabel: "Fin",
        });
        this.introJs
            .addSteps(
                [
                    ...this.stepsValue,
                    ...this.elementTargets.map((element) => {
                        return {
                            title: element.dataset.title,
                            intro: element.dataset.intro,
                            element: element,
                            position: element.dataset.position,
                            step: element.dataset.step,
                        }
                    }).sort((a, b) => {
                        return Number(a.step) - Number(b.step);
                    })
                ]
            )
            .oncomplete(() => {
                this.close();
            })
            .start();
    }

    close(event) {
        this.introJs
            .exit(false)
            .then(() => {
                if (!this.hasDispatchValue || (event && event.params.button) !== undefined) return
                this.dispatch(this.dispatchValue);
            });
    }

    next(event) {
        this.introJs
            .nextStep()
            .then(() => {
                if (!this.hasDispatchValue || (event && event.params.button) !== undefined) return
                this.dispatch(this.dispatchValue);
            });
    }
}