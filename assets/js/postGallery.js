(function() {
    'use strict';
    
    document.addEventListener('DOMContentLoaded', function() {
        initPostCarousel();
    });
    
    function initPostCarousel() {
        const container = document.querySelector('.post-carousel-container');
        if (!container) return;
        
        const postsCount = container.getAttribute('data-posts') || 4;
        const category = container.getAttribute('data-category') || '';
        
        // This grabs posts from wordpress
        fetchPosts(postsCount, category, function(posts) {
            if (posts && posts.length > 0) {
                buildCarousel(container, posts);
                initCarouselControls();
            }
        });
    }
    
    function fetchPosts(count, category, callback) {
        const formData = new FormData();
        formData.append('action', 'get_carousel_posts');
        formData.append('nonce', postCarouselData.nonce);
        formData.append('posts', count);
        formData.append('category', category);
        
        fetch(postCarouselData.ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                callback(data.data);
            }
        })
        .catch(error => {
            console.error('Error fetching posts:', error);
        });
    }
    // return for carousel
    function buildCarousel(container, posts) {
        const carouselHTML = `
            <div class="post-carousel-wrapper">
                <div class="post-carousel">
                    <button class="carousel-nav carousel-prev" aria-label="Previous Post">‹</button>
                    <button class="carousel-nav carousel-next" aria-label="Next Post">›</button>
                    
                    <div class="post-slides">
                        ${posts.map((post, index) => `
                            <div class="post-slide ${index === 0 ? 'active' : ''}">
                                <div class="post-slide-image">
                                    <img src="${post.thumbnail}" alt="${post.title}" loading="lazy">
                                </div>
                                <div class="post-slide-content">
                                    ${post.category ? `<span class="post-category">${post.category}</span>` : ''}
                                    <h3><a href="${post.permalink}">${post.title}</a></h3>
                                    <div class="post-meta">
                                        <span class="post-date">${post.date}</span>
                                        <span class="post-author">${post.author}</span>
                                    </div>
                                    <p class="post-excerpt">${post.excerpt}</p>
                                    <a href="${post.permalink}" class="read-more-btn">Read More →</a>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                    
                    <div class="carousel-dots">
                        ${posts.map((_, index) => `
                            <button class="carousel-dot ${index === 0 ? 'active' : ''}" 
                                    data-slide="${index}" 
                                    aria-label="Go to slide ${index + 1}">
                            </button>
                        `).join('')}
                    </div>
                </div>
            </div>
        `;
        
        container.innerHTML = carouselHTML;
    }

    function initCarouselControls() {
        const carousel = document.querySelector('.post-carousel');
        if (!carousel) return;
        
        const slides = carousel.querySelectorAll('.post-slide');
        const dots = carousel.querySelectorAll('.carousel-dot');
        const prevBtn = carousel.querySelector('.carousel-prev');
        const nextBtn = carousel.querySelector('.carousel-next');
        
        let currentSlide = 0;
        let autoplayInterval;
        // Timer
        const autoplayDelay = 9000;
        
        function showSlide(index) {
            if (index >= slides.length) currentSlide = 0;
            else if (index < 0) currentSlide = slides.length - 1;
            else currentSlide = index;
            
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));
            
            slides[currentSlide].classList.add('active');
            dots[currentSlide].classList.add('active');
        }
        
        function nextSlide() {
            showSlide(currentSlide + 1);
        }
        
        function prevSlide() {
            showSlide(currentSlide - 1);
        }
        
        function startAutoplay() {
            stopAutoplay();
            autoplayInterval = setInterval(nextSlide, autoplayDelay);
        }
        
        function stopAutoplay() {
            if (autoplayInterval) clearInterval(autoplayInterval);
        }
        
        function restartAutoplay() {
            stopAutoplay();
            startAutoplay();
        }

        prevBtn.addEventListener('click', function() {
            prevSlide();
            restartAutoplay();
        });
        
        nextBtn.addEventListener('click', function() {
            nextSlide();
            restartAutoplay();
        });
        
        dots.forEach((dot, index) => {
            dot.addEventListener('click', function() {
                showSlide(index);
                restartAutoplay();
            });
        });
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') {
                prevSlide();
                restartAutoplay();
            } else if (e.key === 'ArrowRight') {
                nextSlide();
                restartAutoplay();
            }
        });
        
        carousel.addEventListener('mouseenter', stopAutoplay);
        carousel.addEventListener('mouseleave', startAutoplay);
        
        let touchStartX = 0;
        let touchEndX = 0;
        
        carousel.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        
        carousel.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            const swipeDistance = touchEndX - touchStartX;
            
            if (Math.abs(swipeDistance) > 50) {
                if (swipeDistance > 0) prevSlide();
                else nextSlide();
                restartAutoplay();
            }
        }, { passive: true });
        
        startAutoplay();
        
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) stopAutoplay();
            else startAutoplay();
        });
    }
    
})();