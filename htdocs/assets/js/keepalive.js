/*-------------------------------------------------------------
 * keepalive.js – Keeps an ImpressCMS session alive while the
 * user is interacting with the page.
 *-------------------------------------------------------------*/

document.addEventListener("DOMContentLoaded", function () {
	/* 5 minutes in milliseconds */
	const KEEPALIVE_INTERVAL = 5 * 60 * 1000;

	/* ----- Grab the URL that the preload injected ---------------*/
	const scriptTag = document.getElementById("keepalive-script");
	const KEEPALIVE_URL =
		scriptTag && scriptTag.dataset.keepaliveUrl
			? scriptTag.dataset.keepaliveUrl
			: "/keepalive.php"; // graceful fallback for older installations

	/* Timestamp of the last detected user activity */
	let lastActivity = Date.now();

	/*--- Initial ping -----------------------------------*/
	sendKeepAlive();

	/*--- Periodic ping – only after the idle interval */
	setInterval(function () {
		if (Date.now() - lastActivity >= KEEPALIVE_INTERVAL) {
			sendKeepAlive();
			lastActivity = Date.now(); // reset the counter
		}
	}, KEEPALIVE_INTERVAL);

	/*--- Send a GET request to the server -------------*/
	function sendKeepAlive() {
		fetch(KEEPALIVE_URL, {
			method: "GET",
			credentials: "include",
			cache: "no-store",
			headers: {
				"X-Requested-With": "XMLHttpRequest",
				"Cache-Control": "no-store", // extra safety for HTTP‑level caching
			},
		})
			.then((response) => {
				if (!response.ok) {
					throw new Error(`HTTP ${response.status}`);
				}
				return response.json();
			})
			.then((data) => {
				console.log("Keepalive:", data);
			})
			.catch((error) => {
				console.error("Keepalive error:", error);
			});
	}

	function markActivity() {
		lastActivity = Date.now();
	}

	/*--- Detect user activity via DOM events ---------*/
	document.addEventListener("pointerdown", markActivity, { passive: true });
	document.addEventListener("keydown", markActivity);
	document.addEventListener("scroll", markActivity, { passive: true });
	document.addEventListener("focus", markActivity);
	/*--- Question : do we want the keepalive to pauze when the page is hidden? ---------*/
	document.addEventListener("visibilitychange", function () {
		if (!document.hidden) {
			markActivity();
		}
	});

	/*--- Detect XHR activity --------------------------*/
	const originalXHROpen = XMLHttpRequest.prototype.open;
	XMLHttpRequest.prototype.open = function (...args) {
		// Attach the listener only once per XHR instance to avoid accumulation
		if (!this.__keepaliveLoadstartAttached) {
			this.__keepaliveLoadstartAttached = true;
			this.addEventListener("loadstart", function () {
				lastActivity = Date.now();
			});
		}
		return originalXHROpen.apply(this, args);
	};
});
