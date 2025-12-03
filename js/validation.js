document.addEventListener("DOMContentLoaded", () => {
  const byId = (id) => document.getElementById(id);

  const attach = (formId, handler) => {
    const f = byId(formId);
    if (!f) return;
    f.addEventListener("submit", (e) => {
      const ok = handler(f);
      if (!ok) e.preventDefault();
    });
  };

  const setError = (input, message) => {
    if (!input) return false;
    input.setCustomValidity(message || "");
    input.reportValidity();
    if (message) input.focus();
    return !message;
  };

  attach("registerForm", (f) => {
    const name = f.querySelector('[name="name"]');
    const email = f.querySelector('[name="email"]');
    const pass = f.querySelector('[name="password"]');
    const confirm = f.querySelector('[name="confirm_password"]');

    if (!name.value.trim()) return setError(name, "Name is required");
    if (!email.validity.valid) return setError(email, "Provide a valid email");
    if ((pass.value || "").length < 6)
      return setError(pass, "Password must be at least 6 characters");
    if (pass.value !== confirm.value)
      return setError(confirm, "Passwords do not match");
    [name, email, pass, confirm].forEach((i) => setError(i, ""));
    return true;
  });

  attach("loginForm", (f) => {
    const email = f.querySelector('[name="email"]');
    const pass = f.querySelector('[name="password"]');
    if (!email.validity.valid) return setError(email, "Provide a valid email");
    if (!pass.value) return setError(pass, "Password is required");
    [email, pass].forEach((i) => setError(i, ""));
    return true;
  });

  attach("productForm", (f) => {
    const name = f.querySelector('[name="name"]');
    const price = f.querySelector('[name="price"]');
    const stock = f.querySelector('[name="stock"]');
    if (!name.value.trim()) return setError(name, "Name is required");
    if (Number(price.value) < 0)
      return setError(price, "Price must be 0 or greater");
    if (!Number.isInteger(Number(stock.value)) || Number(stock.value) < 0)
      return setError(stock, "Stock must be 0 or greater");
    [name, price, stock].forEach((i) => setError(i, ""));
    return true;
  });

  attach("editProductForm", (f) => {
    const name = f.querySelector('[name="name"]');
    const price = f.querySelector('[name="price"]');
    const stock = f.querySelector('[name="stock"]');
    if (!name.value.trim()) return setError(name, "Name is required");
    if (Number(price.value) < 0)
      return setError(price, "Price must be 0 or greater");
    if (!Number.isInteger(Number(stock.value)) || Number(stock.value) < 0)
      return setError(stock, "Stock must be 0 or greater");
    [name, price, stock].forEach((i) => setError(i, ""));
    return true;
  });
});
