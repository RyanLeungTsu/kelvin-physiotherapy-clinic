(function() {
    const sections = document.querySelectorAll(".kpc-section");
    const navLinks = document.querySelectorAll(".kpc-indicator li a");

    if (!sections.length || !navLinks.length) return;

    // First nav dot click scrolls to very top
    navLinks[0].addEventListener("click", function(e) {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: "smooth" });
    });

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const id = entry.target.getAttribute("id");
                navLinks.forEach(link => {
                    link.classList.toggle(
                        "is-active",
                        link.getAttribute("href") === "#" + id
                    );
                });
            }
        });
    }, {
        threshold: 0.3,
        rootMargin: "0px"
    });

    sections.forEach(section => observer.observe(section));
})();

// For fixing the width snapping on FAQ Tabs
function fixTabWidths() {
    document.querySelectorAll('.kt-tab-inner-content').forEach(tab => {
        const style = tab.getAttribute('style') || '';
        // Only fix if Kadence hasn't hidden it with display:none
        if (!style.includes('display: none')) {
            tab.style.setProperty('width', '100%', 'important');
            tab.style.setProperty('min-width', '100%', 'important');
        }
    });
}

// Observe DOM changes so we catch when Kadence switches tabs
const observer = new MutationObserver(() => {
    fixTabWidths();
});

const tabsWrap = document.querySelector('.kt-tabs-content-wrap');
if (tabsWrap) {
    observer.observe(tabsWrap, {
        attributes: true,
        subtree: true,
        attributeFilter: ['style', 'class']
    });
}

fixTabWidths();