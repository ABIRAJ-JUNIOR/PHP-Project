document.addEventListener('DOMContentLoaded', () => {
  const filterBtns = document.querySelectorAll('.menu-filter__btn');
  const searchInput = document.getElementById('menu-search-input');
  const menuItems = document.querySelectorAll('.menu-item');
  const categories = document.querySelectorAll('.menu-category');

  let activeCategory = 'all';

  filterBtns.forEach((btn) => {
    btn.addEventListener('click', () => {
      filterBtns.forEach((b) => b.classList.remove('is-active'));
      btn.classList.add('is-active');
      activeCategory = btn.dataset.category;
      applyFilters();
    });
  });

  if (searchInput) {
    searchInput.addEventListener('input', applyFilters);
  }

  function applyFilters() {
    const query = searchInput ? searchInput.value.trim().toLowerCase() : '';

    menuItems.forEach((item) => {
      const cat = item.dataset.category;
      const name = item.dataset.name || '';
      const catMatch = activeCategory === 'all' || cat === activeCategory;
      const searchMatch = !query || name.includes(query);
      item.classList.toggle('hidden', !(catMatch && searchMatch));
    });

    categories.forEach((section) => {
      const cat = section.dataset.category;
      const visibleItems = section.querySelectorAll('.menu-item:not(.hidden)');
      const showSection = activeCategory === 'all'
        ? visibleItems.length > 0
        : cat === activeCategory && visibleItems.length > 0;
      section.classList.toggle('hidden', !showSection);
      const hr = section.nextElementSibling;
      if (hr && hr.tagName === 'HR') {
        hr.classList.toggle('hidden', !showSection);
      }
    });
  }
});
