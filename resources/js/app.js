// Global UI enhancements: smooth page transitions.
// NOTE: page-transition-enter is intentionally NOT applied on the homepage
// because the homepage-loader overlay manages its own show/hide flow.
// Applying page-transition-enter on <html> would set opacity:0 on the
// entire page once the animation ends (animation-fill-mode: both),
// making all homepage content vanish after the loader disappears.
document.addEventListener('DOMContentLoaded', function () {
    var isHomepage = !!document.getElementById('homepage-loader');

    if (!isHomepage) {
        document.documentElement.classList.add('page-transition-enter');
        requestAnimationFrame(function () {
            setTimeout(function () {
                document.documentElement.classList.remove('page-transition-enter');
            }, 350);
        });
    }

    // Intercept same-origin link clicks to add a small exit animation
    document.addEventListener('click', function (e) {
        var a = e.target.closest('a');
        if (!a) return;
        var href = a.getAttribute('href');
        var target = a.getAttribute('target');
        var download = a.hasAttribute('download');
        if (!href || href.startsWith('#') || target === '_blank' || download) return;

        try {
            var url = new URL(href, window.location.href);
            if (url.origin !== window.location.origin) return;
        } catch (err) {
            return;
        }

        if (a.dataset && a.dataset.noTransition === 'true') return;

        e.preventDefault();
        document.documentElement.classList.add('page-transition-exit');
        setTimeout(function () {
            window.location.href = href;
        }, 320);
    });
});
