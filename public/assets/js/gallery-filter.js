document.addEventListener('DOMContentLoaded', () => {
  const filterBtns = document.querySelectorAll('[data-gallery-category]');
  const items = document.querySelectorAll('.gallery-item');

  if (!filterBtns.length || !items.length) return;

  filterBtns.forEach((btn) => {
    btn.addEventListener('click', () => {
      filterBtns.forEach((b) => b.classList.remove('is-active'));
      btn.classList.add('is-active');
      const category = btn.dataset.galleryCategory;

      items.forEach((item) => {
        const cat = item.dataset.galleryCat || 'general';
        const show = category === 'all' || cat === category;
        item.classList.toggle('hidden', !show);
      });
    });
  });
});
