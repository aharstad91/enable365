/**
 * E365 Video Modal Handler
 *
 * Creates a single shared modal overlay appended to body (iOS Safari compatible).
 * This approach avoids position:fixed issues when modal is nested in scrollable containers.
 */
(function() {
    'use strict';

    let overlay = null;
    let iframe = null;
    let scrollPosition = 0;

    /**
     * Create the shared modal overlay (once)
     */
    function createOverlay() {
        if (overlay) return;

        overlay = document.createElement('div');
        overlay.className = 'e365-video__modal';
        overlay.innerHTML = `
            <div class="e365-video__modal-content">
                <iframe class="e365-video__iframe" src="" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
                <div class="e365-video__close-wrapper">
                    <button class="e365-video__close" aria-label="Close video">Close Video</button>
                </div>
            </div>
        `;
        document.body.appendChild(overlay);
        iframe = overlay.querySelector('iframe');
    }

    /**
     * Open the video modal
     */
    function openModal(wrapper) {
        createOverlay();

        const videoUrl = wrapper.dataset.videoUrl;
        if (!videoUrl) return;

        // Build video URL with parameters for better mobile experience
        const params = new URLSearchParams({
            showinfo: '0',
            cc_load_policy: '1',
            cc_lang_pref: 'en',
            enablejsapi: '1',
            origin: window.location.origin,
            autoplay: '1',
            playsinline: '1'
        });

        const separator = videoUrl.includes('?') ? '&' : '?';
        iframe.src = videoUrl + separator + params.toString();

        // Save scroll position for iOS
        scrollPosition = window.scrollY;

        // Show modal
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    /**
     * Close the video modal
     */
    function closeModal() {
        if (!overlay) return;

        // Hide modal
        overlay.classList.remove('active');

        // Clear iframe
        if (iframe) {
            iframe.src = '';
        }

        // Restore scroll
        document.body.style.overflow = '';
        window.scrollTo(0, scrollPosition);
    }

    /**
     * Handle interaction (click or touch)
     */
    function handleInteraction(e) {
        // Open modal when clicking video wrapper or play button
        const wrapper = e.target.closest('.e365-video__wrapper');
        if (wrapper) {
            e.preventDefault();
            openModal(wrapper);
            return;
        }

        // Close button click
        const closeBtn = e.target.closest('.e365-video__close');
        if (closeBtn) {
            closeModal();
            return;
        }

        // Click on modal backdrop (not content)
        if (overlay && e.target === overlay) {
            closeModal();
        }
    }

    /**
     * Initialize event listeners using delegation
     */
    function init() {
        // Click handler
        document.addEventListener('click', handleInteraction);

        // Escape key to close modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && overlay && overlay.classList.contains('active')) {
                closeModal();
            }
        });
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
