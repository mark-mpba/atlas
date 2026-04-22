import '../sass/app.scss';

$(function () {

    var $sidebar = $('#docsSidebar');

    var $mobileBackdrop = $('#mobileSidebarBackdrop');

    var $collapseBtn = $('#collapseSidebarBtn');

    var $openMobileBtn = $('#openSidebarMobileBtn');

    var $tocLinks = $('.doc-toc-link');

    var $docsSearchInput = $('#docsSearchInput');

    var $docsSearchClearBtn = $('#docsSearchClearBtn');

    function toggleSidebarCollapsedState() {

        $sidebar.toggleClass('sidebar-collapsed');

    }

    function openMobileSidebar() {

        $sidebar.removeClass('doc-sidebar-mobile-hidden');

        $mobileBackdrop.show();

    }

    function closeMobileSidebar() {

        if (window.innerWidth < 1024) {

            $sidebar.addClass('doc-sidebar-mobile-hidden');

            $mobileBackdrop.hide();

        }

    }

    function initialiseResponsiveSidebar() {

        if (window.innerWidth < 1024) {

            $sidebar.addClass('doc-sidebar-mobile-hidden');

            $mobileBackdrop.hide();

            $sidebar.removeClass('sidebar-collapsed');

        } else {

            $sidebar.removeClass('doc-sidebar-mobile-hidden');

            $mobileBackdrop.hide();

        }

    }

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

    function updateSearchClearButton() {

        if ($.trim($docsSearchInput.val()) === '') {

            $docsSearchClearBtn.hide();

        } else {

            $docsSearchClearBtn.css('display', 'flex');

        }

    }

    function filterDocumentationNavigation() {

        var term = $.trim($docsSearchInput.val()).toLowerCase();

        $('.doc-search-item').each(function () {

            var $item = $(this);

            var text = ($item.data('search-text') || '').toString();

            var matches = term === '' || text.indexOf(term) !== -1;

            $item.toggle(matches);

        });

        $('.doc-search-section').each(function () {

            var $toggle = $(this);

            var target = $toggle.data('target');

            var $panel = $(target);

            var $wrapper = $toggle.closest('.doc-search-section-wrapper');

            var $visibleChildren = $panel.find('.doc-search-item:visible');

            var sectionText = ($toggle.data('search-text') || '').toString();

            var sectionMatches = term === '' || sectionText.indexOf(term) !== -1;

            var hasVisibleChildren = $visibleChildren.length > 0;

            $wrapper.toggle(sectionMatches || hasVisibleChildren);

            if (term !== '' && hasVisibleChildren) {

                $panel.show();

                $toggle.find('.doc-nav-chevron').addClass('rotate-90');

                $toggle.attr('aria-expanded', 'true');

            }

        });

        updateSearchClearButton();

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

    $('.doc-nav-toggle').on('click', function () {

        if ($docsSearchInput.val().trim() !== '') {

            return;

        }

        var $button = $(this);

        var target = $button.data('target');

        var $panel = $(target);

        var $chevron = $button.find('.doc-nav-chevron');

        $panel.stop(true, true).slideToggle(150);

        $chevron.toggleClass('rotate-90');

        $button.attr('aria-expanded', $panel.is(':visible') ? 'true' : 'false');

    });

    $docsSearchInput.on('input', function () {

        filterDocumentationNavigation();

    });

    $docsSearchClearBtn.on('click', function () {

        $docsSearchInput.val('');

        filterDocumentationNavigation();

        $docsSearchInput.trigger('focus');

    });

    $(window).on('resize', function () {

        initialiseResponsiveSidebar();

    });

    $(window).on('scroll', function () {

        updateActiveTocLink();

    });

    initialiseResponsiveSidebar();

    updateActiveTocLink();

    filterDocumentationNavigation();

    updateSearchClearButton();

});

