@extends('layouts.app')

@section('title', 'Beranda')

@push('styles')
<style>
    /* ── Landing Page Styles ── */
    html { scroll-behavior: smooth; }

    .landing-body {
        background-color: var(--background);
        color: var(--on-surface);
        min-height: 100vh;
    }

    /* ── Navbar ── */
    .landing-nav {
        position: sticky;
        top: 0;
        z-index: 40;
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid var(--outline);
    }
    .landing-nav-inner {
        max-width: 1120px;
        margin: 0 auto;
        padding: 0.875rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .landing-logo {
        display: flex;
        align-items: center;
        gap: 0.625rem;
        text-decoration: none;
    }
    .landing-logo-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: var(--primary);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 16px;
    }
    .landing-logo-text {
        font-weight: 700;
        font-size: 16px;
        color: var(--primary);
        letter-spacing: -0.01em;
    }
    .landing-nav-links {
        display: flex;
        align-items: center;
        gap: 2rem;
        list-style: none;
    }
    .landing-nav-links a {
        font-size: 13px;
        font-weight: 500;
        color: var(--on-surface-variant);
        text-decoration: none;
        transition: color 0.2s;
    }
    .landing-nav-links a:hover {
        color: var(--primary);
    }
    .landing-nav-cta {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.5rem 1.25rem;
        border-radius: var(--radius);
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }
    .landing-nav-cta-primary {
        background: var(--primary);
        color: #fff;
    }
    .landing-nav-cta-primary:hover {
        background: var(--primary-container);
        color: #fff;
    }
    .landing-nav-cta-outline {
        border: 1px solid var(--outline);
        color: var(--on-surface);
        background: transparent;
    }
    .landing-nav-cta-outline:hover {
        border-color: var(--primary);
        color: var(--primary);
    }

    /* ── Mobile Nav ── */
    .mobile-menu-btn {
        display: none;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0.25rem;
        color: var(--on-surface-variant);
    }
    .mobile-menu {
        display: none;
        padding: 0.75rem 1.5rem 1rem;
        border-top: 1px solid var(--outline);
        background: #fff;
    }
    .mobile-menu a {
        display: block;
        padding: 0.5rem 0;
        font-size: 13px;
        font-weight: 500;
        color: var(--on-surface-variant);
        text-decoration: none;
    }
    .mobile-menu a:hover { color: var(--primary); }

    @media (max-width: 768px) {
        .landing-nav-links { display: none; }
        .mobile-menu-btn { display: block; }
        .mobile-menu.is-open { display: block; }
    }

    /* ── Container ── */
    .landing-container {
        max-width: 1120px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    /* ── Hero ── */
    .hero {
        padding: 4rem 0 3.5rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3.5rem;
        align-items: center;
    }
    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.375rem 0.875rem;
        border-radius: 100px;
        background: rgba(0, 35, 111, 0.06);
        color: var(--primary);
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 1.25rem;
    }
    .hero-badge-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--secondary);
    }
    .hero h1 {
        font-size: 2.75rem;
        font-weight: 700;
        line-height: 1.15;
        color: var(--on-surface);
        letter-spacing: -0.02em;
        margin-bottom: 1rem;
    }
    .hero h1 span {
        color: var(--primary);
    }
    .hero-sub {
        font-size: 15px;
        line-height: 1.7;
        color: var(--on-surface-variant);
        max-width: 480px;
        margin-bottom: 2rem;
    }
    .hero-actions {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 2.5rem;
    }
    .hero-actions .btn {
        width: auto;
        padding: 0.75rem 1.75rem;
        font-size: 14px;
        border-radius: var(--radius);
        text-decoration: none;
    }
    .btn-outline {
        background: transparent;
        border: 1px solid var(--outline);
        color: var(--on-surface);
    }
    .btn-outline:hover {
        border-color: var(--primary);
        color: var(--primary);
    }

    /* Stats Row */
    .hero-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        border-top: 1px solid var(--outline);
        padding-top: 1.5rem;
    }
    .hero-stat {
        padding: 0;
    }
    .hero-stat-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--secondary);
        margin-bottom: 0.25rem;
    }
    .hero-stat-value {
        font-size: 18px;
        font-weight: 700;
        color: var(--on-surface);
    }
    .hero-stat-desc {
        font-size: 11px;
        color: var(--on-surface-variant);
        margin-top: 0.125rem;
    }

    /* Hero Mockup Card */
    .hero-mockup {
        background: var(--surface);
        border: 1px solid var(--outline);
        border-radius: var(--radius-lg);
        box-shadow: 0 4px 24px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    .mockup-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.875rem 1.25rem;
        border-bottom: 1px solid var(--outline);
        background: #f8fafc;
    }
    .mockup-dots {
        display: flex;
        gap: 6px;
    }
    .mockup-dots span {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }
    .mockup-dots span:nth-child(1) { background: #f87171; }
    .mockup-dots span:nth-child(2) { background: #fbbf24; }
    .mockup-dots span:nth-child(3) { background: #34d399; }
    .mockup-title {
        font-size: 11px;
        font-weight: 500;
        color: var(--on-surface-variant);
    }
    .mockup-body {
        padding: 1.25rem;
    }
    .mockup-role-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }
    .mockup-role-card {
        background: var(--background);
        border: 1px solid var(--outline);
        border-radius: var(--radius);
        padding: 1rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .mockup-role-card:hover {
        border-color: var(--primary);
        box-shadow: 0 2px 8px rgba(0, 35, 111, 0.08);
    }
    .mockup-role-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.625rem;
    }
    .mockup-role-icon svg {
        width: 16px;
        height: 16px;
    }
    .mockup-role-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--on-surface);
        margin-bottom: 0.25rem;
    }
    .mockup-role-desc {
        font-size: 11px;
        color: var(--on-surface-variant);
        line-height: 1.5;
    }
    .mockup-sidebar {
        background: var(--primary);
        border-radius: var(--radius) 0 0 var(--radius);
        padding: 1.25rem 1rem;
        color: #fff;
    }
    .mockup-sidebar-brand {
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }
    .mockup-sidebar-sub {
        font-size: 10px;
        color: rgba(255,255,255,0.55);
        margin-bottom: 1.25rem;
    }
    .mockup-sidebar-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.625rem;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        color: rgba(255,255,255,0.7);
        margin-bottom: 0.25rem;
    }
    .mockup-sidebar-item.active {
        background: rgba(255,255,255,0.12);
        color: #fff;
    }
    .mockup-sidebar-item svg {
        width: 14px;
        height: 14px;
    }
    .mockup-main-area {
        padding: 1.25rem;
        flex: 1;
    }
    .mockup-main-header {
        font-size: 11px;
        font-weight: 600;
        color: var(--on-surface-variant);
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 1rem;
    }
    .mockup-layout {
        display: flex;
        border-radius: var(--radius);
        border: 1px solid var(--outline);
        overflow: hidden;
        background: var(--surface);
    }

    @media (max-width: 768px) {
        .hero {
            grid-template-columns: 1fr;
            padding: 2.5rem 0 2rem;
            gap: 2rem;
        }
        .hero h1 { font-size: 2rem; }
        .hero-stats { grid-template-columns: 1fr; gap: 0.75rem; }
        .hero-actions { flex-direction: column; }
        .hero-actions .btn { width: 100%; }
    }

    /* ── Section Styling ── */
    .landing-section {
        padding: 4rem 0;
    }
    .landing-section-header {
        text-align: center;
        max-width: 560px;
        margin: 0 auto 2.5rem;
    }
    .section-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--secondary);
        margin-bottom: 0.5rem;
    }
    .section-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--on-surface);
        letter-spacing: -0.02em;
        margin-bottom: 0.625rem;
    }
    .section-desc {
        font-size: 14px;
        color: var(--on-surface-variant);
        line-height: 1.7;
    }

    /* ── About Section ── */
    .about-section {
        background: var(--surface);
        border: 1px solid var(--outline);
        border-radius: var(--radius-lg);
        padding: 3rem;
    }
    .about-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        align-items: center;
    }
    .about-text h2 {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--on-surface);
        letter-spacing: -0.01em;
        margin-bottom: 1rem;
    }
    .about-text p {
        font-size: 14px;
        color: var(--on-surface-variant);
        line-height: 1.8;
        margin-bottom: 0.75rem;
    }
    .about-cards {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    .about-card {
        background: var(--background);
        border: 1px solid var(--outline);
        border-radius: var(--radius-lg);
        padding: 1.25rem;
        transition: border-color 0.2s;
    }
    .about-card:hover {
        border-color: var(--primary);
    }
    .about-card-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.75rem;
    }
    .about-card-icon svg {
        width: 20px;
        height: 20px;
    }
    .about-card h4 {
        font-size: 14px;
        font-weight: 600;
        color: var(--on-surface);
        margin-bottom: 0.375rem;
    }
    .about-card p {
        font-size: 12px;
        color: var(--on-surface-variant);
        line-height: 1.6;
    }

    @media (max-width: 768px) {
        .about-section { padding: 2rem 1.25rem; }
        .about-grid { grid-template-columns: 1fr; gap: 2rem; }
        .about-cards { grid-template-columns: 1fr; }
    }

    /* ── Feature Cards ── */
    .features-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
    }
    .feature-card {
        background: var(--surface);
        border: 1px solid var(--outline);
        border-radius: var(--radius-lg);
        padding: 1.75rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .feature-card:hover {
        border-color: var(--primary);
        box-shadow: 0 4px 16px rgba(0, 35, 111, 0.06);
    }
    .feature-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.25rem;
    }
    .feature-icon svg {
        width: 22px;
        height: 22px;
    }
    .feature-card h3 {
        font-size: 16px;
        font-weight: 600;
        color: var(--on-surface);
        margin-bottom: 0.5rem;
    }
    .feature-card p {
        font-size: 13px;
        color: var(--on-surface-variant);
        line-height: 1.7;
        margin-bottom: 1rem;
    }
    .feature-bullets {
        list-style: none;
        padding: 0;
    }
    .feature-bullets li {
        font-size: 12px;
        color: var(--on-surface-variant);
        padding: 0.25rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .feature-bullet-dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    @media (max-width: 768px) {
        .features-grid { grid-template-columns: 1fr; }
    }

    /* ── Simulator Section ── */
    .simulator-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        justify-content: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--outline);
    }
    .sim-tab {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        border-radius: var(--radius);
        border: 1px solid transparent;
        font-size: 13px;
        font-weight: 600;
        color: var(--on-surface-variant);
        background: transparent;
        cursor: pointer;
        transition: all 0.2s;
        font-family: 'Inter', sans-serif;
    }
    .sim-tab:hover {
        background: var(--background);
        color: var(--on-surface);
    }
    .sim-tab.active {
        background: var(--primary);
        color: #fff;
        border-color: var(--primary);
    }
    .sim-tab svg {
        width: 16px;
        height: 16px;
    }

    .simulator-panel {
        background: var(--surface);
        border: 1px solid var(--outline);
        border-radius: var(--radius-lg);
        padding: 2rem;
        box-shadow: 0 4px 24px rgba(0,0,0,0.04);
    }
    .simulator-grid {
        display: grid;
        grid-template-columns: 1fr 1.3fr;
        gap: 2.5rem;
    }
    .sim-overview h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--on-surface);
        margin: 0.75rem 0;
    }
    .sim-role-badge {
        display: inline-flex;
        padding: 0.25rem 0.75rem;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        background: rgba(0, 35, 111, 0.08);
        color: var(--primary);
    }
    .sim-desc {
        font-size: 13px;
        color: var(--on-surface-variant);
        line-height: 1.7;
        margin-bottom: 1.5rem;
    }
    .sim-actions-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--on-surface-variant);
        margin-bottom: 0.5rem;
    }
    .sim-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .sim-action-pill {
        padding: 0.5rem 0.875rem;
        border-radius: var(--radius);
        font-size: 12px;
        font-weight: 600;
        background: var(--background);
        color: var(--on-surface);
        border: 1px solid var(--outline);
    }

    /* Simulated Dashboard Panel */
    .sim-screen {
        background: var(--background);
        border: 1px solid var(--outline);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        transition: opacity 0.2s;
    }
    .sim-screen-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 0.875rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid var(--outline);
    }
    .sim-screen-header-left {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .sim-screen-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--secondary);
    }
    .sim-screen-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--on-surface-variant);
    }
    .sim-screen-tag {
        font-size: 10px;
        color: var(--on-surface-variant);
    }
    .sim-widgets-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }
    .sim-widget {
        background: var(--surface);
        border: 1px solid var(--outline);
        border-radius: var(--radius);
        padding: 0.875rem;
    }
    .sim-widget-label {
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--on-surface-variant);
    }
    .sim-widget-value {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary);
        margin-top: 0.25rem;
    }
    .sim-widget-value.warning { color: #d97706; }
    .sim-widget-value.success { color: var(--secondary); }
    .sim-widget-value.danger { color: var(--error); }

    .sim-log-title {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--on-surface-variant);
        margin-bottom: 0.5rem;
    }
    .sim-log-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.625rem 0.75rem;
        border-radius: var(--radius);
        background: var(--surface);
        border: 1px solid var(--outline);
        margin-bottom: 0.375rem;
        font-size: 12px;
    }
    .sim-log-text { color: var(--on-surface); }
    .sim-log-time { color: var(--on-surface-variant); font-size: 11px; white-space: nowrap; margin-left: 1rem; }

    .sim-log-badge {
        font-size: 10px;
        font-weight: 600;
        padding: 0.2rem 0.5rem;
        border-radius: 100px;
        white-space: nowrap;
        margin-left: 0.75rem;
    }
    .sim-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }
    .sim-table thead { background: var(--surface); }
    .sim-table th {
        text-align: left;
        padding: 0.5rem 0.625rem;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--on-surface-variant);
        border-bottom: 1px solid var(--outline);
    }
    .sim-table td {
        padding: 0.5rem 0.625rem;
        border-bottom: 1px solid var(--outline);
        color: var(--on-surface);
    }
    .sim-table-btn {
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 700;
        border: none;
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .simulator-grid { grid-template-columns: 1fr; }
        .sim-widgets-row { grid-template-columns: 1fr; }
        .simulator-panel { padding: 1.25rem; }
    }

    /* ── FAQ ── */
    .faq-list {
        max-width: 720px;
        margin: 0 auto;
    }
    .faq-item {
        background: var(--surface);
        border: 1px solid var(--outline);
        border-radius: var(--radius-lg);
        margin-bottom: 0.75rem;
        transition: border-color 0.2s;
    }
    .faq-item:hover {
        border-color: #a0a0b8;
    }
    .faq-btn {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.125rem 1.25rem;
        background: none;
        border: none;
        cursor: pointer;
        text-align: left;
        font-family: 'Inter', sans-serif;
    }
    .faq-btn-text {
        font-size: 14px;
        font-weight: 600;
        color: var(--on-surface);
    }
    .faq-chevron {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--background);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-left: 1rem;
        transition: transform 0.3s, background 0.2s;
    }
    .faq-chevron svg {
        width: 14px;
        height: 14px;
        color: var(--on-surface-variant);
    }
    .faq-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
        padding: 0 1.25rem;
    }
    .faq-content p {
        padding-bottom: 1.125rem;
        font-size: 13px;
        color: var(--on-surface-variant);
        line-height: 1.8;
    }

    /* ── CTA Banner ── */
    .cta-banner {
        background: var(--primary);
        border-radius: var(--radius-lg);
        padding: 3rem;
        text-align: center;
        color: #fff;
    }
    .cta-banner h2 {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
    }
    .cta-banner p {
        font-size: 14px;
        color: rgba(255,255,255,0.75);
        max-width: 520px;
        margin: 0 auto 1.5rem;
        line-height: 1.7;
    }
    .cta-banner-actions {
        display: flex;
        gap: 0.75rem;
        justify-content: center;
        flex-wrap: wrap;
    }
    .cta-btn-white {
        display: inline-flex;
        align-items: center;
        padding: 0.75rem 1.75rem;
        border-radius: var(--radius);
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        background: #fff;
        color: var(--primary);
    }
    .cta-btn-white:hover {
        background: #e8edf5;
        color: var(--primary);
    }
    .cta-btn-outline-white {
        display: inline-flex;
        align-items: center;
        padding: 0.75rem 1.75rem;
        border-radius: var(--radius);
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        background: transparent;
        border: 1px solid rgba(255,255,255,0.3);
        color: #fff;
    }
    .cta-btn-outline-white:hover {
        border-color: rgba(255,255,255,0.6);
        background: rgba(255,255,255,0.05);
        color: #fff;
    }

    /* ── Footer ── */
    .landing-footer {
        border-top: 1px solid var(--outline);
        padding: 2.5rem 0 2rem;
        margin-top: 4rem;
    }
    .footer-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr 1fr;
        gap: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid var(--outline);
    }
    .footer-brand-text {
        font-size: 12px;
        color: var(--on-surface-variant);
        line-height: 1.7;
        margin-top: 0.75rem;
        max-width: 280px;
    }
    .footer-col h4 {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--on-surface-variant);
        margin-bottom: 0.75rem;
    }
    .footer-col ul {
        list-style: none;
        padding: 0;
    }
    .footer-col li {
        margin-bottom: 0.375rem;
    }
    .footer-col a {
        font-size: 13px;
        color: var(--on-surface-variant);
        text-decoration: none;
        transition: color 0.2s;
    }
    .footer-col a:hover { color: var(--primary); }
    .footer-col li.footer-contact-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 12px;
        color: var(--on-surface-variant);
        margin-bottom: 0.5rem;
    }
    .footer-contact-item svg {
        width: 14px;
        height: 14px;
        color: var(--primary);
        flex-shrink: 0;
    }
    .footer-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1.5rem;
        font-size: 11px;
        color: var(--on-surface-variant);
    }

    @media (max-width: 768px) {
        .footer-grid { grid-template-columns: 1fr; }
        .footer-bottom { flex-direction: column; gap: 0.5rem; text-align: center; }
    }
</style>
@endpush

@section('content')
<div class="landing-body">

    <!-- ── Navbar ── -->
    <header class="landing-nav">
        <div class="landing-nav-inner">
            <a href="{{ url('/') }}" class="landing-logo">
                <span class="landing-logo-icon">A</span>
                <span class="landing-logo-text">AKADEMIX</span>
            </a>

            <nav class="landing-nav-links">
                <a href="#about">Tentang</a>
                <a href="#features">Fitur</a>
                <a href="#simulator">Simulator</a>
                <a href="#faq">FAQ</a>
                @if(Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}" class="landing-nav-cta landing-nav-cta-primary">Dashboard →</a>
                    @else
                        <a href="{{ route('login') }}" class="landing-nav-cta landing-nav-cta-primary">Masuk</a>
                    @endauth
                @endif
            </nav>

            <button type="button" class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Menu">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
        <div class="mobile-menu" id="mobileMenu">
            <a href="#about">Tentang</a>
            <a href="#features">Fitur</a>
            <a href="#simulator">Simulator</a>
            <a href="#faq">FAQ</a>
            @if(Route::has('login'))
                @auth
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                @else
                    <a href="{{ route('login') }}">Masuk</a>
                @endauth
            @endif
        </div>
    </header>

    <main class="landing-container">

        <!-- ── Hero ── -->
        <section class="hero">
            <div>
                <div class="hero-badge">
                    <span class="hero-badge-dot"></span>
                    Sistem Informasi Akademik
                </div>
                <h1>Kelola Sekolah<br>Lebih Rapi &amp; <span>Profesional</span>.</h1>
                <p class="hero-sub">
                    AKADEMIX menyatukan Guru, Siswa, Orang Tua, dan Administrator dalam satu portal terpadu. Desain yang intuitif dan hak akses berlapis membantu operasional sekolah berjalan tanpa hambatan.
                </p>
                <div class="hero-actions">
                    <a href="{{ route('login') }}" class="btn btn-primary" style="width:auto;">Masuk ke Portal →</a>
                    <a href="#features" class="btn btn-outline" style="width:auto;">Pelajari Fitur</a>
                </div>
                <div class="hero-stats">
                    <div class="hero-stat">
                        <div class="hero-stat-label">Multi-Role</div>
                        <div class="hero-stat-value">4 Portal</div>
                        <div class="hero-stat-desc">Akses khusus per peran</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-label">Real-Time</div>
                        <div class="hero-stat-value">Instan</div>
                        <div class="hero-stat-desc">Nilai &amp; absensi langsung</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-label">Keamanan</div>
                        <div class="hero-stat-value">Terproteksi</div>
                        <div class="hero-stat-desc">Hak akses berlapis</div>
                    </div>
                </div>
            </div>

            <!-- ── Dashboard Preview ── -->
            <div class="hero-mockup">
                <div class="mockup-header">
                    <div class="mockup-dots">
                        <span></span><span></span><span></span>
                    </div>
                    <span class="mockup-title">AKADEMIX</span>
                </div>
                <div class="mockup-body">
                    <!-- Mini Dashboard Layout Preview -->
                    <div class="mockup-layout">
                        <div class="mockup-sidebar">
                            <div class="mockup-sidebar-brand">AKADEMIX</div>
                            <div class="mockup-sidebar-sub">Portal Institusi</div>
                            <div class="mockup-sidebar-item active">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                Dashboard
                            </div>
                            <div class="mockup-sidebar-item">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                Data Siswa
                            </div>
                            <div class="mockup-sidebar-item">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Nilai & Rapor
                            </div>
                            <div class="mockup-sidebar-item">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Jadwal
                            </div>
                        </div>
                        <div class="mockup-main-area">
                            <div class="mockup-main-header">Dashboard Admin</div>
                            <div class="mockup-role-grid">
                                <div class="mockup-role-card">
                                    <div class="mockup-role-icon" style="background:rgba(0,35,111,0.08);color:var(--primary);">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                                    </div>
                                    <div class="mockup-role-name">Administrator</div>
                                    <div class="mockup-role-desc">Kelola data, kelas, dan jadwal sekolah</div>
                                </div>
                                <div class="mockup-role-card">
                                    <div class="mockup-role-icon" style="background:rgba(0,106,97,0.08);color:var(--secondary);">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V9a2 2 0 012-2h2a2 2 0 012 2v9a2 2 0 01-2 2h-2z"/></svg>
                                    </div>
                                    <div class="mockup-role-name">Guru Pengajar</div>
                                    <div class="mockup-role-desc">Input nilai, materi, dan presensi</div>
                                </div>
                                <div class="mockup-role-card">
                                    <div class="mockup-role-icon" style="background:rgba(0,35,111,0.08);color:var(--primary);">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
                                    </div>
                                    <div class="mockup-role-name">Siswa</div>
                                    <div class="mockup-role-desc">Lihat nilai, unduh materi, kirim tugas</div>
                                </div>
                                <div class="mockup-role-card">
                                    <div class="mockup-role-icon" style="background:rgba(0,106,97,0.08);color:var(--secondary);">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    </div>
                                    <div class="mockup-role-name">Orang Tua</div>
                                    <div class="mockup-role-desc">Pantau kehadiran dan nilai anak</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ── About ── -->
        <section id="about" class="landing-section" style="scroll-margin-top:80px;">
            <div class="about-section">
                <div class="about-grid">
                    <div class="about-text">
                        <div class="section-label">Tentang Akademix</div>
                        <h2>Ekosistem Pendidikan yang Transparan &amp; Adaptif.</h2>
                        <p>Kami percaya pengelolaan akademik yang baik tidak seharusnya rumit. AKADEMIX merampingkan alur birokrasi sekolah — dari pencatatan harian hingga laporan kelulusan.</p>
                        <p>Dengan menghubungkan seluruh pihak dalam satu wadah digital, kolaborasi guru, siswa, dan orang tua menjadi lebih mudah diakses di mana saja.</p>
                    </div>
                    <div class="about-cards">
                        <div class="about-card">
                            <div class="about-card-icon" style="background:rgba(0,106,97,0.08);color:var(--secondary);">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <h4>Ringan &amp; Cepat</h4>
                            <p>Pemuatan halaman cepat bahkan pada koneksi seluler lambat.</p>
                        </div>
                        <div class="about-card">
                            <div class="about-card-icon" style="background:rgba(0,35,111,0.06);color:var(--primary);">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <h4>Privasi Terjaga</h4>
                            <p>Siswa hanya melihat nilainya sendiri. Data pribadi dilindungi ketat.</p>
                        </div>
                        <div class="about-card">
                            <div class="about-card-icon" style="background:rgba(0,106,97,0.08);color:var(--secondary);">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h4>Data Presisi</h4>
                            <p>Perhitungan kehadiran dan rata-rata nilai rapor yang akurat.</p>
                        </div>
                        <div class="about-card">
                            <div class="about-card-icon" style="background:rgba(0,35,111,0.06);color:var(--primary);">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <h4>Portal Wali</h4>
                            <p>Orang tua memantau kehadiran dan tugas anak secara mandiri.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ── Features ── -->
        <section id="features" class="landing-section" style="scroll-margin-top:80px;">
            <div class="landing-section-header">
                <div class="section-label">Fitur Utama</div>
                <h2 class="section-title">Modul Akademik Siap Pakai</h2>
                <p class="section-desc">Fitur modern yang disesuaikan dengan kebutuhan administrasi sekolah.</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon" style="background:rgba(0,35,111,0.06);color:var(--primary);">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3>Manajemen Nilai</h3>
                    <p>Guru menginput nilai harian, UTS, dan UAS. Sistem merangkum data menjadi laporan siap cetak.</p>
                    <ul class="feature-bullets">
                        <li><span class="feature-bullet-dot" style="background:var(--primary);"></span> Input nilai per mata pelajaran</li>
                        <li><span class="feature-bullet-dot" style="background:var(--primary);"></span> Perhitungan nilai akhir otomatis</li>
                    </ul>
                </div>
                <div class="feature-card">
                    <div class="feature-icon" style="background:rgba(0,106,97,0.08);color:var(--secondary);">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h3>Presensi &amp; Jadwal</h3>
                    <p>Pencatatan kehadiran lengkap (Hadir, Sakit, Izin, Alfa). Jadwal pelajaran terintegrasi dalam profil siswa.</p>
                    <ul class="feature-bullets">
                        <li><span class="feature-bullet-dot" style="background:var(--secondary);"></span> Log absensi harian cepat</li>
                        <li><span class="feature-bullet-dot" style="background:var(--secondary);"></span> Sinkronisasi jadwal realtime</li>
                    </ul>
                </div>
                <div class="feature-card">
                    <div class="feature-icon" style="background:rgba(0,35,111,0.06);color:var(--primary);">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <h3>Materi &amp; Tugas</h3>
                    <p>Guru mengunggah materi belajar dan tugas mandiri. Siswa mengumpulkan berkas tugas langsung melalui sistem.</p>
                    <ul class="feature-bullets">
                        <li><span class="feature-bullet-dot" style="background:var(--primary);"></span> Distribusi dokumen tanpa batas</li>
                        <li><span class="feature-bullet-dot" style="background:var(--primary);"></span> Pelacakan status pengumpulan</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- ── Simulator ── -->
        <section id="simulator" class="landing-section" style="scroll-margin-top:80px;">
            <div class="landing-section-header">
                <div class="section-label">Simulator Dashboard</div>
                <h2 class="section-title">Satu Platform, Empat Portal Khusus</h2>
                <p class="section-desc">Pilih peran di bawah untuk melihat antarmuka dan aksi cepat yang tersedia.</p>
            </div>

            <div class="simulator-tabs">
                <button onclick="switchRole('admin')" id="tab-admin" class="sim-tab active">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                    Administrator
                </button>
                <button onclick="switchRole('teacher')" id="tab-teacher" class="sim-tab">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V9a2 2 0 012-2h2a2 2 0 012 2v9a2 2 0 01-2 2h-2z"/></svg>
                    Guru
                </button>
                <button onclick="switchRole('student')" id="tab-student" class="sim-tab">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
                    Siswa
                </button>
                <button onclick="switchRole('parent')" id="tab-parent" class="sim-tab">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Orang Tua
                </button>
            </div>

            <div class="simulator-panel">
                <div class="simulator-grid">
                    <div class="sim-overview">
                        <span id="role-badge" class="sim-role-badge">Administrator</span>
                        <h3 id="role-title">Pusat Kendali Data Sekolah</h3>
                        <p id="role-desc" class="sim-desc">Administrator memiliki otorisasi penuh untuk mengelola pendaftaran pengguna, mengarsipkan semester, mendistribusikan siswa ke kelas, serta menjadwalkan mata pelajaran.</p>
                        <div class="sim-actions-label">Aksi Cepat</div>
                        <div id="role-actions" class="sim-actions">
                            <span class="sim-action-pill">Atur Kalender Akademik</span>
                            <span class="sim-action-pill">Daftarkan Siswa &amp; Guru</span>
                            <span class="sim-action-pill">Alokasikan Kelas</span>
                        </div>
                    </div>

                    <div id="simulated-panel" class="sim-screen">
                        <div class="sim-screen-header">
                            <div class="sim-screen-header-left">
                                <span class="sim-screen-dot"></span>
                                <span id="panel-header" class="sim-screen-label">Admin — Dashboard</span>
                            </div>
                            <span class="sim-screen-tag">Akses Terotorisasi</span>
                        </div>
                        <div id="role-content-area">
                            <div class="sim-widgets-row">
                                <div class="sim-widget">
                                    <div class="sim-widget-label">Total Guru</div>
                                    <div class="sim-widget-value">32</div>
                                </div>
                                <div class="sim-widget">
                                    <div class="sim-widget-label">Total Siswa</div>
                                    <div class="sim-widget-value">420</div>
                                </div>
                                <div class="sim-widget">
                                    <div class="sim-widget-label">Kelas Aktif</div>
                                    <div class="sim-widget-value">12</div>
                                </div>
                            </div>
                            <div class="sim-log-title">Aktivitas Terakhir</div>
                            <div class="sim-log-item">
                                <span class="sim-log-text">Tahun Akademik 2026/2027 Ganjil Diaktifkan</span>
                                <span class="sim-log-time">10 mnt lalu</span>
                            </div>
                            <div class="sim-log-item">
                                <span class="sim-log-text">40 Siswa Baru ditetapkan ke Kelas X-A</span>
                                <span class="sim-log-time">1 jam lalu</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ── FAQ ── -->
        <section id="faq" class="landing-section" style="scroll-margin-top:80px;">
            <div class="landing-section-header">
                <div class="section-label">FAQ</div>
                <h2 class="section-title">Pertanyaan yang Sering Diajukan</h2>
                <p class="section-desc">Temukan jawaban atas pertanyaan umum seputar platform.</p>
            </div>
            <div class="faq-list">
                <div class="faq-item">
                    <button onclick="toggleFaq(1)" class="faq-btn" id="faq-btn-1" aria-expanded="false">
                        <span class="faq-btn-text">Bagaimana cara mendapatkan akun portal Akademix?</span>
                        <span class="faq-chevron" id="faq-icon-1">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </span>
                    </button>
                    <div class="faq-content" id="faq-content-1">
                        <p>Setiap akun didaftarkan oleh Administrator sekolah. Siswa login menggunakan NISN/Username dan password bawaan yang dibagikan oleh wali kelas.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <button onclick="toggleFaq(2)" class="faq-btn" id="faq-btn-2" aria-expanded="false">
                        <span class="faq-btn-text">Apakah orang tua dan siswa memiliki tampilan yang sama?</span>
                        <span class="faq-chevron" id="faq-icon-2">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </span>
                    </button>
                    <div class="faq-content" id="faq-content-2">
                        <p>Tidak. Dashboard siswa berfokus pada pengumpulan tugas dan nilai. Dashboard orang tua dirancang khusus untuk memantau perkembangan belajar, kehadiran, dan rekap absensi anak.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <button onclick="toggleFaq(3)" class="faq-btn" id="faq-btn-3" aria-expanded="false">
                        <span class="faq-btn-text">Apakah platform ini bisa diakses optimal di HP?</span>
                        <span class="faq-chevron" id="faq-icon-3">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </span>
                    </button>
                    <div class="faq-content" id="faq-content-3">
                        <p>Ya, seluruh antarmuka dirancang responsif. Guru dapat melakukan presensi langsung dari HP, dan siswa dapat mengirim tugas lewat browser ponsel.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ── CTA Banner ── -->
        <section id="contact" class="landing-section" style="scroll-margin-top:80px;">
            <div class="cta-banner">
                <div class="section-label" style="color:rgba(255,255,255,0.5);">Mulai Sekarang</div>
                <h2>Siap Meningkatkan Manajemen Akademik Sekolah?</h2>
                <p>Masuk sekarang untuk mengakses dashboard personal Anda. Administrator dapat langsung mengelola data guru, kelas, dan jadwal pelajaran.</p>
                <div class="cta-banner-actions">
                    <a href="{{ route('login') }}" class="cta-btn-white">Login Sekarang →</a>
                    <a href="mailto:admin@akademix.local" class="cta-btn-outline-white">Hubungi Admin</a>
                </div>
            </div>
        </section>
    </main>

    <!-- ── Footer ── -->
    <footer class="landing-footer">
        <div class="landing-container">
            <div class="footer-grid">
                <div>
                    <a href="{{ url('/') }}" class="landing-logo" style="margin-bottom:0.5rem;">
                        <span class="landing-logo-icon">A</span>
                        <span class="landing-logo-text">AKADEMIX</span>
                    </a>
                    <p class="footer-brand-text">Platform Sistem Informasi Akademik terintegrasi untuk sekolah modern Indonesia.</p>
                </div>
                <div class="footer-col">
                    <h4>Menu</h4>
                    <ul>
                        <li><a href="#about">Tentang</a></li>
                        <li><a href="#features">Fitur Utama</a></li>
                        <li><a href="#simulator">Simulator</a></li>
                        <li><a href="#faq">FAQ</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Kontak</h4>
                    <ul>
                        <li class="footer-contact-item">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            support@akademix.local
                        </li>
                        <li class="footer-contact-item">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Senin – Jumat, 08:00 – 16:00
                        </li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <span>&copy; {{ date('Y') }} AKADEMIX. Seluruh hak cipta dilindungi.</span>
                <span>Didesain untuk keunggulan akademik sekolah.</span>
            </div>
        </div>
    </footer>
</div>

<script>
// Mobile menu toggle
const mobileBtn = document.getElementById('mobileMenuBtn');
const mobileMenu = document.getElementById('mobileMenu');
if (mobileBtn && mobileMenu) {
    mobileBtn.addEventListener('click', () => mobileMenu.classList.toggle('is-open'));
    mobileMenu.querySelectorAll('a').forEach(a => a.addEventListener('click', () => mobileMenu.classList.remove('is-open')));
}

// Role Simulator Data
const roles = {
    admin: {
        badge: "Administrator",
        title: "Pusat Kendali Data Sekolah",
        desc: "Administrator memiliki otorisasi penuh untuk mengelola pendaftaran pengguna, mengarsipkan semester, mendistribusikan siswa ke kelas, serta menjadwalkan mata pelajaran.",
        actions: ["Atur Kalender Akademik", "Daftarkan Siswa & Guru", "Alokasikan Kelas"],
        panel: "Admin — Dashboard",
        html: '<div class="sim-widgets-row"><div class="sim-widget"><div class="sim-widget-label">Total Guru</div><div class="sim-widget-value">32</div></div><div class="sim-widget"><div class="sim-widget-label">Total Siswa</div><div class="sim-widget-value">420</div></div><div class="sim-widget"><div class="sim-widget-label">Kelas Aktif</div><div class="sim-widget-value">12</div></div></div><div class="sim-log-title">Aktivitas Terakhir</div><div class="sim-log-item"><span class="sim-log-text">Tahun Akademik 2026/2027 Ganjil Diaktifkan</span><span class="sim-log-time">10 mnt lalu</span></div><div class="sim-log-item"><span class="sim-log-text">40 Siswa Baru ditetapkan ke Kelas X-A</span><span class="sim-log-time">1 jam lalu</span></div>'
    },
    teacher: {
        badge: "Guru Pengajar",
        title: "Pengelolaan Nilai & Materi",
        desc: "Guru mengontrol kegiatan belajar — dari pembagian materi ajar, pembuatan tugas, hingga penginputan rekap absensi dan nilai siswa.",
        actions: ["Unggah Materi", "Input Nilai", "Rekap Presensi"],
        panel: "Guru — Dashboard",
        html: '<div class="sim-widgets-row"><div class="sim-widget"><div class="sim-widget-label">Kelas Diajar</div><div class="sim-widget-value">4</div></div><div class="sim-widget"><div class="sim-widget-label">Tugas Aktif</div><div class="sim-widget-value">6</div></div><div class="sim-widget"><div class="sim-widget-label">Perlu Dinilai</div><div class="sim-widget-value warning">12</div></div></div><div class="sim-log-title">Input Nilai (Kelas XI-A)</div><table class="sim-table"><thead><tr><th>Siswa</th><th>Tugas 1</th><th>Tugas 2</th><th>Status</th></tr></thead><tbody><tr><td>Budi Hermanto</td><td style="color:var(--primary);font-weight:600;">85</td><td style="color:var(--primary);font-weight:600;">90</td><td><span style="color:var(--secondary);font-weight:600;font-size:11px;">Tersimpan</span></td></tr><tr><td>Anggita Siregar</td><td style="color:var(--primary);font-weight:600;">88</td><td style="color:var(--on-surface-variant);">—</td><td><button class="sim-table-btn" style="background:var(--primary);color:#fff;">Input</button></td></tr></tbody></table>'
    },
    student: {
        badge: "Siswa",
        title: "Pantau Tugas & Hasil Belajar",
        desc: "Siswa dapat melihat rekapitulasi nilai secara transparan dan mengunggah tugas sekolah tepat waktu untuk menghindari keterlambatan.",
        actions: ["Unduh Modul", "Unggah Tugas", "Lihat Rapor"],
        panel: "Siswa — Dashboard",
        html: '<div class="sim-widgets-row"><div class="sim-widget"><div class="sim-widget-label">Tugas Aktif</div><div class="sim-widget-value warning">2</div></div><div class="sim-widget"><div class="sim-widget-label">Rata-Rata Nilai</div><div class="sim-widget-value">88.5</div></div><div class="sim-widget"><div class="sim-widget-label">Kehadiran</div><div class="sim-widget-value success">98%</div></div></div><div class="sim-log-title">Tugas Terbaru</div><div class="sim-log-item"><div><div class="sim-log-text" style="font-weight:600;">Latihan Aljabar Linier</div><div style="font-size:10px;color:var(--on-surface-variant);">Matematika — Guru: Budi W.</div></div><span class="sim-log-badge" style="background:#fee2e2;color:#991b1b;">Sisa 2 Jam</span></div><div class="sim-log-item"><div><div class="sim-log-text" style="font-weight:600;">Essay English Grammar</div><div style="font-size:10px;color:var(--on-surface-variant);">B. Inggris — Guru: Marni L.</div></div><span class="sim-log-badge" style="background:#d1fae5;color:#065f46;">Sudah Dikirim</span></div>'
    },
    parent: {
        badge: "Orang Tua",
        title: "Pengawasan Perkembangan Anak",
        desc: "Akses eksklusif bagi wali siswa untuk memantau kehadiran harian, ketepatan pengumpulan tugas, dan nilai rapor secara transparan.",
        actions: ["Pantau Kehadiran", "Lihat Nilai Rapor", "Kirim Surat Izin"],
        panel: "Orang Tua — Dashboard",
        html: '<div class="sim-widgets-row"><div class="sim-widget"><div class="sim-widget-label">Kehadiran Anak</div><div class="sim-widget-value success">99%</div></div><div class="sim-widget"><div class="sim-widget-label">Catatan Izin/Sakit</div><div class="sim-widget-value">1 Hari</div></div><div class="sim-widget"><div class="sim-widget-label">Tugas Belum Selesai</div><div class="sim-widget-value danger">1</div></div></div><div class="sim-log-title">Aktivitas Anak (Budi Hermanto)</div><div class="sim-log-item"><span class="sim-log-text">Tugas Fisika "Hukum Newton" diserahkan</span><span class="sim-log-time">Hari ini, 13:45</span></div><div class="sim-log-item"><span class="sim-log-text">Absensi Matematika: Hadir tepat waktu</span><span class="sim-log-time">Hari ini, 08:15</span></div>'
    }
};

function switchRole(key) {
    document.querySelectorAll('.sim-tab').forEach(t => t.classList.remove('active'));
    document.getElementById('tab-' + key).classList.add('active');

    const panel = document.getElementById('simulated-panel');
    panel.style.opacity = '0.4';

    setTimeout(() => {
        const d = roles[key];
        document.getElementById('role-badge').textContent = d.badge;
        document.getElementById('role-title').textContent = d.title;
        document.getElementById('role-desc').textContent = d.desc;
        document.getElementById('panel-header').textContent = d.panel;

        const ac = document.getElementById('role-actions');
        ac.innerHTML = '';
        d.actions.forEach(a => {
            const s = document.createElement('span');
            s.className = 'sim-action-pill';
            s.textContent = a;
            ac.appendChild(s);
        });

        document.getElementById('role-content-area').innerHTML = d.html;
        panel.style.opacity = '1';
    }, 150);
}

// FAQ Accordion
function toggleFaq(i) {
    const btn = document.getElementById('faq-btn-' + i);
    const content = document.getElementById('faq-content-' + i);
    const icon = document.getElementById('faq-icon-' + i);
    if (!btn || !content || !icon) return;

    const open = btn.getAttribute('aria-expanded') === 'true';
    btn.setAttribute('aria-expanded', !open);

    if (!open) {
        content.style.maxHeight = content.scrollHeight + 'px';
        icon.style.transform = 'rotate(180deg)';
    } else {
        content.style.maxHeight = '0';
        icon.style.transform = 'rotate(0)';
    }
}
</script>
@endsection