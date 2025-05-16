(function() {
    'use strict';

    $(function() {
        initAnchors();
        $.nette.init();
        enableInputModifiersOnTouch();
    });

    window.initAnchors = function() {
        $("a[href^='#']").click(function(event) {
            event.preventDefault();

            const anchor = $(this).attr('href');
            const $target = $(anchor);
            if (!$target.length) {
                return;
            }

            const headerHeight = $(".header").height();
            console.log(headerHeight);

            history.pushState(null, null, anchor);
            $('html,body').animate({
                scrollTop: $target.offset().top - headerHeight - 20
            });
        });
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
