
document.addEventListener('DOMContentLoaded', function() {
    // Vérifie si les éléments du slideshow existent
    const slides = document.querySelectorAll('.hero-slideshow .slide');
    if (slides.length === 0) {
        console.error('Aucune diapositive trouvée dans le slideshow');
        return;
    }

    const dots = document.querySelectorAll('.carousel-dot');
    const prevBtn = document.querySelector('.carousel-arrow.prev');
    const nextBtn = document.querySelector('.carousel-arrow.next');
    
    let currentSlide = 0;
    let slideInterval = null;
    
    function showSlide(index) {
        // S'assure que l'index est valide
        if (index < 0 || index >= slides.length) {
            console.error('Index de diapositive invalide:', index);
            return;
        }

        // Désactive toutes les diapositives et points
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));
        
        // Active la diapositive et le point courants
        slides[index].classList.add('active');
        if (dots[index]) dots[index].classList.add('active');
        
        currentSlide = index;
    }
    
    function nextSlide() {
        showSlide((currentSlide + 1) % slides.length);
    }
    
    function prevSlide() {
        showSlide((currentSlide - 1 + slides.length) % slides.length);
    }
    
    // Initialisation du slideshow
    function initSlideshow() {
        showSlide(0);
        startSlideshow();
        
        // Ajoute les gestionnaires d'événements
        if (prevBtn) prevBtn.addEventListener('click', () => {
            clearInterval(slideInterval);
            prevSlide();
            startSlideshow();
        });
        
        if (nextBtn) nextBtn.addEventListener('click', () => {
            clearInterval(slideInterval);
            nextSlide();
            startSlideshow();
        });
        
        if (dots.length > 0) {
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    clearInterval(slideInterval);
                    showSlide(index);
                    startSlideshow();
                });
            });
        }
        
        // Pause au survol
        const heroSection = document.querySelector('.hero');
        if (heroSection) {
            heroSection.addEventListener('mouseenter', () => clearInterval(slideInterval));
            heroSection.addEventListener('mouseleave', startSlideshow);
        }
    }
    
    function startSlideshow() {
        clearInterval(slideInterval);
        slideInterval = setInterval(nextSlide, 5000);
    }
    
    // Démarre le slideshow après le chargement
    initSlideshow();
    
    // Ajoute un message de débogage pour vérifier le chargement
    console.log('Slideshow initialisé avec', slides.length, 'diapositives');
});