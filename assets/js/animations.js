/**
 * animations.js — Double Tap Protect
 *
 * Fade-in on scroll via IntersectionObserver.
 * Elements with class `.fade-in` start invisible and animate in
 * when they enter the viewport.
 *
 * Respects prefers-reduced-motion by skipping the animation.
 */

(function () {
  'use strict';

  // Respect accessibility preference: skip animations entirely
  const prefersReducedMotion = window.matchMedia(
    '(prefers-reduced-motion: reduce)'
  ).matches;

  if (prefersReducedMotion) {
    // Make all fade-in elements immediately visible
    document.querySelectorAll('.fade-in').forEach(function (el) {
      el.classList.add('visible');
    });
    return;
  }

  if (!('IntersectionObserver' in window)) {
    // Fallback for browsers without IntersectionObserver support
    document.querySelectorAll('.fade-in').forEach(function (el) {
      el.classList.add('visible');
    });
    return;
  }

  const observer = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          observer.unobserve(entry.target);
        }
      });
    },
    {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px',
    }
  );

  function initObserver() {
    document.querySelectorAll('.fade-in').forEach(function (el) {
      observer.observe(el);
    });
  }

  // Run on DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initObserver);
  } else {
    initObserver();
  }

})();
