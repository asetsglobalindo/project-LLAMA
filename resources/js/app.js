import "./bootstrap";
import "flowbite";

// ============= SLIDER SWIPER FOR CARD ====================
const swiper = new Swiper(".swiper-container", {
    loop: true,
    spaceBetween: 9,
    autoplay: {
        delay: 10000,
        disableOnInteraction: false,
    },
});

// Check if elements exist before adding event listeners
const nextButton = document.getElementById("next");
const prevButton = document.getElementById("prev");

if (nextButton) {
    nextButton.addEventListener("click", () => swiper.slideNext());
}

if (prevButton) {
    prevButton.addEventListener("click", () => swiper.slidePrev());
}
