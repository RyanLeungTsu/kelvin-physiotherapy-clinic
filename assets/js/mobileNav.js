document.addEventListener('DOMContentLoaded', function() {
    const mobileHeaderHTML = `
        <div class="mobile-header">
            <div class="mobile-header-logo-div">
                <img src="/kelvin-physio/wp-content/uploads/carousel-logo/logo.png" alt="Logo" class="mobile-header-logo">
            </div>
            <button class="mobile-menu-toggle" aria-label="Toggle Menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </button>
        </div>
        
        <div class="mobile-menu-overlay">
            <img src="/kelvin-physio/wp-content/uploads/carousel-logo/logo.png" alt="Logo" class="mobile-menu-logo">
            
            <ul class="mobile-menu-nav">
                <li><a href="/kelvin-physio/">Home</a></li>
                <li><a href="/kelvin-physio/about-us/">About Us</a></li>
                <li><a href="/kelvin-physio/services/">Services & Rates</a></li>
                <li><a href="/kelvin-physio/blog/">Blog</a></li>
                <li><a href="/kelvin-physio/faq/">FAQ</a></li>
                <li><a href="/kelvin-physio/contact-us/">Contact Us</a></li>
            </ul>
            
            <svg class="mobile-menu-bottom-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </div>
    `;
    
    document.body.insertAdjacentHTML('afterbegin', mobileHeaderHTML);
    
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const menuOverlay = document.querySelector('.mobile-menu-overlay');
    const bottomArrow = document.querySelector('.mobile-menu-bottom-arrow');
    const mobileHeader = document.querySelector('.mobile-header'); 
    function toggleMenu() {
        menuToggle.classList.toggle('active');
        menuOverlay.classList.toggle('active');
        mobileHeader.classList.toggle('menu-open'); 
        document.body.style.overflow = menuOverlay.classList.contains('active') ? 'hidden' : '';
    }
    
    menuToggle.addEventListener('click', toggleMenu);
    bottomArrow.addEventListener('click', toggleMenu);
    
    const menuLinks = document.querySelectorAll('.mobile-menu-nav a');
    menuLinks.forEach(link => {
        link.addEventListener('click', () => {
            toggleMenu();
        });
    });
});