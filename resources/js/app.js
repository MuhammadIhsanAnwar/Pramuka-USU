// Global UI enhancements: loading overlay and smooth page transitions
document.addEventListener('DOMContentLoaded', function () {
	// Page enter animation
	document.documentElement.classList.add('page-transition-enter');
	requestAnimationFrame(() => {
		setTimeout(() => document.documentElement.classList.remove('page-transition-enter'), 350);
	});

	// Network-aware animated homepage loader
	const loader = document.getElementById('homepage-loader');
	if (loader) {
		const progressBar = loader.querySelector('.loader-progress-bar');

		// Estimate duration based on Network Information API when available
		const conn = navigator.connection || navigator.mozConnection || navigator.webkitConnection || null;
		let estimateMs = 2000; // default
		if (conn) {
			const t = (conn.effectiveType || '').toLowerCase();
			const downlink = conn.downlink || 10;
			if (t.includes('slow-2g')) estimateMs = 12000;
			else if (t.includes('2g')) estimateMs = 9000;
			else if (t.includes('3g')) estimateMs = 5000;
			else if (t.includes('4g')) estimateMs = 1500;
			// adjust by downlink (higher downlink -> faster)
			estimateMs = Math.max(1000, Math.round(estimateMs * (1 / Math.max(0.3, Math.min(downlink / 10, 2)))));
		}

		let percent = 4;
		if (progressBar) progressBar.style.width = percent + '%';

		const start = Date.now();
		const maxTimeout = Math.min(20000, Math.max(8000, estimateMs * 3));

		const tick = () => {
			const elapsed = Date.now() - start;
			// target progress based on elapsed vs estimate, but never reach 100% until load
			const target = Math.min(99, Math.round((elapsed / estimateMs) * 100));
			// smooth random increment when target doesn't increase
			if (target > percent) percent = target;
			else percent = Math.min(99, percent + Math.random() * 3);
			if (progressBar) progressBar.style.width = percent + '%';
		};

		const intervalId = setInterval(tick, 200);

		const finish = () => {
			clearInterval(intervalId);
			if (progressBar) progressBar.style.width = '100%';
			// small delay to let the bar animate to 100%
			setTimeout(() => {
				loader.classList.add('hidden');
				setTimeout(() => {
					if (loader.parentNode) loader.remove();
				}, 480);
			}, 160);
		};

		// When the page fully loads, finish the loader
		window.addEventListener('load', () => {
			finish();
		});

		// Safety: force-hide after maxTimeout
		setTimeout(() => {
			if (document.body.contains(loader)) finish();
		}, maxTimeout);
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
