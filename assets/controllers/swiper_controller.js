import {Controller} from "stimulus";
import Swiper, {Pagination, Mousewheel} from 'swiper';
import 'swiper/css';
import 'swiper/css/pagination';

export default class extends Controller {
    static targets = ['pagination', 'newsletterPopup'];
    static values = {
        isFeaturedSwipeUp: Boolean
    };

    connect() {
        this.isNewsletterPopup = true;

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
                slideChange: (event) => {
                    if (event.activeIndex === 1 && this.isFeaturedSwipeUpValue && this.isNewsletterPopup) {
                        this.openNewsletterPopup(event);
                    }
                },
            }
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