/**
 * @file
 * Contains the definition of the behavior liftigniterWidget.
 */

(function ($, drupalSettings, Drupal) {

  'use strict';

  // Initializing templates and widgets variables.
  var templates = drupalSettings.liftigniter.templates,
  widgets = drupalSettings.liftigniter.widgets;

  /**
   * Attaches the JS behavior for registering widgets.
   */
  Drupal.behaviors.liftigniterWidget = {
    attach: function (context, settings) {
    }
  };

  /**
   * Adds Anchor / Non-Anchor tracking for the widgets.
   */
  function callWidgetTracking(tracking, widgetName, algorithm) {
    switch (tracking.method) {
      case 'anchor':
        $p('track', {
          elements: document.querySelectorAll(tracking.selector),
          name: widgetName,
          source: algorithm,
          _debug: tracking.debug
        });
        break;
      case 'non_anchor':
        // Add Proper Non Anchor Tracking Function.
        // nonAnchorTrackWidget(tracking.selector, widgetName, algorithm, '');// Add options for defualt URL or Inventory URL
        break;
    }
  }

  /**
   * Registering widgets.
   */
  function registerWidget(widget) {
    $p('register', {
      max: parseInt(widget.max, 10),
      widget: widget.widget,
      callback: function(resp) {
        if (resp.items && resp.items.length) {
          // Hook to modify response.
          switch (widget.renderer.method) {
            case 'all':
              // Check if this can be achived for multiple Renderer.
              // $.each(widget.rendered, function(key, value) {
              //   var $element = $(value.selector);
              //   $element.html($p('render', templates[value.template].twig, resp));
              // });
              var $element = $(widget.renderer.selector);
              $element.html($p('render', templates[widget.renderer.template].twig, resp));
              break;
            case 'each':
              var els = document.querySelectorAll(widget.renderer.selector);
              for (var i = 0; i < els.length && i < resp.items.length; ++i) {
                els[i].innerHTML = $p('render', templates[widget.renderer.template].twig, resp.items[i]);
              }
              break;
          }
          if (widget.track.li.enable) {
            callWidgetTracking(widget.track.li, widget.widget, 'LI');
          }
        }
      },
      opts: JSON.parse(widget.opts)
    });
  }

  /**
   * Callback function for AB testing.
   */
  function abTestHandler(slice) {
    $.each(widgets, function(key, widget) {
      if ($(widget.selector).length) {
        if (widget.no_of_items == 'dynamic') {
          widget.max = $(widget.selector).length;
        }
        var registerWidgetFlag = true; // Default to true. register hook to modify this value.
        if (registerWidgetFlag) {
          if (widget.ab_testing.enable) {
            if (slice < widget.ab_testing.slice) {
              registerWidget(widget);
            }
            else {
              if (widget.track.base.enable) {
                callWidgetTracking(widget.track.base, widget.widget, 'base');
              }
            }
          }
          else {
            registerWidget(widget);
          }
        }
      }
    });
    if (drupalSettings.liftigniter.request_fields) {
      $p("setRequestFields", drupalSettings.liftigniter.request_fields);
    }
    if (drupalSettings.liftigniter.array_request_fields) {
      $p("setArrayRequestFields", drupalSettings.liftigniter.array_request_fields);
    }
    $p('fetch');
  }
  $p('abTestSlice', {
    callback: abTestHandler
  });
})(jQuery, drupalSettings, Drupal);
