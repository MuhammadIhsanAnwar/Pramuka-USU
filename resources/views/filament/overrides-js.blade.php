<script>
(function(){
  const root = document.documentElement.style;
  // Primary color shades — main is #3E271A
  root.setProperty('--primary-50', '#fbf6f4');
  root.setProperty('--primary-100', '#f6ede9');
  root.setProperty('--primary-200', '#eddcd3');
  root.setProperty('--primary-300', '#dcc3b6');
  root.setProperty('--primary-400', '#b98a68');
  root.setProperty('--primary-500', '#3E271A');
  root.setProperty('--primary-600', '#3a2317');
  root.setProperty('--primary-700', '#321b12');
  root.setProperty('--primary-800', '#2a160e');
  root.setProperty('--primary-900', '#1f0f08');
  // livewire progress bar
  root.setProperty('--livewire-progress-bar-color', '#3E271A');
})();
</script>

<script>
// Ensure active/open sidebar items always get dark-brown color, including when Filament toggles classes dynamically
(function(){
  const ACTIVE_SELECTOR = '.fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-btn, .fi-sidebar .fi-sidebar-item-open .fi-sidebar-item-btn';
  const COLOR = '#3E271A';

  function applyActiveStyles() {
    document.querySelectorAll(ACTIVE_SELECTOR).forEach(btn => {
      try {
        btn.style.setProperty('color', COLOR, 'important');
        // target icons
        btn.querySelectorAll('svg, path, circle, rect').forEach(svgEl => {
          svgEl.style.setProperty('fill', COLOR, 'important');
          svgEl.style.setProperty('stroke', COLOR, 'important');
        });
      } catch (e) {
        // ignore
      }
    });
  }

  // run on load
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', applyActiveStyles);
  } else {
    applyActiveStyles();
  }

  // observe sidebar for dynamic class changes
  const sidebar = document.querySelector('.fi-sidebar');
  if (sidebar) {
    const mo = new MutationObserver(() => applyActiveStyles());
    mo.observe(sidebar, { attributes: true, subtree: true, childList: true });
  }

  // also re-run periodically for any late-loaded icons
  setInterval(applyActiveStyles, 1200);
})();
</script>