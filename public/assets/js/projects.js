/* ================= PROJECTS PAGE ONLY =================
   Two independent blocks: the sticky horizontal-scroll showcase at the top,
   and the filterable portfolio grid below it. Loaded only by projects.html,
   after main.js. */

/* ================= STICKY HORIZONTAL SHOWCASE ================= */
(function(){
  const section = document.getElementById('projects');
  const track = document.getElementById('projectsTrack');
  const bar = document.getElementById('projectsProgressBar');
  if(!section || !track) return;

  function setHeight(){
    const trackWidth = track.scrollWidth;
    const viewportWidth = window.innerWidth;
    const scrollable = Math.max(trackWidth - viewportWidth + parseFloat(getComputedStyle(document.documentElement).fontSize)*0, 0);
    const extraVh = Math.max(scrollable / window.innerHeight * 100, 40);
    section.style.height = `calc(100vh + ${extraVh}vh)`;
    return scrollable;
  }
  let scrollableWidth = setHeight();
  window.addEventListener('resize', () => { scrollableWidth = setHeight(); });

  let ticking = false;
  function onScroll(){
    if(ticking) return;
    ticking = true;
    requestAnimationFrame(() => {
      const rect = section.getBoundingClientRect();
      const total = section.offsetHeight - window.innerHeight;
      if(total > 0){
        const scrolled = Math.min(Math.max(-rect.top, 0), total);
        const progress = scrolled / total;
        track.style.transform = `translateX(-${progress * scrollableWidth}px)`;
        bar.style.width = (progress * 100) + '%';
      }
      ticking = false;
    });
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
})();

/* ================= PORTFOLIO FILTERS =================
   Status (radio) + property type (radio) + location (select) + free-text
   search, combined with AND. The cards are authored in the HTML with
   data-status / data-type / data-location, so this module only ever decides
   which of them to show — it never builds markup, and the page is complete
   and readable if this script never runs at all.

   Every control is read from the DOM at filter time rather than mirrored into
   module state. There is then only one source of truth for "what is selected",
   which is what makes Clear trivially correct: reset the form, re-read, done —
   no second copy of the state to forget to reset. */
(function(){
  const form = document.getElementById('projectFilters');
  const grid = document.getElementById('portfolioGrid');
  if(!form || !grid) return;

  const cards = Array.from(grid.querySelectorAll('.pcard'));
  const countEl = document.getElementById('filterCount');
  const emptyEl = document.getElementById('portfolioEmpty');
  const searchEl = document.getElementById('f-search');
  const locationEl = document.getElementById('f-location');
  const canAnimate = typeof gsap !== 'undefined' && !matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* Search text is precomputed once per card: the card's own visible text plus
     its data attributes, so a query matches anything the user can actually see
     ("gulshan", "ongoing", "logistics") without maintaining a parallel index
     that can drift out of step with the markup. */
  const haystacks = new Map(cards.map((card) => [
    card,
    (card.textContent + ' ' + card.dataset.status + ' ' + card.dataset.type + ' ' + card.dataset.location)
      .toLowerCase().replace(/\s+/g, ' ')
  ]));

  function matches(card, state){
    if(state.status !== 'all' && card.dataset.status !== state.status) return false;
    if(state.type !== 'all' && card.dataset.type !== state.type) return false;
    if(state.location !== 'all' && card.dataset.location !== state.location) return false;
    // every term must appear somewhere, so "gulshan tower" narrows rather than widens
    return state.terms.every((term) => haystacks.get(card).indexOf(term) > -1);
  }

  function readState(){
    const data = new FormData(form);
    return {
      status: data.get('status') || 'all',
      type: data.get('type') || 'all',
      location: data.get('location') || 'all',
      terms: String(data.get('search') || '').toLowerCase().split(/\s+/).filter(Boolean)
    };
  }

  function apply(animate){
    const state = readState();
    const shown = [];
    const revealed = [];

    cards.forEach((card) => {
      const wasHidden = card.classList.contains('is-filtered-out');
      const show = matches(card, state);
      card.classList.toggle('is-filtered-out', !show);
      if(show){
        shown.push(card);
        if(wasHidden) revealed.push(card); // only cards that just came back get an entrance
      }
    });

    countEl.textContent = shown.length === cards.length
      ? `Showing all ${cards.length} developments`
      : `Showing ${shown.length} of ${cards.length} developments`;
    emptyEl.hidden = shown.length > 0;

    /* Only cards that just came BACK get an entrance. Cards already on screen
       stay put — animating those too would make an unrelated corner of the
       grid twitch on every keystroke. And nothing is forced visible here: on
       the first pass that would overwrite the hidden state the scroll
       entrance below depends on, and the grid would simply be there already. */
    if(animate && canAnimate && revealed.length){
      gsap.fromTo(revealed,
        { opacity: 0, y: 18 },
        { opacity: 1, y: 0, duration: .45, ease: 'power3.out', stagger: .035, overwrite: true }
      );
    }

    /* Showing or hiding cards changes the page height, which invalidates every
       trigger measured below this grid — including the footer reveals. */
    if(typeof ScrollTrigger !== 'undefined') ScrollTrigger.refresh();
  }

  /* One listener on the form covers the radios, the select AND the search box:
     'input' fires for all of them, which is also what makes the search feel
     instant without a keyup handler of its own. */
  form.addEventListener('input', () => apply(true));
  // A search field's native clear (the ✕) fires 'search' in Safari, not 'input'.
  if(searchEl) searchEl.addEventListener('search', () => apply(true));
  // Enter in the search box must not reload the page.
  form.addEventListener('submit', (e) => { e.preventDefault(); apply(true); });

  function clearAll(){
    form.reset();               // restores the two `checked` radios
    if(searchEl) searchEl.value = '';
    if(locationEl) locationEl.value = 'all';
    apply(true);
  }
  const clearBtn = document.getElementById('filterClear');
  const emptyClear = document.getElementById('emptyClear');
  if(clearBtn) clearBtn.addEventListener('click', clearAll);
  if(emptyClear) emptyClear.addEventListener('click', clearAll);

  /* Deep links: projects.html?status=ongoing lands with that filter already
     applied, so a homepage "View all" link can point straight at the right
     slice of the portfolio instead of the unfiltered grid. Only a known
     status value is honoured — anything else falls through to "all" rather
     than leaving the form in a state with no matching radio checked. */
  (function applyStatusFromUrl(){
    const status = new URLSearchParams(location.search).get('status');
    if(!status) return;
    const radio = form.querySelector(`input[name="status"][value="${status}"]`);
    if(radio) radio.checked = true;
  })();

  /* Initial state. The grid is far below a 100vh pinned section, so hiding the
     cards here cannot flash — nothing above the fold is affected. */
  if(canAnimate && typeof ScrollTrigger !== 'undefined'){
    gsap.set(cards, { opacity: 0, y: 28 });
    ScrollTrigger.batch(cards, {
      start: 'top 88%',
      once: true,
      onEnter: (batch) => gsap.to(batch, { opacity: 1, y: 0, duration: .8, ease: 'power3.out', stagger: .08, overwrite: true })
    });
  }
  apply(false);
})();
