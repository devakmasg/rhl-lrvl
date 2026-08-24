/* ================= SHARED: runs on every page =================
   Header scroll state and back-to-top. Both are scroll-driven, so both are
   ScrollTriggers rather than window 'scroll' listeners — that keeps them on
   the same smoothed scroll position and the same frame as every other
   animation (see the header comment in scroll-animations.js).
   Scroll-reveal, letter stagger and background parallax live in
   scroll-animations.js; page-specific behaviour (hero slider, projects
   scroll, services accordion, testimonials carousel, stats counters) lives
   in its own file, included only where needed. */
(function(){
  const header = document.getElementById('siteHeader');
  const toTop = document.getElementById('toTop');
  const hasScrollTrigger = typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined';

  /* Plain class toggles at a fixed scroll depth, so these degrade honestly:
     without GSAP they fall back to a passive listener and the header still
     works — it just is not sharing Lenis' smoothed position. */
  function atScrollDepth(depth, onCross){
    const threshold = typeof depth === 'function' ? depth : () => depth;
    const update = () => onCross(window.scrollY > threshold());

    if(hasScrollTrigger){
      gsap.registerPlugin(ScrollTrigger);
      /* One page-length trigger that reports the position, rather than a
         trigger-less one whose start/end IS the threshold: the boundary form
         reported itself active at scroll 0 and lit both the scrolled header
         and the back-to-top button on a freshly loaded page. Reading
         window.scrollY per update is unambiguous, and re-reading `threshold`
         each time keeps a viewport-relative depth correct across resizes.
         This still runs inside ScrollTrigger.update(), so it shares Lenis'
         frame and smoothed position — the reason it is not a bare listener. */
      ScrollTrigger.create({ start: 0, end: 'max', onUpdate: update, onRefresh: update });
      update();
      return;
    }
    window.addEventListener('scroll', () => requestAnimationFrame(update), { passive: true });
    update();
  }

  if(header){
    atScrollDepth(40, (past) => header.classList.toggle('scrolled', past));
  }

  if(toTop){
    atScrollDepth(() => window.innerHeight * 1.2, (past) => toTop.classList.toggle('show', past));
    // Hand the trip back to Lenis when it is running, so the return journey
    // uses the same easing as every other scroll on the page instead of the
    // browser's native smooth-scroll curve.
    toTop.addEventListener('click', () => {
      if(window.lenis) window.lenis.scrollTo(0);
      else window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  /* ================= MOBILE NAV =================
     The six header links are hidden below 900px, so this panel is where they
     live. State is held on the toggle's aria-expanded rather than in a local
     variable — one source of truth that the CSS (the burger's cross), the
     screen reader and this code all read from the same place. */
  (function mobileNav(){
    const toggle = document.getElementById('navToggle');
    const panel = document.getElementById('mobileNav');
    if(!toggle || !panel) return;

    const isOpen = () => toggle.getAttribute('aria-expanded') === 'true';

    function setOpen(open){
      toggle.setAttribute('aria-expanded', String(open));
      toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
      panel.classList.toggle('is-open', open);
      /* inert, not hidden: the panel has to stay in the layout for its fade,
         but a closed panel must not be reachable by Tab or announced. */
      panel.toggleAttribute('inert', !open);
      document.body.classList.toggle('nav-open', open);
      // Lenis owns scrolling on this site; body{overflow:hidden} alone does
      // not stop it, so the lock has to go through Lenis when it is running.
      if(window.lenis){ open ? window.lenis.stop() : window.lenis.start(); }
    }

    setOpen(false);

    toggle.addEventListener('click', () => {
      const open = !isOpen();
      setOpen(open);
      // Move focus into the panel on open, and hand it back to the button on
      // close, so a keyboard user is never dropped at the top of the document.
      // The panel is visibility:hidden until .is-open lands, and a hidden
      // element cannot take focus — so the open case has to wait for the
      // style to be applied rather than firing in the same tick as the class.
      if(open) requestAnimationFrame(() => { const first = panel.querySelector('a'); if(first) first.focus(); });
      else toggle.focus();
    });

    // Tapping a link navigates; closing first means the panel is not still
    // open behind a same-page anchor like #story.
    panel.addEventListener('click', (e) => { if(e.target.closest('a')) setOpen(false); });

    document.addEventListener('keydown', (e) => {
      if(e.key === 'Escape' && isOpen()){ setOpen(false); toggle.focus(); }
    });

    // Rotating to landscape past the breakpoint hides the toggle; without this
    // the panel would be left open with no visible way to close it.
    matchMedia('(min-width:901px)').addEventListener('change', (e) => {
      if(e.matches && isOpen()) setOpen(false);
    });
  })();

  /* ================= MOBILE NAV: ABOUT ACCORDION =================
     The desktop "About" dropdown opens on hover; a touch device has no
     hover, so tapping it here expands its six sub-links inline in the same
     panel instead. The outer mobileNav's panel-click handler already closes
     the whole panel on any <a> tap — this only needs to handle the toggle
     button itself, which is not an <a> and so does not trigger that. */
  (function mobileNavAbout(){
    const group = document.querySelector('.mnav-group');
    const toggle = group && group.querySelector('.mnav-toggle');
    if(!group || !toggle) return;

    toggle.addEventListener('click', () => {
      const open = toggle.getAttribute('aria-expanded') !== 'true';
      toggle.setAttribute('aria-expanded', String(open));
      group.classList.toggle('is-open', open);
    });
  })();
})();
