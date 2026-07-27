<style>
/* Filament override styles loaded after Filament's CSS */
html.fi {
  --fi-color-primary: #3E271A !important;
}
html.fi .fi .btn-primary,
html.fi .btn-primary,
html.fi .fi-body .btn-primary {
  background-color: #3E271A !important;
  color: #fff !important;
  border-color: transparent !important;
}
html.fi .btn-secondary,
html.fi .fi-body .btn-secondary {
  color: #3E271A !important;
  border-color: rgba(62,39,26,0.2) !important;
}
html.fi .fi-logo .brand-text,
html.fi .fi .fi-logo .brand-text,
html.fi .fi-body .fi-logo .brand-text {
  color: #3E271A !important;
}
/* Ensure panel cards use cream backgrounds where appropriate */
html.fi .surface-card,
html.fi .fi-body .surface-card {
  background-color: #fff !important;
  border-color: rgba(62,39,26,0.08) !important;
}
/* Match global homepage background */
html.fi, html.fi body, html.fi .fi-body {
  background-color: #f8f4ea !important; /* same as homepage bg in resources/css/app.css */
}
/* Force livewire progress bar color */
html.fi .livewire-progress { background-color: #3E271A !important; }
</style>
<style>
/* Sidebar: dark brown background, white text/icons */
.fi-main-sidebar,
.fi-sidebar {
  background-color: #3E271A !important;
}
.fi-main-sidebar .fi-sidebar-header,
.fi-sidebar .fi-sidebar-header {
  background-color: transparent !important;
  padding-bottom: 0.75rem;
}
.fi-sidebar .fi-sidebar-nav,
.fi-main-sidebar .fi-sidebar-nav {
  color: #ffffff !important;
  padding: 0.5rem 0.75rem 1rem;
}
.fi-sidebar .fi-sidebar-item {
  margin: 0.2rem 0;
}
.fi-sidebar .fi-sidebar-item-btn,
.fi-sidebar .fi-sidebar-item-label,
.fi-sidebar a,
.fi-sidebar .fi-sidebar-group-label {
  color: #ffffff !important;
}
.fi-sidebar .fi-sidebar-item-btn {
  border-radius: 999px !important;
  padding: 0.7rem 0.9rem !important;
  transition: all 0.2s ease;
}
.fi-sidebar .fi-sidebar-item-btn:hover,
.fi-sidebar .fi-sidebar-item-btn:focus {
  background-color: rgba(255,255,255,0.12) !important;
}
.fi-sidebar .fi-sidebar-item-icon svg,
.fi-sidebar .fi-sidebar-item-icon,
.fi-sidebar .fi-icon {
  color: #ffffff !important;
  fill: #ffffff !important;
  stroke: #ffffff !important;
}
.fi-sidebar .fi-sidebar-group-label {
  margin: 0.6rem 0.4rem 0.25rem;
  font-size: 0.75rem;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  opacity: 0.8;
}
.fi-sidebar .fi-sidebar-footer {
  color: #ffffff !important;
}

/* Make active/open menu items look like clear selected cards */
.fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-btn,
.fi-sidebar .fi-sidebar-item-open .fi-sidebar-item-btn,
.fi-sidebar .fi-sidebar-item.fi-active > .fi-sidebar-item-btn,
.fi-sidebar .fi-sidebar-item.fi-sidebar-item-has-active-child-items > .fi-sidebar-item-btn {
  background-color: #f8f4ea !important;
  color: #3E271A !important;
  box-shadow: inset 0 0 0 1px rgba(62,39,26,0.08);
}
.fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-label,
.fi-sidebar .fi-sidebar-item-open .fi-sidebar-item-label,
.fi-sidebar .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-label,
.fi-sidebar .fi-sidebar-item.fi-sidebar-item-has-active-child-items > .fi-sidebar-item-btn .fi-sidebar-item-label {
  color: #3E271A !important;
}
.fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-icon svg,
.fi-sidebar .fi-sidebar-item-open .fi-sidebar-item-icon svg,
.fi-sidebar .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-icon svg,
.fi-sidebar .fi-sidebar-item.fi-sidebar-item-has-active-child-items > .fi-sidebar-item-btn .fi-sidebar-item-icon svg {
  fill: #3E271A !important;
  stroke: #3E271A !important;
}

/* On small screens ensure the logo/brand text in the sidebar is white */
@media (max-width: 767px) {
  .fi-sidebar .fi-sidebar-header-logo-ctn .fi-logo {
    color: #ffffff !important;
  }
  .fi-sidebar .fi-sidebar-header-logo-ctn .fi-logo div,
  .fi-sidebar .fi-sidebar-header-logo-ctn .fi-logo span {
    color: #ffffff !important;
  }
  .fi-sidebar .fi-sidebar-header-logo-ctn .fi-logo [style] {
    color: #ffffff !important;
  }
}

@media (max-width: 767px) {
  .fi-topbar {
    align-items: center;
  }

  .fi-topbar-open-sidebar-btn,
  .fi-topbar-close-sidebar-btn {
    order: -1 !important;
    margin-inline-end: 0.75rem !important;
  }

  .fi-topbar-start {
    order: 0 !important;
  }
}

/* Topbar user avatar portrait ratio */
html.fi .fi-topbar .fi-user-menu-trigger {
  align-items: center;
  display: inline-flex;
  gap: 0.5rem;
  padding: 0.25rem 0.4rem;
}

html.fi .fi-topbar .fi-user-menu-trigger-avatar-wrapper {
  width: 2.25rem;
  height: 3rem;
  min-width: 2.25rem;
  border-radius: 0.75rem;
  overflow: hidden;
}

html.fi .fi-topbar .fi-user-menu-trigger-avatar-wrapper .fi-avatar {
  width: 100% !important;
  height: 100% !important;
  border-radius: 0 !important;
  object-fit: cover !important;
}

html.fi .fi-topbar .fi-user-menu-trigger-avatar-wrapper .fi-avatar img {
  object-fit: cover !important;
}

html.fi .fi-topbar .fi-user-menu-trigger-name {
  color: #3E271A;
  font-size: 0.875rem;
  font-weight: 600;
  white-space: normal;
  max-width: 12rem;
  overflow: visible;
  text-overflow: unset;
  word-break: break-word;
  line-height: 1.15;
}

html.fi .fi-topbar .fi-user-menu-trigger {
  min-width: 10rem;
  padding: 0.25rem 0.75rem;
}

html.fi .fi-dropdown-panel {
  min-width: 14rem !important;
  width: auto !important;
  background-color: #ffffff !important;
  border: 1px solid rgba(93, 64, 55, 0.1) !important;
  border-radius: 1rem !important;
  box-shadow: 0 24px 60px rgba(15, 23, 42, 0.08) !important;
  padding: 0 !important;
  overflow: hidden !important;
}

html.fi .fi-dropdown-list {
  padding: 0 !important;
}

html.fi .fi-dropdown-list-item {
  padding: 0.75rem 1rem !important;
  color: #334155 !important;
  border-top: 1px solid transparent !important;
  background-color: transparent !important;
  display: flex !important;
  align-items: center !important;
  justify-content: flex-start !important;
  gap: 0.75rem !important;
  min-width: 0 !important;
  width: 100% !important;
}

html.fi .fi-dropdown-list-item:hover,
html.fi .fi-dropdown-list-item:focus-visible {
  background-color: #F5F5DC !important;
}

html.fi .fi-dropdown-panel .fi-dropdown-list-item-logout:hover,
html.fi .fi-dropdown-panel .fi-dropdown-list-item-logout:focus-visible,
html.fi .fi-dropdown-panel .fi-dropdown-list-item-logout button:hover,
html.fi .fi-dropdown-panel .fi-dropdown-list-item-logout button:focus-visible,
html.fi .fi-dropdown-panel .fi-dropdown-list-item-logout:hover .fi-dropdown-list-item-label,
html.fi .fi-dropdown-panel .fi-dropdown-list-item-logout:focus-visible .fi-dropdown-list-item-label {
  background-color: #fee2e2 !important;
  color: #b91c1c !important;
  background-image: none !important;
}
html.fi .fi-dropdown-panel .fi-dropdown-list-item-logout button {
  background-color: transparent !important;
}

html.fi .fi-dropdown-list-item-label {
  color: #334155 !important;
  display: block !important;
  width: 100% !important;
  white-space: normal !important;
  word-break: break-word !important;
}

html.fi .fi-dropdown-list-item svg {
  flex-shrink: 0 !important;
}

html.fi .fi-dropdown-header {
  padding: 1rem 1rem !important;
  border-bottom: 1px solid rgba(93, 64, 55, 0.1) !important;
  color: #334155 !important;
  background-color: #ffffff !important;
  white-space: normal !important;
}

html.fi .fi-dropdown-header p {
  white-space: normal !important;
  word-break: break-word !important;
}

html.fi .fi-dropdown-header .text-left {
  display: flex !important;
  flex-direction: column !important;
  gap: 0.125rem !important;
}

html.fi .fi-topbar .fi-user-menu-trigger {
  background-color: #ffffff;
  border: 1px solid rgba(93, 64, 55, 0.1);
  border-radius: 9999px;
  padding: 0.25rem 0.5rem;
}
</style>
<style>
/* Stronger overrides for active/open sidebar buttons — target labels, icons, SVG & paths */
html.fi .fi-main-sidebar .fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-btn,
html.fi .fi-main-sidebar .fi-sidebar .fi-sidebar-item-open .fi-sidebar-item-btn,
html.fi .fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-btn,
html.fi .fi-sidebar .fi-sidebar-item-open .fi-sidebar-item-btn {
  color: #3E271A !important;
}

html.fi .fi-main-sidebar .fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-btn .fi-sidebar-item-label,
html.fi .fi-main-sidebar .fi-sidebar .fi-sidebar-item-open .fi-sidebar-item-btn .fi-sidebar-item-label,
html.fi .fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-btn .fi-sidebar-item-label,
html.fi .fi-sidebar .fi-sidebar-item-open .fi-sidebar-item-btn .fi-sidebar-item-label {
  color: #3E271A !important;
}

html.fi .fi-main-sidebar .fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-btn .fi-sidebar-item-icon,
html.fi .fi-main-sidebar .fi-sidebar .fi-sidebar-item-open .fi-sidebar-item-btn .fi-sidebar-item-icon,
html.fi .fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-btn .fi-sidebar-item-icon,
html.fi .fi-sidebar .fi-sidebar-item-open .fi-sidebar-item-btn .fi-sidebar-item-icon,
html.fi .fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-btn .fi-icon,
html.fi .fi-sidebar .fi-sidebar-item-open .fi-sidebar-item-btn .fi-icon {
  color: #3E271A !important;
  fill: #3E271A !important;
  stroke: #3E271A !important;
}

html.fi .fi-main-sidebar .fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-btn .fi-sidebar-item-icon svg,
html.fi .fi-main-sidebar .fi-sidebar .fi-sidebar-item-open .fi-sidebar-item-btn .fi-sidebar-item-icon svg,
html.fi .fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-btn .fi-sidebar-item-icon svg,
html.fi .fi-sidebar .fi-sidebar-item-open .fi-sidebar-item-btn .fi-sidebar-item-icon svg,
html.fi .fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-btn .fi-icon svg,
html.fi .fi-sidebar .fi-sidebar-item-open .fi-sidebar-item-btn .fi-icon svg {
  fill: #3E271A !important;
  stroke: #3E271A !important;
}

html.fi .fi-sidebar .fi-sidebar-item-btn .fi-sidebar-item-icon svg path,
html.fi .fi-sidebar .fi-sidebar-item-btn .fi-sidebar-item-icon svg circle,
html.fi .fi-sidebar .fi-sidebar-item-btn .fi-sidebar-item-icon svg rect,
html.fi .fi-sidebar .fi-sidebar-item-btn .fi-icon svg path,
html.fi .fi-sidebar .fi-sidebar-item-btn .fi-icon svg circle,
html.fi .fi-sidebar .fi-sidebar-item-btn .fi-icon svg rect {
  fill: #3E271A !important;
  stroke: #3E271A !important;
}

/* Aggressive page-content overrides for user menu pages */
html.fi .fi-page-main {
  padding: 0 !important;
}

html.fi .fi-page-content {
  padding: 0 !important;
}

html.fi .fi-page-content > div,
html.fi .fi-page-content > form,
html.fi .fi-page-content > section,
html.fi .fi-user-page-shell > div,
html.fi .fi-user-page-shell > form,
html.fi .fi-user-page-shell > section {
  padding: 1rem !important;
  background: #ffffff !important;
  border-radius: 24px !important;
  box-shadow: 0 8px 24px rgba(62, 39, 26, 0.08) !important;
  border: 1px solid rgba(62, 39, 26, 0.12) !important;
}

html.fi .fi-user-page-shell {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  padding: 0.25rem 0 0;
}

html.fi .fi-page-content h1,
html.fi .fi-page-content h2,
html.fi .fi-page-content h3,
html.fi .fi-page-content h4 {
  color: #3E271A !important;
}

html.fi .fi-page-content p,
html.fi .fi-page-content label,
html.fi .fi-page-content button,
html.fi .fi-page-content .fi-btn,
html.fi .fi-page-content .btn {
  color: #5b4b3d !important;
}

html.fi .fi-page-content button,
html.fi .fi-page-content .fi-btn,
html.fi .fi-page-content .btn {
  border-radius: 999px !important;
}

/* Form controls and button styling */
html.fi .fi-page-content input,
html.fi .fi-page-content textarea,
html.fi .fi-page-content select,
html.fi .fi-page-content .fi-input,
html.fi .fi-page-content .fi-textarea,
html.fi .fi-page-content .fi-select {
  border: 1px solid rgba(62, 39, 26, 0.2) !important;
  border-radius: 14px !important;
  padding: 0.7rem 0.9rem !important;
  background-color: #fff !important;
  color: #3E271A !important;
  box-shadow: inset 0 1px 2px rgba(0,0,0,0.03) !important;
}

html.fi .fi-page-content input:focus,
html.fi .fi-page-content textarea:focus,
html.fi .fi-page-content select:focus {
  border-color: #3E271A !important;
  box-shadow: 0 0 0 3px rgba(62,39,26,0.12) !important;
  outline: none !important;
}

html.fi .fi-page-content button,
html.fi .fi-page-content .fi-btn,
html.fi .fi-page-content .btn,
html.fi .fi-page-content .fi-action-group button {
  background-color: #3E271A !important;
  color: #ffffff !important;
  border: 1px solid #3E271A !important;
  padding: 0.7rem 1rem !important;
  font-weight: 600 !important;
  box-shadow: 0 4px 10px rgba(62,39,26,0.12) !important;
}

html.fi .fi-page-content button:hover,
html.fi .fi-page-content .fi-btn:hover,
html.fi .fi-page-content .btn:hover {
  background-color: #2b1b10 !important;
  border-color: #2b1b10 !important;
}

html.fi .fi-page-content .fi-checkbox,
html.fi .fi-page-content .fi-radio {
  accent-color: #3E271A !important;
}

html.fi .fi-page-content .fi-fo-rich-editor-panel p,
html.fi .fi-page-content .fi-fo-rich-editor-panel span,
html.fi .fi-page-content [x-ref="fileInput"] ~ *,
html.fi .fi-page-content .fi-fo-file-upload p,
html.fi .fi-page-content .fi-fo-file-upload span {
  color: #ffffff !important;
}

html.fi .fi-page-content .fi-tabs-item,
html.fi .fi-page-content .fi-tabs-item-label,
html.fi .fi-page-content .fi-tabs-item svg,
html.fi .fi-page-content .fi-tabs-item svg path,
html.fi .fi-page-content .fi-tabs-item svg circle {
  color: #ffffff !important;
  fill: #ffffff !important;
  stroke: #ffffff !important;
}
</style>
<style>
/* Ensure the "Samakan dengan Domisili" toggle shows cream when off and brown when on.
   Placed here so it loads after Filament CSS and other overrides. */
.fi-fo-toggle,
.fi-toggle,
.filament-forms-toggle,
.filament-toggle,
.fi-toggle-track,
.fi-fo-toggle .fi-toggle-track,
.filament-toggle .fi-toggle-track,
.filament-forms-toggle .fi-toggle-track {
    background-color: #FAF3EB !important; /* cream */
    color: rgba(0,0,0,0.85) !important;
    padding: .35rem .6rem !important;
    border-radius: .5rem !important;
    border: 1px solid rgba(62,39,26,0.08) !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: .35rem !important;
}

/* Active state */
.fi-fo-toggle[aria-checked="true"],
.fi-toggle[aria-checked="true"],
.filament-forms-toggle[aria-checked="true"],
.filament-toggle[aria-checked="true"],
.fi-toggle-track[aria-checked="true"],
.fi-fo-toggle[data-state="true"],
.fi-toggle[data-state="true"],
.filament-forms-toggle[data-state="true"] {
    background-color: #3E271A !important; /* brown */
    color: #fff !important;
    border-color: transparent !important;
}
</style>
<style>
/* More specific overrides for Filament toggle button classes to ensure
   cream when off and brown when on, overriding color utility classes. */
.fi-toggle,
.fi-toggle * {
  background-color: #FAF3EB !important; /* cream */
  color: rgba(0,0,0,0.85) !important;
}

/* Active/on state */
.fi-toggle.fi-toggle-on,
.fi-toggle.fi-toggle-on *,
.fi-toggle.fi-toggle-on svg,
.fi-toggle.fi-toggle-on path {
  background-color: #3E271A !important; /* brown */
  color: #fff !important;
  fill: #fff !important;
  stroke: #fff !important;
}

/* Off state explicitly */
.fi-toggle.fi-toggle-off,
.fi-toggle.fi-toggle-off * {
  background-color: #FAF3EB !important;
  color: rgba(0,0,0,0.85) !important;
}
</style>
<style>
/* Strong overrides for checkbox inputs rendered by Filament forms
   Targets inputs with class .fi-checkbox-input (e.g. Samakan dengan Domisili) */
input.fi-checkbox-input {
  background-color: #FAF3EB !important; /* cream */
  background-image: none !important;
  border: 1px solid rgba(62,39,26,0.08) !important;
  box-shadow: none !important;
  width: calc(var(--spacing) * 4) !important;
  height: calc(var(--spacing) * 4) !important;
  appearance: none !important;
  border-radius: .5rem !important;
}

/* Checked / active */
input.fi-checkbox-input:checked,
input.fi-checkbox-input:indeterminate {
  background-color: #3E271A !important; /* brown */
  background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg viewBox='0 0 16 16' fill='%23fff' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M12.207 4.793a1 1 0 0 1 0 1.414l-5 5a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L6.5 9.086l4.293-4.293a1 1 0 0 1 1.414 0z'/%3E%3C/svg%3E") !important;
  background-repeat: no-repeat !important;
  background-position: center !important;
  border-color: transparent !important;
}

/* Ensure disabled state remains visible */
input.fi-checkbox-input:disabled {
  background-color: #F3EDE6 !important;
  opacity: 0.95 !important;
}
</style>
<style>
/* Specific target for the Samakan dengan Domisili checkbox input */
input.samakan-domisili-checkbox {
  background-color: #FAF3EB !important;
  border-radius: .5rem !important;
  border: 1px solid rgba(62,39,26,0.08) !important;
}

input.samakan-domisili-checkbox:checked {
  background-color: #3E271A !important;
  background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg viewBox='0 0 16 16' fill='%23fff' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M12.207 4.793a1 1 0 0 1 0 1.414l-5 5a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L6.5 9.086l4.293-4.293a1 1 0 0 1 1.414 0z'/%3E%3C/svg%3E") !important;
  background-repeat: no-repeat !important;
  background-position: center !important;
}
</style>