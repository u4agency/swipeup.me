import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        const options = {
            rootMargin: "0px",
            threshold: 0.1,
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    this.loadVideo();
                    observer.unobserve(entry.target);
                }
            });
        }, options);

        observer.observe(this.element);
    }

    loadVideo() {
        const sources = this.element.querySelectorAll("source");
        sources.forEach((source) => {
            source.setAttribute("src", source.getAttribute("data-src"));
        });
        this.element.load();
    }
}

