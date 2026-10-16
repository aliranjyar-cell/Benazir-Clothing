
document.addEventListener('DOMContentLoaded', () => {
    // Open and close the mobile filter sidebar
    const filterSidebar = document.getElementById('mobileFilterSidebar');

    // Quick view modal
    const quickViewModal = document.getElementById('quickViewModal');

    // Close an overlay by removing its visible classes
    function closeOverlay(element) {
        if (!element) return;

        element.classList.add('hidden');
        element.classList.remove('flex');
    }

    // Open an overlay
    function openOverlay(element) {
        if (!element) return;

        element.classList.remove('hidden');
        element.classList.add('flex');
    }

    // Handle clicks on shop page controls
    document.addEventListener('click', (event) => {
        const actionElement = event.target.closest('[data-action]');

        if (!actionElement) return;

        const action = actionElement.dataset.action;

        switch (action) {
            case 'open-mobile-filters':
                openOverlay(filterSidebar);
                break;

            case 'close-mobile-filters':
                closeOverlay(filterSidebar);
                break;

            case 'open-quick-view':
                openOverlay(quickViewModal);
                break;

            case 'close-quick-view':
                closeOverlay(quickViewModal);
                break;
        }
    });

    // Close overlays when the Escape key is pressed
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeOverlay(filterSidebar);
            closeOverlay(quickViewModal);
        }
    });
});
