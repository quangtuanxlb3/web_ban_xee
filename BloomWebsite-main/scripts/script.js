window.addEventListener('scroll', () => {
    const nav = document.querySelector('.flowers-list');
    const offset = nav.offsetTop;

    if (window.scrollY >= offset) {
        nav.classList.add('is-sticky');
        
    } else {
        nav.classList.remove('is-sticky');
    }
});