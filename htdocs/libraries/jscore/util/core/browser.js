/*  Module: browser
 *  Detects important information relating to user's browser
 *
 *    browser:  (string)  - Holds the browser name (Chrome, Safari, IE, etc)
 *    version:  (string)  - Holds the browser software version (String used due to Chrome's version format, eg XX.X.XXXX.XX)
 *    isMobile: (boolean) - Set by findMobile(). True if user is on a mobile device, otherwise false. Should return true for iDevices (non iPad), Android devices (non-tablet), Blackberrys, and Windows Phone OS 7+ devices
 *    isIOS:    (boolean) - Set by _isIOS, which is run inside findMobile. This flag indicates a device is running iOS
 *    isTablet: (boolean) - Set by findTablet(). True if user is on a tablet device, otherwise false. Should return true for iPads and Android tablets
 *    isIE:   (boolean) - Set by findBrowser(). True if user is on IE, false otherwise
 */

define(['jquery'], function ($) {
  var _userString = navigator.userAgent
  , _properties = {
    browser: ''
    , version: ''
    , isMobile: 0
    , mobileRedirected: 0
    , isIOS: 0
    , isTablet: 0
    , isIE: 0
    , ieCompat: ''
    , screenWidth: screen.width
  }

  , module = {
    initialize: function() {
      _properties.isMobile = this.findMobile(_userString);
      if (!_properties.isMobile) {
        _properties.isTablet = this.findTablet(_userString);
      }
      _properties.browser = this.findBrowser(_userString);
      _properties.version = this.findVersion(_properties.browser, _userString);
    }
    , getState: function( property ) {
      return _properties[property];
    }
    , findMobile: function(uaString) {
      if (uaString.match(/iPhone/i) || uaString.match(/Android.*Mobi/i) || (uaString.match(/Android/i) && (_properties.screenWidth < 768)) || uaString.match(/iPod/i) || uaString.match(/BlackBerry/i) || (navigator.platform.match(/arm/i) && (_properties.screenWidth < 768)) || uaString.match(/Windows Phone/i)) {
        _properties.isIOS = _isIOS();
        return 1;
      } else {
        return 0;
      }
    }
    , findTablet: function(uaString) {
      if (uaString.match(/iPad/i) || (uaString.match(/Android/i) && !uaString.match(/Mobi/i)) || (navigator.platform.match(/arm/i) && (_properties.screenWidth >= 768))) {
        return 1;
      } else {
        return 0;
      }
    }
    , findBrowser: function(uaString) {
      if (uaString.match(/WebKit/i)) {
        return (uaString.match(/Chrome/i) || uaString.match(/Android/i)) ? 'chrome' : (uaString.match(/CriOS/i) ? 'chrome_ios' : ((_properties.isMobile || _properties.isTablet) ? 'iOS' : 'safari'));
      } else if (uaString.match(/Firefox/i)) {
        return 'firefox';
      } else if (uaString.match(/MSIE/i)) {
        _properties.isIE = 1;
        return 'ie';
      } else if (uaString.match(/Opera/i)) {
        return 'opera';
      }
    }
    , findVersion: function(browserType, uaString) {
      var ieVersion
        , jQueryVersion;
      if ((!_properties.isMobile) && (!_properties.isTablet)) {
        if (browserType === 'chrome') {
          return uaString.match(/chrome\/(.*)\s/i)[1];
        } else if (browserType === 'firefox') {
          return uaString.match(/firefox\/(.*)$/i)[1];
        } else if (browserType === 'safari') {
          return uaString.match(/version\/(.*)\s/i)[1];
        } else if (browserType === 'opera') {
          return uaString.match(/version\/(.*)$/i)[1];
        } else if (browserType === 'ie') {
          // IE is special. Must account for its ablility to change version modes, as the $.browser version number sometimes changes where the UA version doesn't (Compatibility Mode is a good example of when it happens)
          jQueryVersion = $.browser.version;
          ieVersion = uaString.match(/msie\s(\d*\.\d*)/i)[1];
          return jQueryVersion < ieVersion ? jQueryVersion : ieVersion;
        } else {
          return $.browser.version;
        }
      } else {
        if (browserType === 'firefox') {
          return uaString.match(/firefox\/(\d.*\d)/i)[1];
        } else if (browserType === 'chrome_ios'){
          return uaString.match(/CriOS\/(.*)\sMo/i)[1];
        } else {
          return uaString.match(/version\/(\d*\.\d*)/i)[1];
        }
      }
    }
    , getProperties: function() {
      /* DEV
      window.alert('UA String: ' + uaString + '\n\nproperties object: \nBrowser: '+ _properties.browser + '\nVersion: ' + _properties.version + '\nMobile Device? ' + _properties.isMobile + '\nTablet Device? ' + _properties.isTablet + '\nThe Devil? ' + _properties.isIE);
      /DEV */
    }
    , resetProperties: function() {
      /* DEV
      _properties = {
        browser: ''
        , version: ''
        , isMobile: 0
        , isTablet: 0
        , isIE: 0
        , ieCompat: ''
        , mobileRedirected: 0
        , screenWidth: screen.width
      };
      /DEV */
    }
  }
  , _isIOS = function() {
    return ((navigator.platform.indexOf("iPhone") !== -1) || (navigator.platform.indexOf("iPod") !== -1));
  };

  return module;
});