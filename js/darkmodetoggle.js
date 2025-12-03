(() => {
  "use strict";

  const getStoredTheme = () => localStorage.getItem("theme");

  const setTheme = (theme) => {
    let effectiveTheme = theme;
    if (theme === "auto") {
      effectiveTheme = window.matchMedia("(prefers-color-scheme: dark)").matches
        ? "dark"
        : "light";
    }
    document.documentElement.setAttribute("data-bs-theme", effectiveTheme);
  };

  const getPreferredTheme = () => {
    const stored = getStoredTheme();
    if (stored) return stored;
    return "auto";
  };

  // Sets theme immediately to prevent flash
  setTheme(getPreferredTheme());

  // When DOM is ready, update buttons UI and attach listeners
  document.addEventListener("DOMContentLoaded", () => {
    const currentTheme = getPreferredTheme();

    document.querySelectorAll("[data-bs-theme-value]").forEach((button) => {
      const isActive =
        button.getAttribute("data-bs-theme-value") === currentTheme;
      button.classList.toggle("active", isActive);
      button.setAttribute("aria-pressed", isActive);

      button.addEventListener("click", () => {
        const selectedTheme = button.getAttribute("data-bs-theme-value");
        localStorage.setItem("theme", selectedTheme);
        setTheme(selectedTheme);

        document.querySelectorAll("[data-bs-theme-value]").forEach((btn) => {
          const isNowActive =
            btn.getAttribute("data-bs-theme-value") === selectedTheme;
          btn.classList.toggle("active", isNowActive);
          btn.setAttribute("aria-pressed", isNowActive);
        });
      });
    });
  });

  // Listens the system theme changes when it is on Auto mode
  window
    .matchMedia("(prefers-color-scheme: dark)")
    .addEventListener("change", () => {
      if (getStoredTheme() === "auto") {
        setTheme("auto");
      }
    });
})();
