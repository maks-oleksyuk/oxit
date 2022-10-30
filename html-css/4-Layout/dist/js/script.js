new Swiper(".swiper", {
  slidesPerView: 1,
  spaceBetween: 40,
  pagination: {
    el: ".swiper-pagination",
  },
  breakpoints: {
    600: {
      slidesPerView: 2,
      spaceBetween: 30,
    },
    850: {
      slidesPerView: 3,
      spaceBetween: 5,
    },
  },
});

new Swiper(".reviews-swiper", {
  loop: true,
  slidesPerView: 1,
  spaceBetween: 30,
  centeredSlides: true,
  speed: 1000,
  effect: "fade",
  fadeEffect: {
    crossFade: true,
  },
  autoplay: {
    delay: 10000,
    waitForTransition: false,
    disableOnInteraction: false,
  },
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
});

new Swiper(".mentors-swiper", {
  slidesPerView: 1,
  autoplay: {
    delay: 10000,
    disableOnInteraction: false,
  },
  breakpoints: {
    850: {
      spaceBetween: 50,
    },
    1: {
      spaceBetween: 30,
    },
  },
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
});
