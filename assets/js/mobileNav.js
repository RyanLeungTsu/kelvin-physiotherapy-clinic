document.addEventListener("DOMContentLoaded", function () {
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
            
            <li class="menu-accordion">
                <!-- Trigger is just the "Services" text, no arrow, styled like other links -->
                <button class="accordion-trigger" aria-expanded="false" aria-controls="services-submenu">
                    Services
                </button>
                <ul class="submenu" id="services-submenu" aria-hidden="true">
                    <li><a href="/kelvin-physio/services/physio/">Physiotherapy</a></li>
                    <li><a href="/kelvin-physio/services/rehab/">Rehabilitation</a></li>
                    <li><a href="/kelvin-physio/services/kins/">Kinesiology</a></li>
                    <li><a href="/kelvin-physio/services/telehealth/">TeleHealth</a></li>
                </ul>
            </li>            
            <li><a href="/kelvin-physio/rates/">Rates</a></li>
            <li><a href="/kelvin-physio/blog/">Blog</a></li>
            <li><a href="/kelvin-physio/faq/">FAQ</a></li>
            <li><a href="/kelvin-physio/contact-us/">Contact Us</a></li>
        </ul>
        
        <svg class="mobile-menu-bottom-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
    </div>
    `;

  document.body.insertAdjacentHTML("afterbegin", mobileHeaderHTML);

  const menuToggle = document.querySelector(".mobile-menu-toggle");
  const menuOverlay = document.querySelector(".mobile-menu-overlay");
  const bottomArrow = document.querySelector(".mobile-menu-bottom-arrow");
  const mobileHeader = document.querySelector(".mobile-header");
  const accordionTriggers = document.querySelectorAll(".accordion-trigger");
  accordionTriggers.forEach((trigger) => {
    trigger.addEventListener("click", function (e) {
      e.preventDefault();
      const submenu = this.nextElementSibling;
      const isExpanded = this.getAttribute("aria-expanded") === "true";

      // Toggles the accordion state
      this.setAttribute("aria-expanded", !isExpanded);
      submenu.classList.toggle("active");
      submenu.setAttribute("aria-hidden", isExpanded);

      // If adding more accordians later
      // accordionTriggers.forEach(otherTrigger => {
      //     if (otherTrigger !== this) {
      //         otherTrigger.setAttribute('aria-expanded', 'false');
      //         otherTrigger.nextElementSibling.classList.remove('active');
      //     }
      // });
    });
  });

  // Ensure submenu links close the full menu when clicked
  const submenuLinks = document.querySelectorAll(".submenu a");
  submenuLinks.forEach((link) => {
    link.addEventListener("click", () => {
      toggleMenu(); // Reuse your existing menu toggle function
    });
  });
  function toggleMenu() {
    menuToggle.classList.toggle("active");
    menuOverlay.classList.toggle("active");
    mobileHeader.classList.toggle("menu-open");
    // document.body.style.overflow = menuOverlay.classList.contains('active') ? 'hidden' : '';
    if (menuOverlay.classList.contains("active")) {
      document.body.classList.add("menu-open");
    } else {
      document.body.classList.remove("menu-open");
    }
  }

  // Force closes the mobile menu and removes the scroll locks
  function forceCloseMobileMenu() {
    // Removes all active states from menu elements
    menuToggle.classList.remove("active");
    menuOverlay.classList.remove("active");
    mobileHeader.classList.remove("menu-open");
    // Removes the background scroll lock
    document.body.classList.remove("menu-open");
    // Close any open accordions (optional, prevents leftover open states)
    const accordionTriggers = document.querySelectorAll(".accordion-trigger");
    accordionTriggers.forEach((trigger) => {
      trigger.setAttribute("aria-expanded", "false");
      const submenu = trigger.nextElementSibling;
      if (submenu) submenu.classList.remove("active");
    });
  }

  const mobileMenuMediaQuery = window.matchMedia("(max-width: 500px)");

  // Automatically force-closes menus when viewport resizes wider than mobile breakpoint
  function handleBreakpointChange() {
    if (!mobileMenuMediaQuery.matches) {
      forceCloseMobileMenu();
    }
  }

  mobileMenuMediaQuery.addEventListener("change", handleBreakpointChange);

  handleBreakpointChange();

  menuToggle.addEventListener("click", toggleMenu);
  bottomArrow.addEventListener("click", toggleMenu);

  const menuLinks = document.querySelectorAll(".mobile-menu-nav a");
  menuLinks.forEach((link) => {
    link.addEventListener("click", () => {
      toggleMenu();
    });
  });
});
