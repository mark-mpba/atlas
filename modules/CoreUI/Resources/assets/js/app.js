import '../sass/app.scss';
import '@/bootstrap';
$(function () {
    var $sidebar = $('#docsSidebar');
    var $mobileBackdrop = $('#mobileSidebarBackdrop');
    var $collapseBtn = $('#collapseSidebarBtn');
    var $openMobileBtn = $('#openSidebarMobileBtn');
    var $tocLinks = $('.doc-toc-link');

    /**
     * Toggle desktop sidebar collapsed state.
     *
     * @returns {void}
     */
    function toggleSidebarCollapsedState() {
        $sidebar.toggleClass('sidebar-collapsed');
    }

    /**
     * Open the mobile sidebar.
     *
     * @returns {void}
     */
    function openMobileSidebar() {
        $sidebar.removeClass('doc-sidebar-mobile-hidden');
        $mobileBackdrop.removeClass('hidden');
    }

    /**
     * Close the mobile sidebar.
     *
     * @returns {void}
     */
    function closeMobileSidebar() {
        if (window.innerWidth < 1024) {
            $sidebar.addClass('doc-sidebar-mobile-hidden');
            $mobileBackdrop.addClass('hidden');
        }
    }

    /**
     * Initialise sidebar state based on screen width.
     *
     * @returns {void}
     */
    function initialiseResponsiveSidebar() {
        if (window.innerWidth < 1024) {
            $sidebar.addClass('doc-sidebar-mobile-hidden');
            $mobileBackdrop.addClass('hidden');
            $sidebar.removeClass('sidebar-collapsed');
        } else {
            $sidebar.removeClass('doc-sidebar-mobile-hidden');
            $mobileBackdrop.addClass('hidden');
        }
    }

    /**
     * Highlight the active in-document navigation item.
     *
     * @returns {void}
     */
    function updateActiveTocLink() {
        var scrollPosition = $(window).scrollTop() + 140;
        var activeId = null;

        $('.abbott-content h2[id], .abbott-content h3[id]').each(function () {
            if ($(this).offset().top <= scrollPosition) {
                activeId = $(this).attr('id');
            }
        });

        $tocLinks.removeClass('active');

        if (activeId) {
            $('.doc-toc-link[data-target="' + activeId + '"]').addClass('active');
        }
    }

    $collapseBtn.on('click', function () {
        toggleSidebarCollapsedState();
    });

    $openMobileBtn.on('click', function () {
        openMobileSidebar();
    });

    $mobileBackdrop.on('click', function () {
        closeMobileSidebar();
    });

    $tocLinks.on('click', function () {
        closeMobileSidebar();
    });

    $(window).on('resize', function () {
        initialiseResponsiveSidebar();
    });

    $(window).on('scroll', function () {
        updateActiveTocLink();
    });

    initialiseResponsiveSidebar();
    updateActiveTocLink();
});
