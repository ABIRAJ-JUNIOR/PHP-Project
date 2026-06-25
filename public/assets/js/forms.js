document.addEventListener('DOMContentLoaded', () => {
  const orderForm = document.getElementById('order-form') || document.getElementById('checkout-form');
  const addToCartForm = document.getElementById('add-to-cart-form');
  const trackOrderForm = document.getElementById('track-order-form');
  const contactForm = document.getElementById('contact-form');

  if (orderForm) {
    orderForm.addEventListener('submit', (e) => {
      if (!validateOrderForm(orderForm)) {
        e.preventDefault();
      }
    });
    bindLiveValidation(orderForm, validateOrderForm);
  }

  if (addToCartForm) {
    addToCartForm.addEventListener('submit', (e) => {
      if (!validateAddToCartForm(addToCartForm)) {
        e.preventDefault();
      }
    });
    bindLiveValidation(addToCartForm, validateAddToCartForm);
  }

  if (trackOrderForm) {
    trackOrderForm.addEventListener('submit', (e) => {
      if (!validateTrackOrderForm(trackOrderForm)) {
        e.preventDefault();
      }
    });
    bindLiveValidation(trackOrderForm, validateTrackOrderForm);
  }

  if (contactForm) {
    contactForm.addEventListener('submit', (e) => {
      if (!validateContactForm(contactForm)) {
        e.preventDefault();
      }
    });
    bindLiveValidation(contactForm, validateContactForm);
  }
});

function bindLiveValidation(form, validator) {
  form.querySelectorAll('input, textarea, select').forEach((field) => {
    field.addEventListener('blur', () => validator(form));
    field.addEventListener('input', () => {
      const group = field.closest('.form-group');
      if (group && group.classList.contains('form-group--error')) {
        validator(form);
      }
    });
  });
}

function validateOrderForm(form) {
  let valid = true;
  valid = validateRequired(form, 'order-name', 'Name is required.') && valid;
  valid = validateEmail(form, 'order-email') && valid;
  valid = validatePhone(form, 'order-phone') && valid;
  if (form.querySelector('#order-quantity')) {
    valid = validateQuantity(form, 'order-quantity') && valid;
  }
  valid = validatePickupDate(form, 'order-pickup') && valid;
  return valid;
}

function validateAddToCartForm(form) {
  return validateQuantity(form, 'order-quantity');
}

function validateTrackOrderForm(form) {
  let valid = true;
  valid = validateEmail(form, 'track-email') && valid;

  const orderField = form.querySelector('#track-order-id');
  const orderError = form.querySelector('#track-order-id-error');
  if (orderField && orderField.value.trim() !== '') {
    const value = parseInt(orderField.value, 10);
    if (Number.isNaN(value) || value < 1) {
      setFieldError(orderField, orderError, 'Please enter a valid order number.');
      valid = false;
    } else {
      clearFieldError(orderField, orderError);
    }
  } else if (orderField && orderError) {
    clearFieldError(orderField, orderError);
  }

  return valid;
}

function validateContactForm(form) {
  let valid = true;
  valid = validateRequired(form, 'contact-name', 'Name is required.') && valid;
  valid = validateEmail(form, 'contact-email') && valid;
  valid = validateRequired(form, 'contact-message', 'Please enter your message.') && valid;
  return valid;
}

function validateRequired(form, fieldId, message) {
  const field = form.querySelector('#' + fieldId);
  const errorEl = form.querySelector('#' + fieldId + '-error');
  if (!field) return true;

  const value = field.value.trim();
  if (!value) {
    setFieldError(field, errorEl, message);
    return false;
  }
  clearFieldError(field, errorEl);
  return true;
}

function validateEmail(form, fieldId) {
  const field = form.querySelector('#' + fieldId);
  const errorEl = form.querySelector('#' + fieldId + '-error');
  if (!field) return true;

  const value = field.value.trim();
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  if (!value) {
    setFieldError(field, errorEl, 'Email is required.');
    return false;
  }
  if (!emailRegex.test(value)) {
    setFieldError(field, errorEl, 'Please enter a valid email address.');
    return false;
  }
  clearFieldError(field, errorEl);
  return true;
}

function validatePhone(form, fieldId) {
  const field = form.querySelector('#' + fieldId);
  const errorEl = form.querySelector('#' + fieldId + '-error');
  if (!field) return true;

  const value = field.value.trim();
  const phoneRegex = /^[\d\s\-+().]{7,30}$/;

  if (!value) {
    setFieldError(field, errorEl, 'Phone number is required.');
    return false;
  }
  if (!phoneRegex.test(value)) {
    setFieldError(field, errorEl, 'Please enter a valid phone number.');
    return false;
  }
  clearFieldError(field, errorEl);
  return true;
}

function validatePickupDate(form, fieldId) {
  const field = form.querySelector('#' + fieldId);
  const errorEl = form.querySelector('#' + fieldId + '-error');
  if (!field) return true;

  const value = field.value;
  if (!value) {
    setFieldError(field, errorEl, 'Please select a pickup date.');
    return false;
  }

  const selected = new Date(value + 'T00:00:00');
  const today = new Date();
  today.setHours(0, 0, 0, 0);

  if (selected < today) {
    setFieldError(field, errorEl, 'Pickup date cannot be in the past.');
    return false;
  }
  clearFieldError(field, errorEl);
  return true;
}

function validateQuantity(form, fieldId) {
  const field = form.querySelector('#' + fieldId);
  const errorEl = form.querySelector('#' + fieldId + '-error');
  if (!field) return true;

  const value = parseInt(field.value, 10);
  if (Number.isNaN(value) || value < 1 || value > 99) {
    setFieldError(field, errorEl, 'Please enter a quantity between 1 and 99.');
    return false;
  }
  clearFieldError(field, errorEl);
  return true;
}

function setFieldError(field, errorEl, message) {
  const group = field.closest('.form-group');
  if (group) group.classList.add('form-group--error');
  if (errorEl) errorEl.textContent = message;
  field.setAttribute('aria-invalid', 'true');
}

function clearFieldError(field, errorEl) {
  const group = field.closest('.form-group');
  if (group) group.classList.remove('form-group--error');
  if (errorEl) errorEl.textContent = '';
  field.removeAttribute('aria-invalid');
}
