$(function () {
  let header = $("header");
  let ghost = $(".ghost");
  let headerHeight = header.outerHeight();
  new bootstrap.ScrollSpy(document.body, {
    target: "#nav",
    offset: headerHeight + 1
  });
  $(document).on("scroll", function () {
    let scrollTop = $(window).scrollTop();
    headerNav.collapse("hide");

    if (scrollTop >= 19) {
      ghost.css({
        display: "inherit",
        height: header.outerHeight()
      });
      header.addClass("navbar-fix");
    } else {
      ghost.css({
        display: "none",
        height: header.outerHeight()
      });
      header.removeClass("navbar-fix");
    }
  });
  let headerNav = $("#navbarNav");
  headerNav.find('.nav-link[href^="#"]').on("click", function (e) {
    e.preventDefault();
    headerNav.collapse("hide");
    let elem = $(e.target.getAttribute("href")).get(0);
    let y = elem.getBoundingClientRect().top + window.pageYOffset;

    if (!header.hasClass("navbar-fix")) {
      y -= header.outerHeight();
    } else {
      y -= headerHeight;
    }

    window.scroll(0, y);
  });
  let reviews = new Swiper(".testimonials .swiper", {
    loop: true,
    slidesPerView: 1,
    spaceBetween: 30,
    grabCursor: true,
    pagination: {
      el: ".testimonials .swiper-pagination",
      clickable: true
    },
    autoplay: {
      delay: 5000,
      disableOnInteraction: false
    },
    breakpoints: {
      768: {
        slidesPerView: 2
      }
    }
  });
  let plans = new Swiper(".price .swiper", {
    grabCursor: true,
    spaceBetween: 27,
    slidesPerView: "auto",
    centeredSlides: true,
    breakpoints: {
      769: {
        centeredSlides: false
      }
    }
  });
});