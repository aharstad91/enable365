/**
 * E365 Video Modal Handler
 *
 * Handles all e365-video blocks on the page with a single set of event listeners.
 * Uses opacity/visibility for iOS Safari compatibility (not display: none).
 */
(function() {
    'use strict';

    let currentModal = null;
    let currentIframe = null;
    let scrollPosition = 0;

    /**
     * Open a video modal
     */
    function openModal(wrapper) {
        const blockId = wrapper.closest('.e365-video').id;
        const modal = document.getElementById(blockId + '-modal');
        if (!modal) return;

        const iframe = modal.querySelector('iframe');
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

        // Show modal using class toggle (iOS Safari compatible)
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';

        // Track current modal
        currentModal = modal;
        currentIframe = iframe;
    }

    /**
     * Close the currently open modal
     */
    function closeModal() {
        if (!currentModal) return;

        // Hide modal using class toggle
        currentModal.classList.remove('active');

        // Clear iframe
        if (currentIframe) {
            currentIframe.src = '';
        }

        // Restore scroll
        document.body.style.overflow = '';
        window.scrollTo(0, scrollPosition);

        currentModal = null;
        currentIframe = null;
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
        const modal = e.target.closest('.e365-video__modal');
        if (modal && e.target === modal) {
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
            if (e.key === 'Escape' && currentModal) {
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
