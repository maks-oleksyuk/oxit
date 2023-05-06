(function ($) {
  Drupal.behaviors.myThemeBehavior = {
    attach: function (context, settings) {
      //Toggle classes for superfish menu in mobile screen.
      $(context).find('.navbar-toggler').click(function () {
        $("#superfish-main-menu-toggle").toggleClass('sf-expanded');
        $("#superfish-main-menu-corporate-toggle").toggleClass('sf-expanded');
        $("#superfish-main-menu-accordion").toggleClass('sf-hidden sf-expanded');
        $("#superfish-main-menu-corporate-accordion").toggleClass('sf-hidden sf-expanded');
        $(window).scrollTop(0);
        $('body').toggleClass('fixed-body');
      });

      // Classes for search button and placeholder.
      $(context).find('.search-button').click(function () {
        $(".search-content").toggleClass('search-active');
        $('body').toggleClass('fixed-body');
        document.getElementsByName('keys')[0].placeholder = 'Search here...';
      })

      $(context).find('.webform-dialog-narrow').click(function () {
        $('body').addClass('fixed-body');
      })

      // Placeholder on ar lang.
      let html = document.querySelector('html');
      if (html.getAttribute('lang') === "ar"){
        $(context).find('.search-button').click(function () {
          document.getElementsByName('keys')[0].placeholder = 'البحث هنا';
        })
      }

      // Close button for search.
      $(context).find('.search-button-close').click(function () {
        $(".search-content").removeClass('search-active');
        $('body').removeClass('fixed-body');
      })

      //Display none for services windows.
      setTimeout(function () {
        once('sorry-for-timeout','.eapps-link').forEach(function (element) {
          element.style.display = "none"
        });
      }, 1000);

      $(window).once('webform-dialog').on({
        'dialog:afterclose': function () {
          let body = jQuery('body');
          body.removeClass('fixed-body');
        }
      });
    }
  };
})(jQuery);
