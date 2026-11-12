/**
 * icms.notify - small, dependency-free notification messages (replaces jGrowl).
 *
 * icms.notify(message, { type: 'info' | 'success' | 'error', timeout: ms, html: false })
 *
 *  - type     'error' is announced assertively (role="alert") and stays until dismissed.
 *  - timeout  auto-dismiss delay in ms; 0 = stay until dismissed. Default: 0 for errors, 8000 otherwise.
 *             The timer is paused while the pointer or keyboard focus is inside the message.
 *  - html     false (default): the message is shown as text. true: the message is inserted as
 *             HTML and must already be filtered by the caller (on the server, the redirect
 *             message goes through icms_core_HTMLFilter::filterHTML()).
 *
 * Messages are placed in live regions that exist before the message is added, so that
 * screen readers announce them. Escape dismisses the most recent message.
 */
(function (window, document) {
	'use strict';

	var icms = window.icms = window.icms || {};

	var ICONS = { info: 'ℹ', success: '✓', error: '⚠' };
	var DEFAULT_TIMEOUT = 8000;
	var SHOW_DELAY = 100;

	var regions = null;

	function ensureRegions() {
		if (regions && regions.root.isConnected) {
			return regions;
		}
		var root = document.createElement('div');
		root.className = 'icms-notify';

		var polite = document.createElement('div');
		polite.className = 'icms-notify__region';
		polite.setAttribute('role', 'status');
		polite.setAttribute('aria-live', 'polite');

		var assertive = document.createElement('div');
		assertive.className = 'icms-notify__region';
		assertive.setAttribute('role', 'alert');
		assertive.setAttribute('aria-live', 'assertive');

		root.appendChild(polite);
		root.appendChild(assertive);
		document.body.appendChild(root);

		regions = { root: root, polite: polite, assertive: assertive };
		return regions;
	}

	function fillBody(body, message, html) {
		if (html) {
			body.innerHTML = message;
		} else {
			body.textContent = message;
			body.classList.add('icms-notify__body--text');
		}
	}

	function notify(message, options) {
		options = options || {};
		var type = (options.type === 'error' || options.type === 'success') ? options.type : 'info';
		var timeout = typeof options.timeout === 'number' ? options.timeout : (type === 'error' ? 0 : DEFAULT_TIMEOUT);
		var labels = notify.labels;
		var r = ensureRegions();

		var item = document.createElement('div');
		item.className = 'icms-notify__item icms-notify__item--' + type;

		var icon = document.createElement('span');
		icon.className = 'icms-notify__icon';
		icon.setAttribute('aria-hidden', 'true');
		icon.textContent = ICONS[type];

		var body = document.createElement('div');
		body.className = 'icms-notify__body';
		fillBody(body, String(message), options.html === true);

		var close = document.createElement('button');
		close.type = 'button';
		close.className = 'icms-notify__close';
		close.setAttribute('aria-label', labels.close);
		close.innerHTML = '<span aria-hidden="true">×</span>';

		item.appendChild(icon);
		item.appendChild(body);
		item.appendChild(close);

		var timer = null;
		var remaining = timeout;
		var started = 0;

		function dismiss() {
			clearTimeout(timer);
			if (item.parentNode) {
				item.parentNode.removeChild(item);
			}
		}
		function start() {
			if (timeout > 0) {
				started = Date.now();
				timer = setTimeout(dismiss, remaining);
			}
		}
		function pause() {
			if (timer) {
				clearTimeout(timer);
				timer = null;
				remaining = Math.max(1000, remaining - (Date.now() - started));
			}
		}

		item.addEventListener('mouseenter', pause);
		item.addEventListener('mouseleave', function () { if (!timer) { start(); } });
		item.addEventListener('focusin', pause);
		item.addEventListener('focusout', function () { if (!timer) { start(); } });
		close.addEventListener('click', dismiss);
		item._icmsDismiss = dismiss;

		// Add the message after the live regions have been in the DOM for a moment so that
		// assistive technology announces it.
		setTimeout(function () {
			(type === 'error' ? r.assertive : r.polite).appendChild(item);
			start();
		}, SHOW_DELAY);

		return { dismiss: dismiss, element: item };
	}

	notify.labels = { close: 'Close' };

	document.addEventListener('keydown', function (e) {
		if (e.key !== 'Escape' || !regions) {
			return;
		}
		var items = regions.root.querySelectorAll('.icms-notify__item');
		if (items.length) {
			items[items.length - 1]._icmsDismiss();
		}
	});

	/**
	 * Minimal allowlist HTML sanitizer, used by the deprecated $.jGrowl() shim, whose callers
	 * pass HTML that has not been filtered on the server. The markup is parsed in an inert
	 * document (no scripts run, no resources load); elements and attributes that are not on the
	 * allowlist are removed, and links only keep http(s), mailto and relative URLs.
	 * Unknown elements are replaced by their text content.
	 */
	var ALLOWED_TAGS = { A: 1, B: 1, BR: 1, EM: 1, I: 1, STRONG: 1, U: 1, P: 1, SPAN: 1, UL: 1, OL: 1, LI: 1, H3: 1, H4: 1, CODE: 1 };
	var DROP_WITH_CONTENT = { SCRIPT: 1, STYLE: 1, IFRAME: 1, OBJECT: 1, EMBED: 1, TEMPLATE: 1, NOSCRIPT: 1, TEXTAREA: 1, SELECT: 1 };
	var SAFE_URL = /^(?:https?:|mailto:|[^:]*$)/i;

	function sanitizeNode(parent) {
		Array.prototype.slice.call(parent.childNodes).forEach(function (node) {
			if (node.nodeType === 3) {
				return;
			}
			if (node.nodeType !== 1 || DROP_WITH_CONTENT[node.tagName]) {
				parent.removeChild(node);
				return;
			}
			sanitizeNode(node);
			if (!ALLOWED_TAGS[node.tagName]) {
				while (node.firstChild) {
					parent.insertBefore(node.firstChild, node);
				}
				parent.removeChild(node);
				return;
			}
			Array.prototype.slice.call(node.attributes).forEach(function (attr) {
				var name = attr.name.toLowerCase();
				var ok = node.tagName === 'A' && (name === 'href' || name === 'title');
				if (ok && name === 'href') {
					ok = SAFE_URL.test(attr.value.replace(/[\u0000-\u0020\u007f-\u009f]/g, ''));
				}
				if (!ok) {
					node.removeAttribute(attr.name);
				}
			});
			if (node.tagName === 'A' && node.hasAttribute('href')) {
				node.setAttribute('rel', 'noopener noreferrer');
			}
		});
	}

	function sanitizeHTML(html) {
		var doc = new DOMParser().parseFromString('<body>' + html, 'text/html');
		sanitizeNode(doc.body);
		return doc.body.innerHTML;
	}

	/**
	 * Compatibility shim for the removed jGrowl plugin.
	 *
	 * @deprecated since 2.0.5, will be removed in ImpressCMS 3.0. Use icms.notify() instead.
	 *
	 * Maps the commonly used jGrowl options: life -> timeout, sticky -> timeout 0.
	 * Unlike jGrowl, the HTML of the message is filtered (see sanitizeHTML()): only simple
	 * formatting, lists and safe links are kept; scripts, event handlers and other markup are removed.
	 * Other jGrowl options (position, speed, header, theme, ...) are ignored.
	 */
	function bindJGrowl($) {
		if (!$ || $.jGrowl) {
			return;
		}
		$.jGrowl = function (message, options) {
			options = options || {};
			var mapped = { html: true };
			if (options.sticky) {
				mapped.timeout = 0;
			} else if (typeof options.life === 'number') {
				mapped.timeout = options.life;
			}
			if (options.type) {
				mapped.type = options.type;
			}
			if (window.console && window.console.warn) {
				window.console.warn('$.jGrowl() is deprecated and will be removed in ImpressCMS 3.0. Use icms.notify() instead.');
			}
			return notify(sanitizeHTML(String(message)), mapped);
		};
	}
	bindJGrowl(window.jQuery);
	document.addEventListener('DOMContentLoaded', function () { bindJGrowl(window.jQuery); });

	icms.notify = notify;
	icms.notify.sanitize = sanitizeHTML;
})(window, document);
