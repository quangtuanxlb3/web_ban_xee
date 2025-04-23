const slide = document.querySelector('.banner-slide');
const images = document.querySelectorAll('.banner-slide img');
const prevBtn = document.querySelector('.pre-banner');
const nextBtn = document.querySelector('.next-banner');

let currentIndex = 0;
const totalSlides = images.length;
const slideWidth = images[0].clientWidth;

function updateSlide() {
    slide.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
}

nextBtn.addEventListener('click', () => {
    currentIndex = (currentIndex + 1) % totalSlides;
    updateSlide();
});

prevBtn.addEventListener('click', () => {
    currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
    updateSlide();
});

setInterval(() => {
    currentIndex = (currentIndex + 1) % totalSlides;
    updateSlide();
}, 5000);

window.addEventListener('resize', () => {
    slide.style.transition = 'none';
    updateSlide();
    setTimeout(() => {
        slide.style.transition = 'transform 0.5s ease-in-out';
    });
});
