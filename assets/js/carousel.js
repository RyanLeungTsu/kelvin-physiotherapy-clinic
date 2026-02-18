document.addEventListener('DOMContentLoaded', () => {
    const slides = document.querySelectorAll('.carousel-slide');
    let current = 0;

    function Carousel(index) {
        slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
        });
    }
    
    Carousel(current);

    setInterval(() => {
        current = (current + 1) % slides.length;
        Carousel(current);
    }, 10000);
})

document.addEventListener('DOMContentLoaded', () => {
    const slides = document.querySelectorAll('.carousel-slide');
    
    const wpUrl = window.location.origin + '/kelvin-physio';
    
    slides.forEach(slide => {
        if (!slide.querySelector('.hero-overlay')) {
            const overlay = document.createElement('div');
            overlay.className = 'hero-overlay';
            overlay.innerHTML = `
                <div class="hero-logo">
                    <img src="${wpUrl}/wp-content/uploads/carousel-logo/logo.png" alt="Logo">
                </div>
                <h1 class="hero-title">Kelvin Physiotherapy Clinic</h1>
                <p class="hero-tagline">Your Wellness is Our First Priority</p>
            `;
            slide.appendChild(overlay);
        }
    });
});