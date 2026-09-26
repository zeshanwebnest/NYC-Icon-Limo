/* ==========================================================================
   NYC ICON LIMO — components.js
   Accordion, testimonial slider, fleet filter tabs, booking handoff,
   and client-side form validation.
   ========================================================================== */

(function () {
  "use strict";

  /* ======================================================================
     Accordion
     Independent toggling by default; data-accordion="single" makes a group
     exclusive (used for short FAQ previews).
     ====================================================================== */
  document.querySelectorAll(".accordion").forEach(function (group) {
    var exclusive = group.getAttribute("data-accordion") === "single";
    var triggers = Array.prototype.slice.call(group.querySelectorAll(".acc-item__trigger"));

    triggers.forEach(function (trigger) {
      trigger.addEventListener("click", function () {
        var panel = document.getElementById(trigger.getAttribute("aria-controls"));
        var open = trigger.getAttribute("aria-expanded") === "true";

        if (exclusive) {
          triggers.forEach(function (other) {
            if (other === trigger) return;
            other.setAttribute("aria-expanded", "false");
            var otherPanel = document.getElementById(other.getAttribute("aria-controls"));
            if (otherPanel) otherPanel.setAttribute("data-open", "false");
          });
        }

        trigger.setAttribute("aria-expanded", String(!open));
        if (panel) panel.setAttribute("data-open", String(!open));
      });
    });
  });

  /* ======================================================================
     Fleet filter tabs
     ====================================================================== */
  document.querySelectorAll("[data-filter]").forEach(function (root) {
    var buttons = Array.prototype.slice.call(root.querySelectorAll("[data-filter-btn]"));
    var items = Array.prototype.slice.call(root.querySelectorAll("[data-category]"));

    if (!buttons.length || !items.length) return;

    buttons.forEach(function (button) {
      button.addEventListener("click", function () {
        var wanted = button.getAttribute("data-filter-btn");

        buttons.forEach(function (other) {
          var active = other === button;
          other.classList.toggle("is-active", active);
          other.setAttribute("aria-pressed", String(active));
        });

        items.forEach(function (item) {
          var show = wanted === "all" || item.getAttribute("data-category") === wanted;
          item.hidden = !show;
        });
      });
    });
  });

  /* ======================================================================
     Testimonial slider
     ====================================================================== */
  document.querySelectorAll("[data-quotes]").forEach(function (root) {
    var track = root.querySelector(".quotes__track");
    var slides = track ? Array.prototype.slice.call(track.children) : [];
    var dotsWrap = root.querySelector(".quotes__dots");
    var index = 0;
    var timer = null;

    if (!track || slides.length < 2) return;

    var dots = slides.map(function (_, i) {
      var dot = document.createElement("button");
      dot.type = "button";
      dot.setAttribute("aria-label", "Show testimonial set " + (i + 1));
      dot.addEventListener("click", function () {
        stop();
        go(i);
      });
      if (dotsWrap) dotsWrap.appendChild(dot);
      return dot;
    });

    function render() {
      track.style.transform = "translateX(-" + index * 100 + "%)";
      slides.forEach(function (slide, i) {
        slide.setAttribute("aria-hidden", String(i !== index));
      });
      dots.forEach(function (dot, i) {
        dot.classList.toggle("is-active", i === index);
      });
    }

    function go(i) {
      index = (i + slides.length) % slides.length;
      render();
    }

    function start() {
      if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) return;
      stop();
      timer = window.setInterval(function () {
        go(index + 1);
      }, 7000);
    }

    function stop() {
      if (timer) window.clearInterval(timer);
      timer = null;
    }

    root.addEventListener("mouseenter", stop);
    root.addEventListener("mouseleave", start);
    root.addEventListener("focusin", stop);

    render();
    start();
  });

  /* ======================================================================
     Date inputs never accept a pickup in the past
     ====================================================================== */
  var today = new Date();
  var minDate =
    today.getFullYear() +
    "-" +
    String(today.getMonth() + 1).padStart(2, "0") +
    "-" +
    String(today.getDate()).padStart(2, "0");

  document.querySelectorAll('input[type="date"]').forEach(function (input) {
    if (!input.getAttribute("min")) input.setAttribute("min", minDate);
  });

  /* ======================================================================
     Booking handoff
     The hero booking widget is a plain GET form pointing at the contact
     page, so it works without JavaScript. This reads those parameters back
     and pre-fills the full reservation form.
     ====================================================================== */
  var params = new URLSearchParams(window.location.search);

  if (params.toString()) {
    params.forEach(function (value, key) {
      var field = document.querySelector('[data-prefill="' + key + '"]');
      if (!field || !value) return;

      if (field.tagName === "SELECT") {
        var match = Array.prototype.slice.call(field.options).filter(function (option) {
          return option.value.toLowerCase() === value.toLowerCase();
        })[0];
        if (match) field.value = match.value;
      } else {
        field.value = value;
      }
    });

    var target = document.querySelector("form[data-validate]");
    if (target) {
      window.requestAnimationFrame(function () {
        target.scrollIntoView({ block: "center", behavior: "auto" });
      });
    }
  }

  /* ======================================================================
     Form validation
     ====================================================================== */
  document.querySelectorAll("form[data-validate]").forEach(function (form) {
    var required = Array.prototype.slice.call(form.querySelectorAll("[required]"));

    required.forEach(function (field) {
      field.addEventListener("input", function () {
        if (field.getAttribute("aria-invalid") === "true" && field.value.trim()) {
          field.setAttribute("aria-invalid", "false");
        }
      });
    });

    form.addEventListener("submit", function (event) {
      event.preventDefault();
      var firstInvalid = null;

      required.forEach(function (field) {
        var empty = !field.value || !field.value.trim();
        var badEmail =
          field.type === "email" && field.value && !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(field.value);
        var invalid = empty || badEmail;
        field.setAttribute("aria-invalid", String(invalid));
        if (invalid && !firstInvalid) firstInvalid = field;
      });

      if (firstInvalid) {
        firstInvalid.focus();
        return;
      }

      var success = form.querySelector(".form-success");
      form.reset();

      if (success) {
        success.classList.add("is-visible");
        success.scrollIntoView({ block: "nearest", behavior: "smooth" });
        window.setTimeout(function () {
          success.classList.remove("is-visible");
        }, 9000);
      }
    });
  });
})();
