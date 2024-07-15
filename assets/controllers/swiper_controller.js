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
        isPreview: Boolean,
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
                    if (this.hasAnalyticsUrlValue && !this.isPreviewValue) this.swipeAnalytics(event.slides[event.activeIndex].dataset.swipe, event.slides[event.activeIndex].dataset.analyticsCsrf)
                },
                slideChange: (event) => {
                    if (this.hasAnalyticsUrlValue && !this.isPreviewValue) {
                        this.swipeAnalytics(event.slides[event.activeIndex].dataset.swipe, event.slides[event.activeIndex].dataset.analyticsCsrf, this.lastAnalyticsSwipeId,)

                        if (event.activeIndex === 1 && this.isFeaturedSwipeUpValue && this.isNewsletterPopup) {
                            this.openNewsletterPopup(event);
                        }
                    }
                },
            }
        });

        // Gestion du scroll conditionnel sur la dernière slide pour la molette de la souris
        document.querySelectorAll('.swiper-slide .overflow-y-auto').forEach(element => {
            element.addEventListener('wheel', (event) => {
                if (element.scrollTop === 0 && event.deltaY < 0) {
                    // Si la scrollbar est tout en haut et que l'utilisateur essaie de défiler vers le haut
                    this.swiper.slidePrev();
                    event.preventDefault();
                } else if (element.scrollHeight - element.scrollTop === element.clientHeight && event.deltaY > 0) {
                    // Si la scrollbar est tout en bas et que l'utilisateur essaie de défiler vers le bas
                    this.swiper.slideNext();
                    event.preventDefault();
                } else {
                    // Sinon, autorisez le défilement normal
                    event.stopPropagation();
                }
            });

            // Gestion du scroll conditionnel sur la dernière slide pour les événements tactiles
            let startY, startScrollTop;

            element.addEventListener('touchstart', (event) => {
                startY = event.touches[0].pageY;
                startScrollTop = element.scrollTop;
            });

            element.addEventListener('touchmove', (event) => {
                let currentY = event.touches[0].pageY;
                let diffY = startY - currentY;

                if (element.scrollTop === 0 && diffY < 0) {
                    // Si la scrollbar est tout en haut et que l'utilisateur essaie de défiler vers le haut
                    this.swiper.slidePrev();
                    event.preventDefault();
                } else if (element.scrollHeight - element.scrollTop === element.clientHeight && diffY > 0) {
                    // Si la scrollbar est tout en bas et que l'utilisateur essaie de défiler vers le bas
                    this.swiper.slideNext();
                    event.preventDefault();
                } else {
                    // Sinon, autorisez le défilement normal
                    event.stopPropagation();
                }
            });
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
