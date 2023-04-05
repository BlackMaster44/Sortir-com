const button = document.querySelector('.filter-collapse');
const filters = document.querySelector('.filters');
filters.style.height = 0;
filters.style.display = 'none';
let iscollapsed = true;
button.addEventListener('click', () => {
    iscollapsed = !iscollapsed;
    console.log(filters.style.height);
        filters.style.height = filters.style.height === '0px' ? 'auto' : '0px';
        filters.style.display = filters.style.display === 'none' ? 'block' : 'none';
        button.textContent = iscollapsed ? "Filters ▼" : 'Filters ▲';
})