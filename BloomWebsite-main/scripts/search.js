import { ROOT_WEBSITE } from "./constant.js";

const searchInput = document.getElementById('searchInput');
const searchIcon = document.getElementById('searchIcon');

function handleSearch() {
    const query = searchInput.value.trim();
    if (query) {
        window.location.href = `/${ROOT_WEBSITE}/tim-kiem?keyword=${encodeURIComponent(query)}&category=&sort=name_asc`;
    }
}

searchInput.addEventListener('keypress', function(event) {
    if (event.key === 'Enter') {
        handleSearch();
    }
});

searchIcon.addEventListener('click', handleSearch);