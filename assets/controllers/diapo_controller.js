import {Controller} from "stimulus";
import Swiper, {Autoplay} from 'swiper';
import 'swiper/css';
import 'swiper/css/pagination';

export default class extends Controller {
    connect() {
        new Swiper(this.element, {
            modules: [Autoplay],
            loop: true,
            loopAdditionalSlides: 5,
            slidesPerView: 1,
            breakpoints: {
                414: {
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 3,
                },
                1024: {
                    slidesPerView: 4,
                },
                1280: {
                    slidesPerView: 5,
                },
            },
            spaceBetween: 0,
            effect: 'slide',
            autoplay: {
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
                delay: 1000,
            },
        });
    }
}