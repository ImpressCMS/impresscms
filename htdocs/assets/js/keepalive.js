/* keepalive.js */

document.addEventListener("DOMContentLoaded", function () {
	/* 5 minutes in milliseconds */
	const KEEPALIVE_INTERVAL = 5 * 60 * 1000;

	/* Keepalive URL */
	const scriptTag = document.getElementById("keepalive-script");
	const KEEPALIVE_URL = scriptTag ? scriptTag.dataset.keepaliveUrl : "";

	if (!KEEPALIVE_URL) {
		return;
	}

	/* Active request controller */
	let inflightController = null;

	/* Periodic keepalive */
	const intervalId = setInterval(sendKeepAlive, KEEPALIVE_INTERVAL);

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
				// Session is gone (logged out or expired): stop pinging
				if (response.status === 401 || response.status === 403) {
					clearInterval(intervalId);
				}
			})
			.catch(() => {
				// Ignore network errors and aborts
			})
			.finally(() => {
				if (inflightController === controller) {
					inflightController = null;
				}
			});
	}
});
