/* ==========================================================================
   NYC ICON LIMO — main.js
   Header scroll state, mobile drawer, active-page marking, footer year.
   ========================================================================== */

(function () {
  "use strict";

  /* ---- Header: transparent over the hero, solid once scrolled ---- */
  var header = document.querySelector(".site-header");

  if (header) {
    var setHeaderState = function () {
      header.classList.toggle("is-stuck", window.scrollY > 24);
    };
    setHeaderState();
    window.addEventListener("scroll", setHeaderState, { passive: true });
  }

  /* ---- Mobile drawer ---- */
  var toggle = document.querySelector(".nav__toggle");
  var drawer = document.getElementById("mobile-drawer");

  if (toggle && drawer) {
    var setDrawer = function (open) {
      toggle.setAttribute("aria-expanded", String(open));
      toggle.setAttribute("aria-label", open ? "Close navigation menu" : "Open navigation menu");
      drawer.classList.toggle("is-open", open);
      drawer.setAttribute("aria-hidden", String(!open));
      document.body.style.overflow = open ? "hidden" : "";
      /* The drawer sits on ink, so the header must read as solid behind it. */
      if (header) header.classList.toggle("is-stuck", open || window.scrollY > 24);
    };

    toggle.addEventListener("click", function () {
      setDrawer(toggle.getAttribute("aria-expanded") !== "true");
    });

    drawer.querySelectorAll("a").forEach(function (link) {
      link.addEventListener("click", function () {
        setDrawer(false);
      });
    });

    document.addEventListener("keydown", function (event) {
      if (event.key === "Escape" && toggle.getAttribute("aria-expanded") === "true") {
        setDrawer(false);
        toggle.focus();
      }
    });

    /* A resize past the desktop breakpoint would leave the drawer stranded.
       Kept in step with the 1100px breakpoint in components.css. */
    window.addEventListener("resize", function () {
      if (window.innerWidth > 1100 && toggle.getAttribute("aria-expanded") === "true") {
        setDrawer(false);
      }
    });
  }

  /* ---- Mark the current page across nav, drawer and footer ---- */
  var here = window.location.pathname.split("/").pop() || "index.html";

  document.querySelectorAll(".nav__link, .drawer__nav a, .footer__links a").forEach(function (link) {
    var href = (link.getAttribute("href") || "").split("#")[0];
    if (href && href === here) {
      link.setAttribute("aria-current", "page");
    }
  });

  /* ---- Footer year ---- */
  var year = document.getElementById("current-year");
  if (year) {
    year.textContent = new Date().getFullYear();
  }
})();
