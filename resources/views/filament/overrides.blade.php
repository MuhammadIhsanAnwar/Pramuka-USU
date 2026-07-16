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
}
.fi-sidebar .fi-sidebar-nav,
.fi-main-sidebar .fi-sidebar-nav {
  color: #ffffff !important;
}
.fi-sidebar .fi-sidebar-item-btn,
.fi-sidebar .fi-sidebar-item-label,
.fi-sidebar a,
.fi-sidebar .fi-sidebar-group-label {
  color: #ffffff !important;
}
.fi-sidebar .fi-sidebar-item-icon svg,
.fi-sidebar .fi-sidebar-item-icon,
.fi-sidebar .fi-icon {
  color: #ffffff !important;
  fill: #ffffff !important;
  stroke: #ffffff !important;
}
.fi-sidebar .fi-sidebar-item-btn:hover,
.fi-sidebar .fi-sidebar-item-btn:focus {
  background-color: rgba(255,255,255,0.06) !important; /* subtle hover */
}
.fi-sidebar .fi-sidebar-item-active,
.fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-btn {
  background-color: #f8f4ea !important; /* cream to match main dashboard background */
}
.fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-label {
  color: #3E271A !important;
}
.fi-sidebar .fi-sidebar-footer {
  color: #ffffff !important;
}

/* Override Filament's '.fi-active' and open-group sidebar item styles. */
.fi-sidebar .fi-sidebar-item.fi-active > .fi-sidebar-item-btn,
.fi-sidebar .fi-sidebar-item.fi-sidebar-item-has-active-child-items > .fi-sidebar-item-btn {
  background-color: #D2B48C !important;
}
.fi-sidebar .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-label,
.fi-sidebar .fi-sidebar-item.fi-sidebar-item-has-active-child-items > .fi-sidebar-item-btn .fi-sidebar-item-label,
.fi-sidebar .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-icon,
.fi-sidebar .fi-sidebar-item.fi-sidebar-item-has-active-child-items > .fi-sidebar-item-btn .fi-sidebar-item-icon {
  color: #3E271A !important;
}
.fi-sidebar .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-icon svg,
.fi-sidebar .fi-sidebar-item.fi-sidebar-item-has-active-child-items > .fi-sidebar-item-btn .fi-sidebar-item-icon svg {
  fill: #3E271A !important;
  stroke: #3E271A !important;
}

/* When a sidebar group/item is opened or active and gets a light background,
   ensure its text and icons are dark so they remain readable. */
.fi-sidebar .fi-sidebar-item-open .fi-sidebar-item-btn,
.fi-sidebar .fi-sidebar-item-open .fi-sidebar-item-btn .fi-sidebar-item-label,
.fi-sidebar .fi-sidebar-item-open .fi-sidebar-item-btn .fi-sidebar-item-icon,
.fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-btn,
.fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-btn .fi-sidebar-item-label,
.fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-btn .fi-sidebar-item-icon {
  background-color: #f8f4ea !important; /* cream for active/open */
  color: #3E271A !important;
}
.fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-btn .fi-sidebar-item-icon svg,
.fi-sidebar .fi-sidebar-item-open .fi-sidebar-item-btn .fi-sidebar-item-icon svg {
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
</style>
<style>
/* Ensure active/open sidebar items use dark brown text/icons (override earlier white rule) */
.fi-main-sidebar .fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-label,
.fi-main-sidebar .fi-sidebar .fi-sidebar-item-open .fi-sidebar-item-label,
.fi-main-sidebar .fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-btn,
.fi-main-sidebar .fi-sidebar .fi-sidebar-item-open .fi-sidebar-item-btn,
.fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-label,
.fi-sidebar .fi-sidebar-item-open .fi-sidebar-item-label {
  color: #3E271A !important;
}
.fi-main-sidebar .fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-icon svg,
.fi-main-sidebar .fi-sidebar .fi-sidebar-item-open .fi-sidebar-item-icon svg,
.fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-icon svg,
.fi-sidebar .fi-sidebar-item-open .fi-sidebar-item-icon svg {
  fill: #3E271A !important;
  stroke: #3E271A !important;
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

html.fi .fi-main-sidebar .fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-btn .fi-sidebar-item-icon svg,
html.fi .fi-main-sidebar .fi-sidebar .fi-sidebar-item-open .fi-sidebar-item-btn .fi-sidebar-item-icon svg,
html.fi .fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-btn .fi-sidebar-item-icon svg,
html.fi .fi-sidebar .fi-sidebar-item-open .fi-sidebar-item-btn .fi-sidebar-item-icon svg,
html.fi .fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-btn .fi-icon svg,
html.fi .fi-sidebar .fi-sidebar-item-open .fi-sidebar-item-btn .fi-icon svg {
  fill: #3E271A !important;
  stroke: #3E271A !important;
}

/* also target common SVG child elements */
html.fi .fi-sidebar .fi-sidebar-item-btn .fi-sidebar-item-icon svg path,
html.fi .fi-sidebar .fi-sidebar-item-btn .fi-sidebar-item-icon svg circle,
html.fi .fi-sidebar .fi-sidebar-item-btn .fi-sidebar-item-icon svg rect {
  fill: #3E271A !important;
  stroke: #3E271A !important;
}
</style>