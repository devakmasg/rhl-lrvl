/* ================= PARTNERS PAGE ONLY =================
   Audience switch between the Landowners and Investors panels.
   Loaded only by partners.html, after main.js.

   Built as an ARIA tablist rather than two buttons that toggle a class: the
   panels are genuinely alternative views of the same section, which is exactly
   what the tab pattern describes. That buys correct semantics for screen
   readers and, with the arrow-key handling below, the keyboard behaviour
   people expect from tabs — roving focus, not one tab stop per panel. */
(function(){
  const list = document.getElementById('audienceSwitch');
  if(!list) return;
  const tabs = Array.from(list.querySelectorAll('[role="tab"]'));
  if(!tabs.length) return;

  function panelFor(tab){ return document.getElementById(tab.getAttribute('aria-controls')); }

  function select(tab, focus){
    tabs.forEach((t) => {
      const active = t === tab;
      t.setAttribute('aria-selected', String(active));
      // Roving tabindex: only the selected tab is in the tab order, so Tab
      // moves past the whole group instead of through every option.
      t.tabIndex = active ? 0 : -1;
      const panel = panelFor(t);
      if(panel) panel.hidden = !active;
    });
    if(focus) tab.focus();

    // Swapping panels changes page height, so anything measured below this
    // section — the reveals, the footer — has to be re-measured.
    if(typeof ScrollTrigger !== 'undefined') ScrollTrigger.refresh();

    if(typeof gsap !== 'undefined' && !matchMedia('(prefers-reduced-motion: reduce)').matches){
      const panel = panelFor(tab);
      if(panel){
        const items = panel.querySelectorAll('.pillar, .step');
        gsap.fromTo(items,
          { opacity: 0, y: 20 },
          { opacity: 1, y: 0, duration: .5, ease: 'power3.out', stagger: .035, overwrite: true }
        );
      }
    }
  }

  tabs.forEach((tab) => {
    tab.addEventListener('click', () => select(tab, false));
    tab.addEventListener('keydown', (e) => {
      const i = tabs.indexOf(tab);
      let next = null;
      if(e.key === 'ArrowRight' || e.key === 'ArrowDown') next = tabs[(i + 1) % tabs.length];
      else if(e.key === 'ArrowLeft' || e.key === 'ArrowUp') next = tabs[(i - 1 + tabs.length) % tabs.length];
      else if(e.key === 'Home') next = tabs[0];
      else if(e.key === 'End') next = tabs[tabs.length - 1];
      if(next){ e.preventDefault(); select(next, true); }
    });
  });

  /* Deep links: #investors / #landowners open that panel directly, so the
     "Landowners" and "Investors" cards elsewhere on the site can each point at
     the view they promise instead of dropping everyone on the same one. */
  function fromHash(){
    const id = (location.hash || '').replace('#', '');
    const match = tabs.find((t) => t.getAttribute('aria-controls') === 'panel-' + id || t.id === 'tab-' + id);
    if(match) select(match, false);
  }
  window.addEventListener('hashchange', fromHash);
  fromHash();
})();
