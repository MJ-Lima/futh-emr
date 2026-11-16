@php
    /* FUTH Lafia EMR — modern hospital UI polish (Filament v5) */
@endphp

<style>
    /* ============ Modern Hospital UI — FUTH Lafia EMR ============ */
    :root {
        --futh-primary: #0f5132;
        --futh-primary-light: #16a34a;
        --futh-primary-bg: #f0fdf4;
        --futh-primary-border: #bbf7d0;
        --futh-accent: #0d9488;
        --futh-text: #111827;
        --futh-text-muted: #6b7280;
        --futh-border: #e5e7eb;
        --futh-bg: #f8fafc;
        --futh-card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
    }

    /* Smooth font rendering */
    body, .fi-body, .fi-tmp-body {
        font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, Arial, sans-serif !important;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        background: var(--futh-bg) !important;
    }

    /* ============ Topbar ============ */
    .fi-topbar {
        background: linear-gradient(to right, #ffffff, #f0fdf4) !important;
        border-bottom: 1px solid var(--futh-border) !important;
        backdrop-filter: blur(8px);
        box-shadow: 0 1px 2px rgba(15, 81, 50, 0.05) !important;
    }

    /* ============ Sidebar ============ */
    .fi-sidebar {
        background: linear-gradient(180deg, #ffffff 0%, #f9fafb 100%) !important;
        border-right: 1px solid var(--futh-border) !important;
    }

    /* Brand area */
    .fi-sidebar-brand, .fi-sidebar-header {
        background: linear-gradient(135deg, #0f5132 0%, #16a34a 100%) !important;
        color: #ffffff !important;
        padding: 1.25rem 1rem !important;
        border-bottom: 1px solid rgba(15, 81, 50, 0.15) !important;
    }

    .fi-sidebar-brand-text, .fi-sidebar-brand-name {
        color: #ffffff !important;
        font-weight: 800 !important;
        letter-spacing: -0.02em !important;
        font-size: 1rem !important;
    }

    /* Nav items */
    .fi-sidebar-nav-items .fi-sidebar-item {
        color: #4b5563 !important;
        border-radius: 0.625rem !important;
        margin: 3px 8px !important;
        padding: 0.5rem 0.75rem !important;
        transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1) !important;
        font-weight: 500 !important;
    }

    .fi-sidebar-nav-items .fi-sidebar-item:hover {
        background: var(--futh-primary-bg) !important;
        color: var(--futh-primary) !important;
        transform: translateX(2px);
    }

    .fi-sidebar-nav-items .fi-sidebar-item-active,
    .fi-sidebar-nav-items .fi-sidebar-item-active[href] {
        background: linear-gradient(135deg, #0f5132 0%, #16a34a 100%) !important;
        color: #ffffff !important;
        font-weight: 600 !important;
        box-shadow: 0 4px 12px rgba(15, 81, 50, 0.25) !important;
    }

    .fi-sidebar-nav-items .fi-sidebar-item-active svg {
        color: #ffffff !important;
    }

    /* Group headings */
    .fi-sidebar-nav-items .fi-sidebar-group-heading,
    .fi-sidebar-nav-items .fi-sidebar-group-label {
        color: #9ca3af !important;
        font-size: 0.7rem !important;
        font-weight: 700 !important;
        letter-spacing: 0.08em !important;
        text-transform: uppercase !important;
        padding: 0.75rem 1rem 0.25rem !important;
    }

    /* ============ Navigation badges ============ */
    .fi-sidebar-nav-items .fi-sidebar-item-badge {
        font-size: 0.65rem !important;
        font-weight: 700 !important;
        min-width: 1.25rem !important;
        padding: 0 0.4rem !important;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1) !important;
    }

    /* ============ Cards / Sections ============ */
    .fi-section, .fi-card, .fi-tmp-section {
        border-radius: 0.875rem !important;
        box-shadow: var(--futh-card-shadow) !important;
        border: 1px solid var(--futh-border) !important;
        overflow: hidden;
    }

    .fi-section-header, .fi-card-header {
        padding: 1rem 1.25rem !important;
        border-bottom: 1px solid var(--futh-border) !important;
        background: linear-gradient(to right, #ffffff, #f9fafb) !important;
    }

    .fi-section-header-heading, .fi-card-heading {
        font-weight: 700 !important;
        color: var(--futh-text) !important;
        font-size: 1rem !important;
        letter-spacing: -0.01em !important;
    }

    .fi-section-header-description, .fi-card-description {
        color: var(--futh-text-muted) !important;
        font-size: 0.85rem !important;
        margin-top: 2px !important;
    }

    /* Section icons */
    .fi-section-header-icon, .fi-card-icon {
        color: var(--futh-primary) !important;
        background: var(--futh-primary-bg) !important;
        border: 1px solid var(--futh-primary-border) !important;
        border-radius: 0.5rem !important;
        padding: 0.4rem !important;
    }

    /* ============ Stat / Widget cards ============ */
    .fi-widget-stat {
        border-radius: 0.875rem !important;
        background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%) !important;
        border: 1px solid var(--futh-border) !important;
        box-shadow: var(--futh-card-shadow) !important;
        padding: 1.25rem !important;
        position: relative;
        overflow: hidden;
        transition: all 0.2s ease !important;
    }

    .fi-widget-stat:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(15, 81, 50, 0.15) !important;
        border-color: var(--futh-primary-border) !important;
    }

    .fi-widget-stat::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 4px; height: 100%;
        background: linear-gradient(180deg, #16a34a 0%, #0f5132 100%);
    }

    .fi-widget-stat-value {
        font-weight: 800 !important;
        color: var(--futh-primary) !important;
        font-size: 1.75rem !important;
        letter-spacing: -0.02em !important;
    }

    .fi-widget-stat-label {
        color: var(--futh-text-muted) !important;
        font-size: 0.75rem !important;
        font-weight: 600 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.06em !important;
    }

    /* ============ Buttons ============ */
    .fi-btn {
        border-radius: 0.625rem !important;
        font-weight: 600 !important;
        transition: all 0.18s ease !important;
    }

    .fi-btn-fi-primary, .fi-btn-color-primary {
        background: linear-gradient(135deg, #16a34a 0%, #0f5132 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 2px 4px rgba(15, 81, 50, 0.2) !important;
    }

    .fi-btn-fi-primary:hover {
        background: linear-gradient(135deg, #15803d 0%, #14532d 100%) !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(15, 81, 50, 0.3) !important;
    }

    /* ============ Tables ============ */
    .fi-table {
        border-radius: 0.875rem !important;
        overflow: hidden;
    }

    .fi-table th {
        background: linear-gradient(to bottom, #f9fafb, #f3f4f6) !important;
        color: var(--futh-text-muted) !important;
        font-size: 0.7rem !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        padding: 0.75rem 1rem !important;
        border-bottom: 2px solid var(--futh-primary-border) !important;
    }

    .fi-table td {
        padding: 0.875rem 1rem !important;
        border-bottom: 1px solid var(--futh-border) !important;
    }

    .fi-table tr:hover td {
        background: var(--futh-primary-bg) !important;
    }

    /* ============ Badges ============ */
    .fi-badge {
        font-weight: 600 !important;
        border-radius: 0.5rem !important;
        padding: 0.25rem 0.6rem !important;
        font-size: 0.72rem !important;
        letter-spacing: 0.02em !important;
    }

    /* ============ Form inputs ============ */
    .fi-input, .fi-select, .fi-textarea, .fi-datepicker {
        border-radius: 0.5rem !important;
        border: 1px solid var(--futh-border) !important;
        padding: 0.625rem 0.875rem !important;
        transition: all 0.18s ease !important;
    }

    .fi-input:focus, .fi-select:focus, .fi-textarea:focus {
        border-color: var(--futh-primary) !important;
        box-shadow: 0 0 0 3px rgba(15, 81, 50, 0.12) !important;
        outline: none !important;
    }

    .fi-form-item label {
        font-weight: 600 !important;
        color: var(--futh-text) !important;
        font-size: 0.85rem !important;
    }

    /* ============ Page header ============ */
    .fi-page-header, .fi-tmp-page-header {
        background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%) !important;
        padding: 1.5rem 1.75rem !important;
        border-radius: 0.875rem !important;
        border: 1px solid var(--futh-border) !important;
        box-shadow: var(--futh-card-shadow) !important;
        margin-bottom: 1rem !important;
    }

    .fi-page-header-heading, .fi-tmp-page-title {
        font-weight: 800 !important;
        color: var(--futh-primary) !important;
        font-size: 1.5rem !important;
        letter-spacing: -0.02em !important;
    }

    /* ============ Tabs ============ */
    .fi-tabs-item-active {
        color: var(--futh-primary) !important;
        border-bottom-color: var(--futh-primary) !important;
        font-weight: 700 !important;
    }

    /* ============ Health is Wealth watermark on auth pages ============ */
    .futh-login-wrapper .futh-login-card {
        box-shadow: 0 25px 50px -12px rgba(15, 81, 50, 0.25) !important;
        border: 1px solid var(--futh-primary-border) !important;
        transition: all 0.3s ease !important;
    }

    .futh-login-wrapper .futh-login-card:hover {
        box-shadow: 0 30px 60px -15px rgba(15, 81, 50, 0.35) !important;
        transform: translateY(-2px);
    }

    /* ============ Dashboard layout ============ */
    .fi-dashboard-grid {
        gap: 1rem !important;
    }

    /* ============ Section dividers — subtle green dotted line ============ */
    .fi-section + .fi-section,
    .fi-card + .fi-card {
        margin-top: 1rem !important;
    }
</style>

<style>
    /* ============ Print button — modern styling ============ */
    .futh-print-btn {
        background: linear-gradient(135deg, #16a34a 0%, #0f5132 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        text-decoration: none;
        box-shadow: 0 2px 4px rgba(15, 81, 50, 0.2);
        transition: all 0.2s ease;
    }

    .futh-print-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(15, 81, 50, 0.3);
    }
</style>
