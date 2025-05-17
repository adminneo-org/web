(function() {
    'use strict';

    $(function() {
        initAnchors();
        initMenu();
        initFooter();
        $.nette.init();
        jush.highlight_tag('code');
        enableInputModifiersOnTouch();
    });

    window.initMenu = function() {
        const $menu = $("#menu");
        const $menuOpen = $("#menu-button .ic-menu");
        const $menuClose = $("#menu-button .ic-close");

        $("#menu-button").on("click", function(event) {
            event.preventDefault();

            $menu.toggle();
            $menuOpen.toggle();
            $menuClose.toggle();
        });
    }

    window.initAnchors = function() {
        if (location.hash !== "") {
            scrollToAnchor(location.hash);
        }

        $("a[href^='#']").on("click", function(event) {
            event.preventDefault();

            const hash = $(this).attr('href');

            scrollToAnchor(hash, true);
            window.location.hash = hash;
        });
    }

    function scrollToAnchor(hash, smooth = false) {
        const $target = $(hash);
        if (!$target.length) {
            return;
        }

        const headerHeight = $(".header").height();
        const position = $target.offset().top - headerHeight - 20;

        if (smooth) {
            $('html,body').animate({
                scrollTop: position
            });
        } else {
            window.scrollTo(0, position);
        }
    }

    // Allow :active and :focus styles in Mobile Safari.
    window.enableInputModifiersOnTouch = function(elements) {
        if (!navigator.userAgent.match(/(iPhone|iPod|iPad).*Safari\//) && !navigator.userAgent.match(/Android.*Mobile Safari\//)) {
            return;
        }

        if (!elements) {
            const tagNames = ["a", "input", "textarea", "button", "select", "label"];
            tagNames.forEach(function(element) {
                const elements = document.getElementsByTagName(element);
                if (elements && elements.length > 0) {
                    enableInputModifiersOnTouch(elements);
                }
            });

            return;
        }

        for (let i = 0; i < elements.length; i++) {
            elements[i].addEventListener("touchstart", function() {
            }, {capture: true, passive: true});
        }
    };

})();
