/**
 * domready.js
 * 
 * Cross browser mozilla's 'onDOMContentLoaded' implementation.
 * Executes a function when the dom tree is loaded without waiting for images.
 * 
 * Based on +Element.Events.domready+ from Mootools open source project, 
 * this tiny javascript library adds the emulated 'DOMContentLoaded' functionality.
 * 
 * Features:
 *   - No dependency on external libraries
 *   - Compatible with Prototype.js 
 * 
 * Tested browsers (Windows):
 *   - IE 7 (XP standalone)
 *   - IE 6 SP2
 *   - Firefox 2.0.0.4
 *   - Opera 9.21
 * 
 * Tested browsers (Mac OS X):
 *   - Safari 2.0.4
 *   - Firefox 2.0.0.4
 *   - Mac Opera 9.21
 *   - Mac IE 5.2.3
 *
 * Copyright (c) 2007 Takanori Ishikawa.
 * License: MIT-style license.
 * 
 * MooTools Copyright:
 * copyright (c) 2007 Valerio Proietti, <http://mad4milk.net>
 *
 *
 * See Also:
 *
 *   mootools 
 *   http://mootools.net/
 *   
 *   The window.onload Problem - Solved!
 *   http://dean.edwards.name/weblog/2005/09/busted/
 *   
 *   [PATCH] Faster onload for DomReady.onload
 *   http://dev.rubyonrails.org/ticket/5414
 *   Changeset 6596: Support for "DOMContentLoaded" event handling (prototype.js event branch)
 *   http://dev.rubyonrails.org/changeset/6596
 *
 */

if (typeof DomReady == 'undefined') DomReady = new Object();

/*
 * Registers function +fn+ will be executed when the dom 
 * tree is loaded without waiting for images. 
 * 
 * Example:
 *
 *  DomReady.domReady.add(function() {
 *    ...
 *  });
 *
 */
DomReady.domReady = {
  add: function(fn) {
    
    //-----------------------------------------------------------
    // Already loaded?
    //-----------------------------------------------------------
    if (DomReady.domReady.loaded) return fn();
    
    //-----------------------------------------------------------
    // Observers
    //-----------------------------------------------------------
    var observers = DomReady.domReady.observers;
    if (!observers) observers = DomReady.domReady.observers = [];
    // Array#push is not supported by Mac IE 5
    observers[observers.length] = fn;
    
    //-----------------------------------------------------------
    // domReady function
    //-----------------------------------------------------------
    if (DomReady.domReady.callback) return;
    DomReady.domReady.callback = function() {
      if (DomReady.domReady.loaded) return;
      
      DomReady.domReady.loaded = true;
      if (DomReady.domReady.timer) {
        clearInterval(DomReady.domReady.timer);
        DomReady.domReady.timer = null;
      }
      
      var observers = DomReady.domReady.observers;
      for (var i = 0, length = observers.length; i < length; i++) {
        var fn = observers[i];
        observers[i] = null;
        fn(); // make 'this' as window
      }
      DomReady.domReady.callback = DomReady.domReady.observers = null;
    };
    
    //-----------------------------------------------------------
    // Emulates 'onDOMContentLoaded'
    //-----------------------------------------------------------
    var ie = !!(window.attachEvent && !window.opera);
    var webkit = navigator.userAgent.indexOf('AppleWebKit/') > -1;
    
    if (document.readyState && webkit) {
      
      // Apple WebKit (Safari, OmniWeb, ...)
      DomReady.domReady.timer = setInterval(function() {
        var state = document.readyState;
        if (state == 'loaded' || state == 'complete') {
          DomReady.domReady.callback();
        }
      }, 50);
      
    } else if (document.readyState && ie) {
      // Windows IE 
      var src = (window.location.protocol == 'https:') ? '://0' : 'javascript:void(0)';
      document.write(
        '<script type="text/javascript" defer="defer" src="' + src + '" ' + 
        'onreadystatechange="if (this.readyState == \'complete\') DomReady.domReady.callback();"' + 
        '><\/script>');
      
    } else {
      
      if (window.addEventListener) {
        // for Mozilla browsers, Opera 9
        document.addEventListener("DOMContentLoaded", DomReady.domReady.callback, false);
        // Fail safe 
        window.addEventListener("load", DomReady.domReady.callback, false);
      } else if (window.attachEvent) {
        window.attachEvent('onload', DomReady.domReady.callback);
      } else {
        // Legacy browsers (e.g. Mac IE 5)
        var fn = window.onload;
        window.onload = function() {
          DomReady.domReady.callback();
          if (fn) fn();
        }
      }
      
    }
    
  }
}