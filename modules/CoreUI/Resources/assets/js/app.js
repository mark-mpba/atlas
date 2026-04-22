import '../sass/app.scss';

$(function () {
    let $sidebar = $('#docsSidebar');
    let $mobileBackdrop = $('#mobileSidebarBackdrop');
    let $collapseBtn = $('#collapseSidebarBtn');
    let $openMobileBtn = $('#openSidebarMobileBtn');
    let $tocLinks = $('.doc-toc-link');
    let $docsSearchInput = $('#docsSearchInput');
    let $docsSearchClearBtn = $('#docsSearchClearBtn');

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
        let scrollPosition = $(window).scrollTop() + 140;
        let activeId = null;

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
            let $toggle = $(this);
            let target = $toggle.data('target');
            let $panel = $(target);
            let defaultExpanded = ($toggle.data('default-expanded') || '').toString() === 'true';

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
        let term = $.trim($docsSearchInput.val()).toLowerCase();

        if (term === '') {
            resetSectionStateWhenSearchCleared();
            updateSearchClearButton();
            return;
        }

        $('.doc-search-item').each(function () {
            let $item = $(this);
            let text = ($item.data('search-text') || '').toString().toLowerCase();
            let matches = text.indexOf(term) !== -1;

            $item.toggle(matches);
        });

        $('.doc-search-section').each(function () {
            let $toggle = $(this);
            let target = $toggle.data('target');
            let $panel = $(target);
            let $wrapper = $toggle.closest('.doc-search-section-wrapper');
            let sectionText = ($toggle.data('search-text') || '').toString().toLowerCase();

            let hasMatchingChildren = $panel.find('.doc-search-item').filter(function () {
                return $(this).css('display') !== 'none';
            }).length > 0;

            let sectionMatches = sectionText.indexOf(term) !== -1;
            let shouldShowSection = sectionMatches || hasMatchingChildren;

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

        let $button = $(this);
        let target = $button.data('target');
        let $panel = $(target);
        let $chevron = $button.find('.doc-nav-chevron');
        let willOpen = !$panel.is(':visible');

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
