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

        // Set iframe src with autoplay
        iframe.src = videoUrl + (videoUrl.includes('?') ? '&' : '?') + 'autoplay=1';

        // Show modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';

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

        document.body.style.overflow = '';
        currentModal = null;
        currentIframe = null;
    }

    /**
     * Initialize event listeners using delegation
     */
    function init() {
        // Click handler for video wrappers (open modal)
        document.addEventListener('click', function(e) {
            const wrapper = e.target.closest('.e365-video__wrapper');
            if (wrapper) {
                e.preventDefault();
                openModal(wrapper);
                return;
            }

            // Close button click
            const closeBtn = e.target.closest('.e365-video__modal button');
            if (closeBtn && closeBtn.getAttribute('aria-label') === 'Lukk video') {
                closeModal();
                return;
            }

            // Click on modal backdrop (not content)
            const modal = e.target.closest('.e365-video__modal');
            if (modal && e.target === modal) {
                closeModal();
            }
        });

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
