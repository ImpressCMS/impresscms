/* Copyright 2012 Twitter, Inc.
*
* Licensed under the Apache License, Version 2.0 (the "License");
* you may not use this file except in compliance with the License.
* You may obtain a copy of the License at
*
* http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS,
* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing permissions and
* limitations under the License.
*
* This is a modified version to work outside of bootstrap... - Will Hall
*
* ==========================================================
*/

/* ===================================================
* bootstrap-transition.js v2.0.4
* http://twitter.github.com/bootstrap/javascript.html#transitions
* ===================================================
*/
!function($){$(function(){$.support.transition=(function(){var transitionEnd=(function(){var el=document.createElement("bootstrap"),transEndEventNames={WebkitTransition:"webkitTransitionEnd",MozTransition:"transitionend",OTransition:"oTransitionEnd",msTransition:"MSTransitionEnd",transition:"transitionend"},name;for(name in transEndEventNames){if(el.style[name]!==undefined){return transEndEventNames[name];}}}());return transitionEnd&&{end:transitionEnd};})();});}(window.jQuery);

/* =========================================================
* bootstrap-modal.js v2.0.3
* http://twitter.github.com/bootstrap/javascript.html#modals
* =========================================================
*/
!function($){var Modal=function(content,options){this.options=options;this.$element=$(content).delegate('[data-dismiss="modal"]',"click.dismiss.modal",$.proxy(this.hide,this));};Modal.prototype={constructor:Modal,toggle:function(){return this[!this.isShown?"show":"hide"]();},show:function(){var that=this,e=$.Event("show");this.$element.trigger(e);if(this.isShown||e.isDefaultPrevented()){return;}$("body").addClass("modal-open");this.isShown=true;escape.call(this);backdrop.call(this,function(){var transition=$.support.transition&&that.$element.hasClass("fade");if(!that.$element.parent().length){that.$element.appendTo(document.body);}that.$element.show();if(transition){that.$element[0].offsetWidth;}that.$element.addClass("in");transition?that.$element.one($.support.transition.end,function(){that.$element.trigger("shown");}):that.$element.trigger("shown");});},hide:function(e){e&&e.preventDefault();var that=this;e=$.Event("hide");this.$element.trigger(e);if(!this.isShown||e.isDefaultPrevented()){return;}this.isShown=false;$("body").removeClass("modal-open");escape.call(this);this.$element.removeClass("in");$.support.transition&&this.$element.hasClass("fade")?hideWithTransition.call(this):hideModal.call(this);}};function hideWithTransition(){var that=this,timeout=setTimeout(function(){that.$element.off($.support.transition.end);hideModal.call(that);},500);this.$element.one($.support.transition.end,function(){clearTimeout(timeout);hideModal.call(that);});}function hideModal(that){this.$element.hide().trigger("hidden");backdrop.call(this);}function backdrop(callback){var that=this,animate=this.$element.hasClass("fade")?"fade":"";if(this.isShown&&this.options.backdrop){var doAnimate=$.support.transition&&animate;this.$backdrop=$('<div class="modal-backdrop hide '+animate+'" />').appendTo(document.body);if(this.options.backdrop!="static"){this.$backdrop.click($.proxy(this.hide,this));}if(doAnimate){this.$backdrop[0].offsetWidth;}this.$backdrop.addClass("in");doAnimate?this.$backdrop.one($.support.transition.end,callback):callback();callback();}else{if(!this.isShown&&this.$backdrop){this.$backdrop.removeClass("in");$.support.transition&&this.$element.hasClass("fade")?this.$backdrop.one($.support.transition.end,$.proxy(removeBackdrop,this)):removeBackdrop.call(this);}else{if(callback){callback();}}}}function removeBackdrop(){this.$backdrop.remove();this.$backdrop=null;}function escape(){var that=this;if(this.isShown&&this.options.keyboard){$(document).on("keyup.dismiss.modal",function(e){e.which==27&&that.hide();});}else{if(!this.isShown){$(document).off("keyup.dismiss.modal");}}}$.fn.modal=function(option){return this.each(function(){var $this=$(this),data=$this.data("modal"),options=$.extend({},$.fn.modal.defaults,$this.data(),typeof option=="object"&&option);if(!data){$this.data("modal",(data=new Modal(this,options)));}if(typeof option=="string"){data[option]();}else{if(options.show){data.show();}}});};$.fn.modal.defaults={backdrop:true,keyboard:true,show:true};$.fn.modal.Constructor=Modal;$(function(){$("body").on("click.modal.data-api",'[data-toggle="modal"]',function(e){var $this=$(this),href,$target=$($this.attr("data-target")||(href=$this.attr("href"))&&href.replace(/.*(?=#[^\s]+$)/,"")),option=$target.data("modal")?"toggle":$.extend({},$target.data(),$this.data());e.preventDefault();$target.modal(option);});});}(window.jQuery);

/* ========================================================
* bootstrap-tab.js v2.1.0
* http://twitter.github.com/bootstrap/javascript.html#tabs
* ========================================================
*/
!function(b){var a=function(c){this.element=b(c)};a.prototype={constructor:a,show:function(){var i=this.element,f=i.closest("ul:not(.dropdown-menu)"),d=i.attr("data-target"),g,c,h;if(!d){d=i.attr("href");d=d&&d.replace(/.*(?=#[^\s]*$)/,"")}if(i.parent("li").hasClass("active")){return}g=f.find(".active a").last()[0];h=b.Event("show",{relatedTarget:g});i.trigger(h);if(h.isDefaultPrevented()){return}c=b(d);this.activate(i.parent("li"),f);this.activate(c,c.parent(),function(){i.trigger({type:"shown",relatedTarget:g})})},activate:function(e,d,h){var c=d.find("> .active"),g=h&&b.support.transition&&c.hasClass("fade");function f(){c.removeClass("active").find("> .dropdown-menu > .active").removeClass("active");e.addClass("active");if(g){e[0].offsetWidth;e.addClass("in")}else{e.removeClass("fade")}if(e.parent(".dropdown-menu")){e.closest("li.dropdown").addClass("active")}h&&h()}g?c.one(b.support.transition.end,f):f();c.removeClass("in")}};b.fn.tab=function(c){return this.each(function(){var e=b(this),d=e.data("tab");if(!d){e.data("tab",(d=new a(this)))}if(typeof c=="string"){d[c]()}})};b.fn.tab.Constructor=a;b(function(){b("body").on("click.tab.data-api",'[data-toggle="tab"], [data-toggle="pill"]',function(c){c.preventDefault();b(this).tab("show")})})}(window.jQuery);