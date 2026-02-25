/*-------------------------------------------------------------
 * keepalive.js – Keeps an ImpressCMS session alive while the
 * user is interacting with the page.
 *-------------------------------------------------------------*/

document.addEventListener("DOMContentLoaded", function () {
	/* 5 minutes in milliseconds */
	const KEEPALIVE_INTERVAL = 5 * 60 * 1000;

	/* URL of the keep‑alive endpoint (root‑relative) */
	const KEEPALIVE_URL = "/keepalive.php";

	/* Timestamp of the last detected user activity */
	let lastActivity = Date.now();

	/*--- 1️⃣  Initial ping -----------------------------------*/
	sendKeepAlive();

	/*--- 2️⃣  Periodic ping – only after the idle interval */
	setInterval(function () {
		if (Date.now() - lastActivity > KEEPALIVE_INTERVAL) {
			sendKeepAlive();
			lastActivity = Date.now(); // reset the counter
		}
	}, KEEPALIVE_INTERVAL);

	/*--- 3️⃣  Send a GET request to the server -------------*/
	function sendKeepAlive() {
		fetch(KEEPALIVE_URL, {
			method: "GET",
			credentials: "include",
			headers: { "X-Requested-With": "XMLHttpRequest" },
		})
			.then((r) => r.json())
			.then((data) => console.log("Keepalive:", data))
			.catch((err) => console.error("Keepalive error:", err));
	}

	/*--- 4️⃣  Detect fetch activity ------------------------*/
	const originalFetch = window.fetch;
	window.fetch = function (...args) {
		lastActivity = Date.now();
		return originalFetch.apply(this, args);
	};

	/*--- 5️⃣  Detect XHR activity --------------------------*/
	const originalXHROpen = XMLHttpRequest.prototype.open;
	XMLHttpRequest.prototype.open = function (...args) {
		this.addEventListener("loadstart", function () {
			lastActivity = Date.now();
		});
		return originalXHROpen.apply(this, args);
	};
});
