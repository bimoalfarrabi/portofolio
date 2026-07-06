import React from 'react';
import { createRoot } from 'react-dom/client';
import App from './components/App';
import NasaCursor from './components/shared/NasaCursor';
import BrandIcon from './components/shared/BrandIcon';
import './bootstrap';

// Public portfolio SPA
const appRoot = document.getElementById('app');
if (appRoot) {
    createRoot(appRoot).render(<App />);
}

// Standalone cursor for non-SPA pages (e.g. login)
const cursorRoot = document.getElementById('nasa-cursor-root');
if (cursorRoot) {
    createRoot(cursorRoot).render(<NasaCursor />);
}

// Admin CMS: live brand-icon previews (Blade-driven via data attributes)
function mountBrandIconPreviews() {
    const nodes = document.querySelectorAll('[data-brand-icon-preview]');
    nodes.forEach((node) => {
        if (node.dataset.brandIconMounted === 'true') return;
        node.dataset.brandIconMounted = 'true';

        const root = createRoot(node);
        const render = () => {
            const name = node.dataset.brandIconValue || node.dataset.brandIconFallback || '';
            root.render(<BrandIcon name={name} className="size-6 text-ink" />);
        };
        render();

        // Re-render when the linked <select> changes
        const sourceId = node.dataset.brandIconSource;
        if (sourceId) {
            const source = document.getElementById(sourceId);
            const fallbackId = node.dataset.brandIconFallbackSource;
            const fallback = fallbackId ? document.getElementById(fallbackId) : null;

            const update = () => {
                node.dataset.brandIconValue = source.value || '';
                if (fallback) node.dataset.brandIconFallback = fallback.value || '';
                render();
            };
            source.addEventListener('change', update);
            if (fallback) fallback.addEventListener('input', update);
        }
    });
}

if (document.querySelector('[data-brand-icon-preview]')) {
    mountBrandIconPreviews();
}
