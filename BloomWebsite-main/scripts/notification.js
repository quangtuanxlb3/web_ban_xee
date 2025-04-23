document.addEventListener('DOMContentLoaded', () => {
    const box = document.getElementById('error-box');
    const btn = document.querySelector('.close-btn');

    function closeErrorBox() {
        if (box) box.style.display = 'none';
    }

    if (btn) {
        btn.addEventListener('click', closeErrorBox);
    }

    setTimeout(closeErrorBox, 10000);
});
