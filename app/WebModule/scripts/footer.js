(function() {
    'use strict';

    let initialized = false;
    let footerFixed = false;
    let liveUpdater = null;

    let anchor = null;
    let footer = null;

    window.initFooter = function(liveUpdate = false) {
        anchor = document.getElementById("footer-anchor");
        footer = document.getElementById("footer");

        if (!anchor || !footer || !window.innerHeight) {
            return;
        }

        initialized = true;

        updateSize();

        // Updating size in 'load' event is required by Safari.
        window.addEventListener("load", function() {
            updateSize();
        });

        window.addEventListener('resize', function(e) {
            updateSize();
        });

        if (liveUpdate) {
            setLiveUpdate(true);
        }
    }

    /**
     * Continuously recalculate size of content and set proper position of footer.
     * This is needed only if page content can be dynamically resized.
     *
     * @param enable
     */
    function setLiveUpdate(enable) {
        if (!initialized || (enable && liveUpdater !== null) || (!enable && liveUpdater === null)) {
            return;
        }

        if (enable) {
            // Run timer to catch new size of dynamic content.
            liveUpdater = setInterval(function() {
                updateSize();
            }, 200);
        } else {
            clearInterval(liveUpdater);
            liveUpdater = null;
        }
    }

    function updateSize() {
        if (!initialized) {
            return;
        }

        const contentHeight = anchor.offsetTop + footer.offsetHeight;

        // Compute fixed status of footer. If new status is tha same as actual, end.
        const fixed = contentHeight < window.innerHeight;
        if (fixed === footerFixed) {
            return;
        }

        // Set new fixed status.
        footerFixed = fixed;

        footer.style.position = footerFixed ? "fixed" : "relative";
    }
})();
