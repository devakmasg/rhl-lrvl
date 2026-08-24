/* ================= MOTION SYSTEM (loaded on every page) =================
   One owner for scroll-linked and reveal motion, so the page has exactly
   ONE scroll authority instead of several listeners each reading a slightly
   different scroll position on a different frame:

     Lenis          smooths the native window scroll. It does not hijack
                    scrolling into a transformed wrapper, so window.scrollY
                    and getBoundingClientRect() stay truthful.
     GSAP ticker    drives Lenis' rAF loop, so Lenis and every GSAP tween
                    advance on one shared frame.
     ScrollTrigger  owns every scroll-linked animation on the site — text
                    reveals, letter staggers, background parallax/scaling,
                    header state. It is updated from Lenis' scroll event, so
                    scrubbed animations track the SMOOTHED position rather
                    than the raw one (that is what stops parallax from
                    juddering a frame behind the content it sits under).
     SplitText      splits headings into lines/words/chars for the staggers.

   Rule of thumb for anything added later: if it reacts to scroll, it belongs
   in a ScrollTrigger — here or in a page script — never in a bare window
   'scroll' listener, which runs off the unsmoothed position on its own frame.
   Mouse-driven motion is the exception and stays on plain listeners + quickTo
   (see the hero parallax in home.js); it has nothing to do with scroll. */
(function(){
  const root = document.documentElement;

  /* .anim-ready is added by an inline <head> script and is what activates
     every "starts hidden" rule in the CSS. Dropping it here is the failsafe:
     if GSAP is missing, or the user asked for reduced motion, the class goes
     away and the page renders as ordinary, fully visible content. */
  function showEverything(){ root.classList.remove('anim-ready'); }

  if(typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined'){ showEverything(); return; }
  gsap.registerPlugin(ScrollTrigger);

  if(matchMedia('(prefers-reduced-motion: reduce)').matches){ showEverything(); return; }

  /* ================= LENIS: SMOOTH SCROLL, SYNCED TO GSAP ================= */
  if(typeof Lenis !== 'undefined'){
    const lenis = new Lenis({
      duration: 1.2,
      easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)), // exponential ease-out — luxury/"heavy" scroll feel
      smoothWheel: true
    });
    lenis.on('scroll', ScrollTrigger.update);
    gsap.ticker.add((time) => { lenis.raf(time * 1000); });
    gsap.ticker.lagSmoothing(0);
    // Published so programmatic jumps (back-to-top, anchor links) can be
    // handed to Lenis instead of racing it with a native smooth scroll.
    window.lenis = lenis;
  }

  /* ================= LETTER / WORD STAGGER ================= */
  const CAN_SPLIT = typeof SplitText !== 'undefined';

  /* Short headings read beautifully letter by letter; long ones turn into a
     crawl, because stagger cost grows with character count while the reader's
     patience does not. So the unit is chosen from the length — roughly one
     line's worth staggers by letter, anything longer by word. `data-split`
     ("chars" | "words" | "none") overrides per element. */
  const CHAR_LIMIT = 40;

  function buildStagger(el){
    if(!CAN_SPLIT) return null;
    const explicit = el.dataset.split;
    if(explicit === 'none') return null;
    const mode = explicit || (el.textContent.trim().length > CHAR_LIMIT ? 'words' : 'chars');
    const split = new SplitText(el, {
      type: 'lines,words,chars',
      mask: 'lines', // each line gets an overflow-hidden wrapper, so letters rise out of nothing
      aria: 'auto'   // parent keeps an aria-label; the generated pieces are hidden from assistive tech
    });
    const targets = mode === 'chars' ? split.chars : split.words;
    return targets && targets.length ? { targets: targets, mode: mode } : null;
  }

  /* One entry point for every heading reveal. Falls back to a plain fade-up
     when SplitText is unavailable or there is nothing to split, so a heading
     is never left invisible just because the stagger could not be built. */
  function revealHeading(el, vars){
    el.style.pointerEvents = 'auto'; // .reveal-up parks these at none until revealed
    const stagger = buildStagger(el);
    if(!stagger){
      return gsap.fromTo(el,
        { opacity: 0, y: 40 },
        Object.assign({ opacity: 1, y: 0, duration: .9, ease: 'power3.out' }, vars)
      );
    }
    gsap.set(el, { opacity: 1 }); // the pieces, not the heading, carry the reveal from here on
    // Letters travel further than one line-height so they clear the mask even
    // on the tallest line, and the per-letter offset stays small — the whole
    // word should feel like one gesture, not a typewriter.
    return gsap.fromTo(stagger.targets,
      { yPercent: 115, opacity: 0 },
      Object.assign({
        yPercent: 0, opacity: 1,
        duration: stagger.mode === 'chars' ? .9 : 1,
        ease: 'power4.out',
        stagger: stagger.mode === 'chars' ? .022 : .06
      }, vars)
    );
  }

  /* ================= REVEALS ================= */
  function buildReveals(){
    /* ---- on load: hero and page-header headings, no scroll required ---- */
    gsap.utils.toArray('[data-reveal="load"]').forEach((el, i) => {
      revealHeading(el, { delay: .25 + i * .12 });
    });

    /* ---- on scroll: headings stagger, body copy fades up as one block ----
       Splitting a paragraph into 300 letters buys nothing and costs a lot of
       DOM, so the stagger is reserved for headings. */
    gsap.utils.toArray('.reveal-up').forEach((el) => {
      const scrollTrigger = { trigger: el, start: 'top 85%', once: true };
      if(/^H[1-3]$/.test(el.tagName)){
        revealHeading(el, { scrollTrigger: scrollTrigger });
      } else {
        gsap.fromTo(el,
          { opacity: 0, y: 50 },
          {
            opacity: 1, y: 0, duration: 1, ease: 'power3.out',
            scrollTrigger: scrollTrigger,
            onStart(){ el.style.pointerEvents = 'auto'; }
          }
        );
      }
    });

    /* ---- grids and cards: staggered in batches as each row enters ---- */
    const cards = gsap.utils.toArray('.reveal-card');
    if(cards.length){
      gsap.set(cards, { opacity: 0, y: 50 });
      ScrollTrigger.batch(cards, {
        start: 'top 85%',
        once: true,
        onEnter: (batch) => gsap.to(batch, {
          opacity: 1, y: 0, duration: .9, ease: 'power3.out', stagger: .15, overwrite: true,
          onStart(){ this.targets().forEach((el) => { el.style.pointerEvents = 'auto'; }); }
        })
      });
    }

    // Splitting rewrites the DOM of every heading, which changes page height:
    // every trigger's start/end has to be measured again afterwards.
    ScrollTrigger.refresh();
  }

  /* SplitText measures line breaks, so it has to run against the real
     webfont — split too early and the lines are cut for Times New Roman and
     the masks land in the wrong places. The timeout is the safety net: if the
     font never arrives, reveal the text anyway rather than leave it hidden. */
  const fontsReady = (document.fonts && document.fonts.ready)
    ? Promise.race([document.fonts.ready, new Promise((resolve) => setTimeout(resolve, 1500))])
    : Promise.resolve();
  fontsReady.then(buildReveals).catch(() => { showEverything(); });

  /* ================= BACKGROUND PARALLAX + SCALING ================= */
  /* Both of these are `scrub: true` rather than timed tweens: the point is a
     rigid link between scroll position and offset, which is also why they
     have to run off Lenis' smoothed position — a raw listener lands a frame
     behind the content sliding over the top, and that gap reads as judder.
     Offsets are yPercent (a share of the element's own height), never pixels,
     so one declared speed behaves the same on a phone and on a 4K monitor.

     How far they may travel is measured, not guessed. These elements are
     deliberately oversized by their CSS (.intro-media img is 120% at inset
     -10%; .stats-bg is inset -12% -2%) and that overhang is the entire travel
     budget: drift further and the parent's edge slides into view at the
     extremes of the scroll. Note the budget is a share of the ELEMENT's
     height, not the parent's — a 10%-of-parent overhang on a 120%-tall image
     is only 8.3% of the image, and clamping to the wrong one of those two
     opens a visible gap at the end of the scroll. */
  function travelBudget(el){
    const parent = el.parentElement;
    if(!parent || !el.offsetHeight) return 0;
    const overhang = (el.offsetHeight - parent.clientHeight) / 2;
    return Math.max(overhang / el.offsetHeight * 100 - 0.5, 0); // half a percent of margin for rounding
  }

  function parallax(el, speed, vars){
    // Function-based values + invalidateOnRefresh: re-measured on resize and
    // once late-loading images have given the element its real height.
    const shift = () => Math.min(speed * 100, travelBudget(el));
    gsap.fromTo(el,
      Object.assign({ yPercent: () => -shift() }, vars && vars.from),
      Object.assign({
        yPercent: () => shift(), ease: 'none',
        scrollTrigger: {
          trigger: el.parentElement || el,
          start: 'top bottom', end: 'bottom top',
          scrub: true, invalidateOnRefresh: true
        }
      }, vars && vars.to)
    );
  }

  // [data-parallax] — foreground media that drifts against the page.
  gsap.utils.toArray('[data-parallax]').forEach((el) => {
    parallax(el, parseFloat(el.dataset.parallax) || 0.15);
  });

  // [data-parallax-bg] — full-bleed section backdrops. These also scale down
  // as they pass, so the backdrop settles rather than just sliding: the drift
  // reads as depth, the scale reads as arrival. Scale only ever goes above 1,
  // so it adds coverage and never eats into the travel budget.
  gsap.utils.toArray('[data-parallax-bg]').forEach((el) => {
    parallax(el, parseFloat(el.dataset.parallaxBg) || 0.2, {
      from: { scale: 1.12 },
      to: { scale: 1 }
    });
  });

  /* [data-parallax-header] — the photographic band at the top of every inner
     page. It needs its own range rather than the shared one above: those run
     'top bottom' → 'bottom top', which assumes the element scrolls UP into
     view. A page header is already at the top of the document, so that range
     is ~65% elapsed before the user has scrolled at all, and the image would
     load already displaced and mid-zoom.
     Running 'top top' → 'bottom top' instead means the header sits at rest on
     arrival and parallaxes only as it leaves: drifting down (slower than the
     page, the classic effect) while easing up in scale. */
  gsap.utils.toArray('[data-parallax-header]').forEach((el) => {
    parallax(el, parseFloat(el.dataset.parallaxHeader) || 0.2, {
      from: { yPercent: 0, scale: 1 },
      to: {
        scale: 1.14,
        scrollTrigger: {
          trigger: el.parentElement || el,
          start: 'top top', end: 'bottom top',
          scrub: true, invalidateOnRefresh: true
        }
      }
    });
  });
})();
