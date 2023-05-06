import React from "react";
import {render} from "react-dom";
import App from "./components/App";

(function ($, Drupal) {
  Drupal.behaviors.moduleBehavior = {
    attach: function (context, settings) {
      once('live-clock-block', 'html', context).forEach(function (element) {
        const elements = document.getElementsByClassName('live-clock-block block-live-clock');
        for (let element of elements) {
          render(
            <App timezone={element.dataset.timezone}/>,
            document.getElementById(element.dataset.id)
          );
        }
      })
    }
  };
})(jQuery, Drupal);
