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

