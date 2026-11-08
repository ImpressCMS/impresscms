/**
 * icms.notify - small, dependency-free notification messages (replaces jGrowl).
 *
 * icms.notify(message, { type: 'info' | 'success' | 'error', timeout: ms, html: false })
 *
 *  - type     'error' is announced assertively (role="alert") and stays until dismissed.
 *  - timeout  auto-dismiss delay in ms; 0 = stay until dismissed. Default: 0 for errors, 8000 otherwise.
 *             The timer is paused while the pointer or keyboard focus is inside the message.
 *  - html     false (default): the message is shown as text. true: a small allow-list of
 *             formatting tags is kept (no attributes), everything else is stripped.
 *
 * Messages are placed in live regions that exist before the message is added, so that
 * screen readers announce them. Escape dismisses the most recent message.
 */
(function (window, document) {
	'use strict';

	var icms = window.icms = window.icms || {};

	var ALLOWED = { B: 1, STRONG: 1, I: 1, EM: 1, BR: 1, P: 1, UL: 1, OL: 1, LI: 1, H3: 1, H4: 1, CODE: 1, SPAN: 1 };
	var DROP = { SCRIPT: 1, STYLE: 1, IFRAME: 1, OBJECT: 1, EMBED: 1, TEMPLATE: 1 };
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

	/** Copy `source` into `target`, keeping only allow-listed tags and no attributes. */
	function sanitizeInto(source, target) {
		Array.prototype.slice.call(source.childNodes).forEach(function (node) {
			if (node.nodeType === 3) {
				target.appendChild(document.createTextNode(node.nodeValue));
			} else if (node.nodeType === 1) {
				var tag = node.tagName.toUpperCase();
				if (DROP[tag]) {
					return;
				}
				if (ALLOWED[tag]) {
					var el = document.createElement(tag.toLowerCase());
					sanitizeInto(node, el);
					target.appendChild(el);
				} else {
					sanitizeInto(node, target);
				}
			}
		});
	}

	function fillBody(body, message, html) {
		if (!html) {
			body.textContent = message;
			return;
		}
		var tpl = document.createElement('template');
		tpl.innerHTML = message;
		sanitizeInto(tpl.content, body);
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

	icms.notify = notify;
})(window, document);
