// Global UI enhancements: loading overlay and smooth page transitions
document.addEventListener('DOMContentLoaded', function () {
	// Page enter animation
	document.documentElement.classList.add('page-transition-enter');
	requestAnimationFrame(() => {
		setTimeout(() => document.documentElement.classList.remove('page-transition-enter'), 350);
	});

	// Hide homepage loader if present
	const loader = document.getElementById('homepage-loader');
	if (loader) {
		// give a little delay to show the logo then fade
		setTimeout(() => {
			loader.classList.add('hidden');
			setTimeout(() => loader.remove(), 500);
		}, 650);
	}

	// Intercept same-origin link clicks to add a small exit animation
	document.addEventListener('click', function (e) {
		const a = e.target.closest('a');
		if (!a) return;
		const href = a.getAttribute('href');
		const target = a.getAttribute('target');
		const download = a.hasAttribute('download');
		if (!href || href.startsWith('#') || target === '_blank' || download) return;

		try {
			const url = new URL(href, window.location.href);
			if (url.origin !== window.location.origin) return; // external
		} catch (err) {
			return; // malformed
		}

		// Allow links that opt-out
		if (a.dataset && a.dataset.noTransition === 'true') return;

		e.preventDefault();
		document.documentElement.classList.add('page-transition-exit');
		setTimeout(() => {
			window.location.href = href;
		}, 300);
	});
});
