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

    function resetSectionStateWhenSearchCleared() {
        $('.doc-search-section').each(function () {
            var $toggle = $(this);
            var target = $toggle.data('target');
            var $panel = $(target);
            var defaultExpanded = ($toggle.data('default-expanded') || '').toString() === 'true';

            $toggle.attr('aria-expanded', defaultExpanded ? 'true' : 'false');
            $toggle.find('.doc-nav-chevron').toggleClass('rotate-90', defaultExpanded);

            if (defaultExpanded) {
                $panel.show();
            } else {
                $panel.hide();
            }
        });

        $('.doc-search-section-wrapper').show();
        $('.doc-search-item').show();
    }

    function filterDocumentationNavigation() {
        var term = $.trim($docsSearchInput.val()).toLowerCase();

        if (term === '') {
            resetSectionStateWhenSearchCleared();
            updateSearchClearButton();
            return;
        }

        $('.doc-search-item').each(function () {
            var $item = $(this);
            var text = ($item.data('search-text') || '').toString().toLowerCase();
            var matches = text.indexOf(term) !== -1;

            $item.toggle(matches);
        });

        $('.doc-search-section').each(function () {
            var $toggle = $(this);
            var target = $toggle.data('target');
            var $panel = $(target);
            var $wrapper = $toggle.closest('.doc-search-section-wrapper');
            var sectionText = ($toggle.data('search-text') || '').toString().toLowerCase();

            var hasMatchingChildren = $panel.find('.doc-search-item').filter(function () {
                return $(this).css('display') !== 'none';
            }).length > 0;

            var sectionMatches = sectionText.indexOf(term) !== -1;
            var shouldShowSection = sectionMatches || hasMatchingChildren;

            $wrapper.toggle(shouldShowSection);

            if (!shouldShowSection) {
                return;
            }

            if (sectionMatches && !hasMatchingChildren) {
                $panel.find('.doc-search-item').show();
                hasMatchingChildren = true;
            }

            if (hasMatchingChildren || sectionMatches) {
                $panel.show();
                $toggle.attr('aria-expanded', 'true');
                $toggle.find('.doc-nav-chevron').addClass('rotate-90');
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
        var willOpen = !$panel.is(':visible');

        $panel.stop(true, true).slideToggle(150);
        $chevron.toggleClass('rotate-90', willOpen);
        $button.attr('aria-expanded', willOpen ? 'true' : 'false');
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
