/**
 * E365 Video Modal Handler
 *
 * Handles all e365-video blocks on the page with a single set of event listeners.
 * Uses event delegation for efficiency.
 */
(function() {
    'use strict';

    let currentModal = null;
    let currentIframe = null;

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

        // Set iframe src with autoplay and playsinline for iOS
        const separator = videoUrl.includes('?') ? '&' : '?';
        iframe.src = videoUrl + separator + 'autoplay=1&playsinline=1';

        // Show modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        // iOS Safari fix: prevent body scroll
        document.body.style.overflow = 'hidden';
        document.body.style.position = 'fixed';
        document.body.style.width = '100%';
        document.body.style.top = `-${window.scrollY}px`;

        // Track current modal for Escape key
        currentModal = modal;
        currentIframe = iframe;
    }

    /**
     * Close the currently open modal
     */
    function closeModal() {
        if (!currentModal) return;

        currentModal.classList.add('hidden');
        currentModal.classList.remove('flex');

        if (currentIframe) {
            currentIframe.src = '';
        }

        // iOS Safari fix: restore body scroll position
        const scrollY = document.body.style.top;
        document.body.style.overflow = '';
        document.body.style.position = '';
        document.body.style.width = '';
        document.body.style.top = '';
        window.scrollTo(0, parseInt(scrollY || '0') * -1);

        currentModal = null;
        currentIframe = null;
    }

    /**
     * Handle interaction (click or touch)
     */
    function handleInteraction(e) {
        const wrapper = e.target.closest('.e365-video__wrapper');
        if (wrapper) {
            e.preventDefault();
            openModal(wrapper);
            return;
        }

        // Close button click - check for close button in modal
        const closeBtn = e.target.closest('.e365-video__modal button[aria-label]');
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
        // Use both click and touchend for better iOS support
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
