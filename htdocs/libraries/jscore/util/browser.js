/*  Module: browser
 *  Detects important information relating to user's browser
 *  Current platforms checked:
 *      Phone-Class -
 *          iPhones and iPods
 *          Android devices
 *          Windows devices running OS 7+
 *      Tablet-Class -
 *          iPads
 *          Android devices (Honeycomb and ICS Tested)
 *      Desktop-Class -
 *          Safari
 *          Chrome
 *          FireFox
 *          Internet Explorer 7+
 *          Opera
 *
 *  Properties:
 *      browser: Holds the browser name (Chrome, Safari, IE, etc)
 *      version: Holds the browser software version
 *      isMobile: Set by getMobile(). True if user is on a mobile device, otherwise false. Should return true for iDevices (non iPad), Android devices (non-tablet), Blackberrys, and Windows Phone OS 7+ devices
 *      isTablet: Set by getTablet(). True if user is on a tablet device, otherwise false. Should return true for iPads and Android tablets
 *      isIE: Set by getBrowser(). True if user is on IE, false otherwise
 *
 *  Methods:
 *      initialize: Run initialization routine
 *      getMobile: Returns true if user is on a mobile platform, otherwise false
 *      getBrowser: Will return browser based upon $.browser (abstraction of navigator object)
 *      getVersion: Will return browser version based upon the UA found by getBrowser()
 *      getProperties: Will use an alert to display the contents of the properties object
 */

define(['jquery'], function ($) {
    var userString = navigator.userAgent

    , module = {
        properties: {
            browser: ''
            , version: ''
            , isMobile: 0
            , isTablet: 0
            , isIE: 0
            , ieCompat: ''
            , screenWidth: screen.width
        }
        , initialize: function() {
            this.properties.isMobile = this.getMobile(userString);
            if (!this.properties.isMobile) {
                this.properties.isTablet = this.getTablet(userString);
            }
            this.properties.browser = this.getBrowser(userString);
            this.properties.version = this.getVersion(this.properties.browser, userString);
        }
        , getMobile: function(uaString) {
            if (uaString.match(/iPhone/i) || uaString.match(/Android.*Mobi/i) || (uaString.match(/Android/i) && (this.properties.screenWidth < 768)) || uaString.match(/iPod/i) || uaString.match(/BlackBerry/i) || (navigator.platform.match(/arm/i) && (this.properties.screenWidth < 768)) || uaString.match(/Windows Phone/i)) {
                return 1;
            } else {
                return 0;
            }
        }
        , getTablet: function(uaString) {
            if (uaString.match(/iPad/i) || (uaString.match(/Android/i) && !uaString.match(/Mobi/i)) || (navigator.platform.match(/arm/i) && (this.properties.screenWidth >= 768))) {
                return 1;
            } else {
                return 0;
            }
        }
        , getBrowser: function(uaString) {
            if (uaString.match(/WebKit/i)) {
                return (uaString.match(/Chrome/i) || uaString.match(/Android/i)) ? 'chrome' : (uaString.match(/CriOS/i) ? 'chrome_ios' : ((this.properties.isMobile || this.properties.isTablet) ? 'iOS' : 'safari'));
            } else if (uaString.match(/Firefox/i)) {
                return 'firefox';
            } else if (uaString.match(/MSIE/i)) {
                this.properties.isIE = 1;
                return 'ie';
            } else if (uaString.match(/Opera/i)) {
                return 'opera';
            }
        }
        , getVersion: function(browserType, uaString) {
            var ieVersion
                , jQueryVersion;
            if ((!this.properties.isMobile) && (!this.properties.isTablet)) {
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
            // window.alert('UA String: ' + uaString);
            window.alert('properties object: \nBrowser: '+this.properties.browser + '\nVersion: ' + this.properties.version + '\nMobile Device? ' + this.properties.isMobile + '\nTablet Device? ' + this.properties.isTablet + '\nThe Devil? ' + this.properties.isIE);
        }
    };
    return module;
});