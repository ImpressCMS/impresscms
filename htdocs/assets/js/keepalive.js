/* keepalive.js */

document.addEventListener("DOMContentLoaded", function () {
	/* 1 minute in milliseconds */
	const KEEPALIVE_INTERVAL = 1 * 60 * 1000;

	/* Keepalive URL */
	const scriptTag = document.getElementById("keepalive-script");
	const KEEPALIVE_URL = scriptTag ? scriptTag.dataset.keepaliveUrl : "";

	if (!KEEPALIVE_URL) {
		return;
	}

	/* Active request controller */
	let inflightController = null;

	/* Periodic keepalive */
	setInterval(function () {
		sendKeepAlive();
	}, KEEPALIVE_INTERVAL);

	/* Send keepalive request */
	function sendKeepAlive() {
		// Abort previous request
		if (inflightController) {
			inflightController.abort();
		}
		const controller = new AbortController();
		inflightController = controller;

		fetch(KEEPALIVE_URL, {
			method: "GET",
			credentials: "include",
			cache: "no-store",
			signal: controller.signal,
			headers: {
				"X-Requested-With": "XMLHttpRequest",
				"Cache-Control": "no-store",
			},
		})
			.then((response) => {
				if (inflightController === controller) {
					inflightController = null;
				}
				if (!response.ok) {
					throw new Error("HTTP " + response.status);
				}
				return response.json();
			})
			.then(function () {
				// Consume response
			})
			.catch(() => {
				if (inflightController === controller) {
					inflightController = null;
				}
				// Ignore errors
			});
	}
});
