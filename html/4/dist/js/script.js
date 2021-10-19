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

new Swiper(".swiper .reviews-swiper", {
  slidesPerView: 1,
  //   spaceBetween: 40,
  pagination: {
    el: ".swiper-pagination",
  },
  //   breakpoints: {
  //     600: {
  //       slidesPerView: 2,
  //       spaceBetween: 30,
  //     },
  //     850: {
  //       slidesPerView: 3,
  //       spaceBetween: 5,
  //     },
  //   },
});
