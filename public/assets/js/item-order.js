document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-quantity-picker]').forEach((picker) => {
    const quantityInput = picker.querySelector('input[type="number"]');
    if (!quantityInput) return;

    const form = picker.closest('form');
    const subtotalEl = form?.querySelector('[data-order-subtotal]') ?? null;
    const priceEl = document.querySelector('[data-unit-price]');
    const unitPrice = priceEl ? parseFloat(priceEl.dataset.unitPrice) : NaN;
    const minQty = quantityInput.min !== '' ? parseInt(quantityInput.min, 10) : 1;
    const maxQty = quantityInput.max !== '' ? parseInt(quantityInput.max, 10) : 99;

    function clampQuantity(value) {
      const num = parseInt(value, 10);
      if (Number.isNaN(num)) return minQty;
      return Math.min(maxQty, Math.max(minQty, num));
    }

    function updateSubtotal() {
      if (!subtotalEl || Number.isNaN(unitPrice)) return;
      const qty = clampQuantity(quantityInput.value);
      quantityInput.value = String(qty);
      subtotalEl.textContent = formatMoney(unitPrice * qty);
    }

    picker.querySelector('[data-quantity-decrease]')?.addEventListener('click', () => {
      quantityInput.value = String(clampQuantity(parseInt(quantityInput.value, 10) - 1));
      updateSubtotal();
    });

    picker.querySelector('[data-quantity-increase]')?.addEventListener('click', () => {
      quantityInput.value = String(clampQuantity(parseInt(quantityInput.value, 10) + 1));
      updateSubtotal();
    });

    quantityInput.addEventListener('input', updateSubtotal);
    quantityInput.addEventListener('change', updateSubtotal);
  });
});

function formatMoney(amount) {
  return '$' + amount.toFixed(2);
}
