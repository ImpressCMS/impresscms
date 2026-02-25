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
			/* ----- 4️⃣  Tell the browser not to cache the request -----*/
			cache: "no-store",
			headers: {
				"X-Requested-With": "XMLHttpRequest",
				"Cache-Control": "no-store", // extra safety for HTTP‑level caching
			},
		})
			.then((r) => r.json())
			.then((data) => console.log("Keepalive:", data))
			.catch((err) => console.error("Keepalive error:", err));
	}

	/*--- Detect fetch activity ------------------------*/
	const originalFetch = window.fetch;
	window.fetch = function (...args) {
		lastActivity = Date.now();
		return originalFetch.apply(this, args);
	};

	/*--- Detect XHR activity --------------------------*/
	const originalXHROpen = XMLHttpRequest.prototype.open;
	XMLHttpRequest.prototype.open = function (...args) {
		this.addEventListener("loadstart", function () {
			lastActivity = Date.now();
		});
		return originalXHROpen.apply(this, args);
	};
});
