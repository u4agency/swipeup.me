import {Controller} from "stimulus";
import Swiper, {Pagination, Mousewheel} from 'swiper';
import 'swiper/css';
import 'swiper/css/pagination';

export default class extends Controller {
    static targets = ['pagination'];

    connect() {
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
        });
    }

    nextSlide() {
        this.swiper.slideNext();
    }
}