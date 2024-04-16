import {Controller} from "stimulus";
import Swiper from 'swiper';
import {Pagination, Mousewheel} from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/pagination';
import analytics from "../services/analytics";

export default class extends Controller {
    static targets = ['pagination', 'newsletterPopup'];
    static values = {
        isFeaturedSwipeUp: Boolean,
        analyticsUrl: String,
    };

    connect() {
        this.lastAnalyticsSwipeId = null;
        this.isNewsletterPopup = true;

        window.addEventListener('beforeunload', function (event) {
            this.swipeAnalytics(null, null, this.lastAnalyticsSwipeId)
        }.bind(this));

        this.swiper = new Swiper(this.element, {
            modules: [Pagination, Mousewheel],
            direction: "vertical",
            slidesPerView: 1,
            spaceBetween: 0,
            mousewheel: true,
            pagination: {
                el: this.paginationTarget,
                clickable: true,
            },
            on: {
                init: (event) => {
                    if (this.hasAnalyticsUrlValue) this.swipeAnalytics(event.slides[event.activeIndex].dataset.swipe, event.slides[event.activeIndex].dataset.analyticsCsrf)
                },
                slideChange: (event) => {
                    if (this.hasAnalyticsUrlValue) {
                        this.swipeAnalytics(event.slides[event.activeIndex].dataset.swipe, event.slides[event.activeIndex].dataset.analyticsCsrf, this.lastAnalyticsSwipeId,)

                        if (event.activeIndex === 1 && this.isFeaturedSwipeUpValue && this.isNewsletterPopup) {
                            this.openNewsletterPopup(event);
                        }
                    }
                },
            }
        });
    }

    swipeAnalytics(id, token, exited = null) {
        analytics(this.analyticsUrlValue, id, token, exited)
            .then(response => {
                this.lastAnalyticsSwipeId = response.id || null;
            });
    }

    openNewsletterPopup(slider) {
        this.isNewsletterPopup = false;
        this.newsletterPopupTarget.classList.remove("hidden")
        slider.disable()
    }

    closeNewsletterPopup() {
        this.newsletterPopupTarget.classList.add("hidden")
        this.swiper.enable()
    }

    nextSlide() {
        this.swiper.slideNext();
    }
}