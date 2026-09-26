/* ==========================================================================
   NYC ICON LIMO — animations.js
   Reveal-on-scroll and counters. Both degrade to "everything visible".
   ========================================================================== */

(function () {
  "use strict";

  var root = document.documentElement;
  var reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  var canObserve = "IntersectionObserver" in window;
  var reveals = document.querySelectorAll("[data-reveal]");

  function revealAll() {
    reveals.forEach(function (el) {
      el.classList.add("is-in");
    });
  }

  /* ---- Reveal on scroll ----
     Elements are only hidden while `js-anim` is on <html> (set by a small
     inline script in the head). If anything below fails we take the class
     off again, so content can never be stranded invisible. */
  if (reduced || !canObserve || !reveals.length) {
    root.classList.remove("js-anim");
  } else {
    var observer = new IntersectionObserver(
      function (entries, obs) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) return;
          entry.target.classList.add("is-in");
          obs.unobserve(entry.target);
        });
      },
      /* A single visible pixel is enough. Thresholds above 0 can leave very
         tall elements unrevealed on short viewports. */
      { threshold: 0, rootMargin: "0px 0px -12% 0px" }
    );

    reveals.forEach(function (el) {
      observer.observe(el);
    });

    /* Safety net: whatever happens, nothing stays hidden past three seconds. */
    window.setTimeout(revealAll, 3000);
  }

  /* ---- Counters ---- */
  var counters = document.querySelectorAll("[data-count]");

  function runCounter(el) {
    var target = parseFloat(el.getAttribute("data-count"));
    var suffix = el.getAttribute("data-count-suffix") || "";

    if (isNaN(target)) return;

    if (reduced) {
      el.textContent = target + suffix;
      return;
    }

    var duration = 1400;
    var started = null;

    function frame(now) {
      if (started === null) started = now;
      var progress = Math.min((now - started) / duration, 1);
      var eased = 1 - Math.pow(1 - progress, 3);
      el.textContent = Math.round(eased * target) + suffix;
      if (progress < 1) window.requestAnimationFrame(frame);
    }

    window.requestAnimationFrame(frame);
  }

  if (counters.length) {
    if (!canObserve) {
      counters.forEach(runCounter);
    } else {
      var countObserver = new IntersectionObserver(
        function (entries, obs) {
          entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;
            runCounter(entry.target);
            obs.unobserve(entry.target);
          });
        },
        { threshold: 0.4 }
      );
      counters.forEach(function (el) {
        countObserver.observe(el);
      });
    }
  }
})();
