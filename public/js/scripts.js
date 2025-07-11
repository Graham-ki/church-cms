document.addEventListener('DOMContentLoaded', function() {
    // Mobile Navigation Toggle
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links');
    
    hamburger.addEventListener('click', function() {
        this.classList.toggle('active');
        navLinks.classList.toggle('active');
    });
    
    // Close mobile menu when clicking on a link
    document.querySelectorAll('.nav-links a').forEach(link => {
        link.addEventListener('click', () => {
            hamburger.classList.remove('active');
            navLinks.classList.remove('active');
        });
    });
    
    // Navigation Scroll Effect
    window.addEventListener('scroll', function() {
        const nav = document.querySelector('.glass-nav');
        if (window.scrollY > 50) {
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }
    });
    
    // Hero Banner Slideshow Functionality
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');
    const prevBtn = document.querySelector('.prev');
    const nextBtn = document.querySelector('.next');
    let currentSlide = 0;
    
    function showSlide(n) {
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));
        
        currentSlide = (n + slides.length) % slides.length;
        slides[currentSlide].classList.add('active');
        dots[currentSlide].classList.add('active');
    }
    
    function nextSlide() {
        showSlide(currentSlide + 1);
    }
    
    function prevSlide() {
        showSlide(currentSlide - 1);
    }
    
    // Auto-advance hero slides every 5 seconds
    let heroSlideInterval = setInterval(nextSlide, 5000);
    
    // Pause auto-advance when hovering over hero slideshow
    const slideshowContainer = document.querySelector('.slideshow-container');
    slideshowContainer.addEventListener('mouseenter', () => {
        clearInterval(heroSlideInterval);
    });
    
    slideshowContainer.addEventListener('mouseleave', () => {
        heroSlideInterval = setInterval(nextSlide, 5000);
    });
    
    // Event listeners for manual hero navigation
    nextBtn.addEventListener('click', () => {
        nextSlide();
        clearInterval(heroSlideInterval);
        heroSlideInterval = setInterval(nextSlide, 5000);
    });
    
    prevBtn.addEventListener('click', () => {
        prevSlide();
        clearInterval(heroSlideInterval);
        heroSlideInterval = setInterval(nextSlide, 5000);
    });
    
    // Dot navigation for hero
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            showSlide(index);
            clearInterval(heroSlideInterval);
            heroSlideInterval = setInterval(nextSlide, 5000);
        });
    });
    
    // Communications Carousel
    const commsContainer = document.querySelector('.comms-container');
    const commCards = document.querySelectorAll('.comm-card');
    const carouselPrev = document.querySelector('.carousel-prev');
    const carouselNext = document.querySelector('.carousel-next');
    let currentCard = 0;
    let cardWidth = commCards[0].offsetWidth + 20; // Including margin
    
    function moveCarousel() {
        commsContainer.style.transform = `translateX(-${currentCard * cardWidth}px)`;
    }
    
    function nextCard() {
        if (currentCard < commCards.length - 3) {
            currentCard++;
        } else {
            currentCard = 0; // Loop back to start
        }
        moveCarousel();
    }
    
    // Auto-advance communications every 3 seconds
    let commsInterval = setInterval(nextCard, 3000);
    
    // Pause auto-advance when hovering over carousel
    const commsCarousel = document.querySelector('.comms-carousel');
    commsCarousel.addEventListener('mouseenter', () => {
        clearInterval(commsInterval);
    });
    
    commsCarousel.addEventListener('mouseleave', () => {
        commsInterval = setInterval(nextCard, 3000);
    });
    
    // Manual navigation for communications carousel
    carouselNext.addEventListener('click', function() {
        nextCard();
        clearInterval(commsInterval);
        commsInterval = setInterval(nextCard, 3000);
    });
    
    carouselPrev.addEventListener('click', function() {
        if (currentCard > 0) {
            currentCard--;
        } else {
            currentCard = commCards.length - 3; // Loop to end
        }
        moveCarousel();
        clearInterval(commsInterval);
        commsInterval = setInterval(nextCard, 3000);
    });
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Initialize the first slide and carousel position
    showSlide(0);
    moveCarousel();
    
    // Responsive adjustments for carousel
    window.addEventListener('resize', function() {
        const newCardWidth = commCards[0].offsetWidth + 20;
        if (Math.abs(newCardWidth - cardWidth) > 5) { // Only update if significant change
            cardWidth = newCardWidth;
            moveCarousel();
        }
    });
});