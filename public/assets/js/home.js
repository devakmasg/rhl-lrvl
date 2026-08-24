/* ================= HOME PAGE ONLY =================
   Hero slider (parallax-layered, autoplay + arrows/dots) and the
   scrolling marquee. Loaded only by index.html, after main.js. */
(function(){
  const header = document.getElementById('siteHeader');
  const heroEl = document.getElementById('hero');

  /* scroll-animations.js opts the whole GSAP layer out when this is set, but
     every slider on this page is Swiper's own autoplay and would keep running
     regardless — carousels that advance on a timer are exactly what the
     preference is asking us to stop. Read once: a mid-session change to the
     OS setting is not worth re-wiring five sliders for. */
  const REDUCED = matchMedia('(prefers-reduced-motion: reduce)').matches;
  const autoplay = (delay) => REDUCED ? false : { delay: delay, disableOnInteraction: false };
  // Arrow keys drive whichever slider is on screen — the arrow buttons are
  // reachable by Tab, but this is what makes the slider itself operable.
  const KEYBOARD = { enabled: true, onlyInViewport: true };

  /* ================= HERO SLIDER =================
     Swiper owns the slide mechanics (crossfade, autoplay, index tracking,
     pagination, arrows). GSAP owns everything layered on top: a different
     entrance transition per slide, the label crossfade, the slow Ken Burns
     drift on the blurred backdrop, and the mouse-move parallax
     (gsap.quickTo — built for exactly this: cheap, repeated updates on
     every mousemove). See the CSS for how the four layers divide up which
     properties each is allowed to write. */
  try { (function heroSlider(){
    if(!heroEl || typeof Swiper === 'undefined') return;
    // local files (assets/images/) — no external hotlinking, so nothing here
    // depends on network access to a third-party CDN at page-load time.
    const slidesData = [
      { img: 'assets/images/hero-1-residential.jpg', label: 'Residential Excellence' },
      { img: 'assets/images/hero-2-commercial.jpg', label: 'Commercial Landmarks' },
      { img: 'assets/images/hero-3-hospitality.jpg', label: 'Hospitality Destinations' },
      { img: 'assets/images/hero-4-waterfront.jpg', label: 'Waterfront Living' },
      { img: 'assets/images/hero-5-business.jpg', label: 'Business Districts' }
    ];
    // tasteful fallback tints so every slide is visibly distinct even if a photo
    // somehow fails to load — the crossfade is never invisible.
    const heroColors = ['#332d22', '#2c3230', '#332a28', '#2d3126', '#302a30'];

    /* ---- one distinct entrance per slide ----
       Each is a different KIND of move — zoom, horizontal pan, focus pull,
       vertical rise, pull-back — with its own duration and easing so the
       rotation never feels like one effect on repeat. They all animate the
       same property set and all land on IDENTITY, which is what lets any
       one of them interrupt any other cleanly: whatever a killed tween left
       behind is a valid starting point for the next `fromTo`.
       Every `from` is deliberately a transform of the WHOLE .hero-slide, so
       the blurred backdrop moves with the photo as one plate — no transition
       can ever tear the photo away from its own background. */
    const IDENTITY = { scale: 1, x: '0%', y: '0%', rotate: 0, filter: 'blur(0px)' };
    const TRANSITIONS = [
      { from: { scale: 1.28 },                 duration: 1.9, ease: 'power2.out' },   // 1. slow zoom in
      { from: { scale: 1.14, x: '12%' },       duration: 1.6, ease: 'power3.out' },   // 2. pan across
      { from: { scale: 1.18, filter: 'blur(26px)' }, duration: 1.5, ease: 'power2.out' }, // 3. focus pull
      { from: { scale: 1.16, y: '14%' },       duration: 1.7, ease: 'power3.out' },   // 4. rise from below
      { from: { scale: 0.88, rotate: 1.4 },    duration: 1.6, ease: 'back.out(1.1)' } // 5. pull back + settle
    ];

    const heroLayer = document.getElementById('heroLayer');
    const slidesWrap = document.getElementById('heroSlides');
    const heroContent = document.getElementById('heroContent');
    const labelEl = document.getElementById('heroLabel');
    let parallaxSetters = null; // declared up here, not beside bindParallax: playEntrance()
                                // runs the moment Swiper is constructed, before that code

    slidesData.forEach((s, i) => {
      const slide = document.createElement('div');
      slide.className = 'swiper-slide'; // Swiper owns this element's opacity/z-index
      const inner = document.createElement('div');
      inner.className = 'hero-slide'; // the per-slide entrance transition lives here
      const bgBlur = document.createElement('div');
      bgBlur.className = 'bg-blur';
      bgBlur.style.backgroundColor = heroColors[i];
      bgBlur.style.backgroundImage = `url("${s.img}")`;
      const bgZoom = document.createElement('div');
      bgZoom.className = 'bg-zoom';
      bgZoom.style.backgroundImage = `url("${s.img}")`; // local file — safe to set directly, no preload dance needed
      inner.appendChild(bgBlur);
      inner.appendChild(bgZoom);
      slide.appendChild(inner);
      slidesWrap.appendChild(slide);
    });

    /* loop:true means the element Swiper is showing may be a clone of the
       authored slide, so every per-slide effect below is looked up live off
       swiper.slides[activeIndex] rather than an array captured at build
       time — realIndex only picks WHICH transition, never which element. */
    function playEntrance(swiper){
      const slideEl = swiper.slides[swiper.activeIndex];
      if(!slideEl) return;
      const inner = slideEl.querySelector('.hero-slide');
      const blur = slideEl.querySelector('.bg-blur');
      const t = TRANSITIONS[swiper.realIndex % TRANSITIONS.length];

      if(inner && !REDUCED){
        gsap.killTweensOf(inner);
        gsap.fromTo(inner,
          Object.assign({}, IDENTITY, t.from),
          Object.assign({}, IDENTITY, { duration: t.duration, ease: t.ease, overwrite: 'auto' })
        );
      }
      if(blur && !REDUCED){
        // Slow Ken Burns on the backdrop only. GSAP tracks scale and x/y as
        // separate transform components, so this never fights the mousemove
        // quickTo below — but it must not `overwrite` it either.
        gsap.killTweensOf(blur, 'scale');
        gsap.fromTo(blur, { scale: 1.06 }, { scale: 1.2, duration: 10, ease: 'none', overwrite: false });
        bindParallax(blur);
      }
    }

    const heroSwiper = new Swiper('#heroSwiper', {
      effect: 'fade',
      fadeEffect: { crossFade: true }, // outgoing slide stays opaque underneath, so a shrinking/panning entrance never exposes bare background
      loop: true,
      speed: 1200,
      autoplay: autoplay(5500),
      keyboard: KEYBOARD,
      pagination: { el: '#heroDots', clickable: true },
      navigation: { prevEl: '#heroPrev', nextEl: '#heroNext' },
      on: {
        slideChangeTransitionStart(swiper){
          const i = swiper.realIndex;
          gsap.to(labelEl, { opacity: 0, duration: .2, onComplete(){
            labelEl.textContent = slidesData[i].label;
            gsap.to(labelEl, { opacity: 1, duration: .3 });
          }});
          playEntrance(swiper);
        }
      }
    });
    playEntrance(heroSwiper); // Swiper fires no slideChange for the slide it starts on

    // autoplay runs continuously; only pause while the hero is scrolled
    // out of view (no point animating an offscreen slide) — hovering the
    // hero to look at the mouse-parallax doesn't stop the rotation.
    if(!REDUCED){
      new IntersectionObserver((entries) => {
        entries.forEach(e => { e.isIntersecting ? heroSwiper.autoplay.start() : heroSwiper.autoplay.stop(); });
      }, { threshold: 0.15 }).observe(heroEl);
    }

    /* ---- mouse-move parallax (GSAP quickTo) ----
       Only the blurred backdrop drifts; the contained photo stays put. It
       has no slack to give — `contain` fits it flush to whichever axis is
       tighter (which axis that is depends on the viewport's aspect ratio),
       so any drift at all would clip an edge off the frame the slider
       exists to show in full. Drifting the backdrop alone reads as more
       depth, not less: a soft plate sliding behind a fixed sharp one.
       Bound to the ACTIVE slide's backdrop only — the other four are at
       opacity 0, and re-binding on each slide change keeps this correct
       across Swiper's loop clones. */
    function bindParallax(el){
      parallaxSetters = {
        x: gsap.quickTo(el, 'x', { duration: .6, ease: 'power3' }),
        y: gsap.quickTo(el, 'y', { duration: .6, ease: 'power3' })
      };
    }
    heroEl.addEventListener('mousemove', (e) => {
      if(!parallaxSetters) return;
      const rect = heroEl.getBoundingClientRect();
      const nx = (e.clientX - rect.left) / rect.width - 0.5;   // -0.5 .. 0.5
      const ny = (e.clientY - rect.top) / rect.height - 0.5;
      parallaxSetters.x(-nx * 46); parallaxSetters.y(-ny * 46);
    });
    heroEl.addEventListener('mouseleave', () => {
      if(parallaxSetters){ parallaxSetters.x(0); parallaxSetters.y(0); }
    });

    /* ---- scroll-exit parallax + scaling (ScrollTrigger, scrubbed) ----
       The photo plate sinks and grows slightly while the copy lifts and
       fades, so the hero feels like it is being left behind rather than
       simply scrolled off. `scrub: true` ties it rigidly to scroll position,
       and because ScrollTrigger is updated from Lenis' scroll event it moves
       on the smoothed position — a raw listener here would land a frame
       behind the content sliding over it, which is exactly the judder this
       effect is supposed to hide.
       The copy is faded out over the first 85% so it is fully gone slightly
       before the hero is, rather than still ghosting at the handover. */
    if(typeof ScrollTrigger !== 'undefined' && !REDUCED){
      gsap.registerPlugin(ScrollTrigger);
      const exit = gsap.timeline({
        scrollTrigger: { trigger: heroEl, start: 'top top', end: 'bottom top', scrub: true }
      });
      if(heroLayer) exit.to(heroLayer, { y: 90, scale: 1.05, ease: 'none', duration: 1 }, 0);
      if(heroContent){
        exit.to(heroContent, { y: 50, ease: 'none', duration: 1 }, 0)
            .to(heroContent, { opacity: 0, ease: 'none', duration: .85 }, 0);
      }
    }

    /* The header sits on photography inside the hero and on white below it.
       Driven from the hero's real geometry on each ScrollTrigger update
       rather than from a boundary trigger's isActive, which misreported the
       state on a page loaded at scroll 0. One rect read per scroll frame. */
    if(header && typeof ScrollTrigger !== 'undefined'){
      gsap.registerPlugin(ScrollTrigger); // the block above may have been skipped
      const syncHeader = () => header.classList.toggle('on-dark', heroEl.getBoundingClientRect().bottom > 80);
      ScrollTrigger.create({ start: 0, end: 'max', onUpdate: syncHeader, onRefresh: syncHeader });
      syncHeader();
    }
  })(); } catch(err) { console.error('[home.js] heroSlider failed:', err); }

  /* ================= FEATURED DEVELOPMENTS SLIDER =================
     Swiper (slidesPerView:'auto') owns the peek-carousel mechanics —
     touch drag, looping, autoplay, pagination — reading each slide's
     CSS width (min(58vw,680px)) itself, which is what naturally
     produces the "one full card + next card's edge peeking in" look.
     GSAP still owns the per-slide directional image entrance on top:
     cycling top/left/right/bottom per card so the rotation doesn't feel
     like one repeated effect, toggled on/off by the Simple/Animated
     control (the only remaining difference between the two modes). */
  try { (function featuredSlider(){
    const section = document.getElementById('featured');
    const track = document.getElementById('featuredTrack');
    if(!track || typeof Swiper === 'undefined') return;
    const cards = Array.from(track.children);
    const toggleWrap = document.getElementById('featuredToggle');
    let mode = 'animated';

    // ---- per-slide directional image entrance (GSAP) ----
    const DIRS = ['top', 'left', 'right', 'bottom'];
    const OFFSET = 70;
    function animateActiveImage(card, i){
      const img = card.querySelector('img');
      if(!img || typeof gsap === 'undefined') return;
      const from = {
        top:    { x: 0, y: -OFFSET },
        bottom: { x: 0, y:  OFFSET },
        left:   { x: -OFFSET, y: 0 },
        right:  { x:  OFFSET, y: 0 }
      }[DIRS[i % DIRS.length]];
      gsap.killTweensOf(img);
      img.style.transition = 'none'; // let GSAP fully own transform for the tween — the CSS
                                      // hover-zoom transition would otherwise fight it frame by frame
      gsap.fromTo(img,
        { x: from.x, y: from.y, scale: 1.16 },
        {
          x: 0, y: 0, scale: 1, duration: 1.1, ease: 'power3.out',
          onComplete(){ img.style.transition = ''; gsap.set(img, { clearProps: 'transform' }); }
        }
      );
    }

    const featuredSwiper = new Swiper('.featured-swiper', {
      slidesPerView: 'auto',
      spaceBetween: 28,
      loop: true,
      speed: 700,
      autoplay: autoplay(5000),
      keyboard: KEYBOARD,
      pagination: { el: '#featuredDots', clickable: true },
      navigation: { prevEl: '#featPrev', nextEl: '#featNext' },
      // loop:true clones slide DOM nodes for seamless wraparound, so
      // swiper.slides[activeIndex] may be a clone, not the original
      // element — realIndex is the clone-proof logical 0..N-1 index,
      // used against the ORIGINAL `cards` array captured before Swiper
      // ever touched the DOM.
      on: {
        slideChangeTransitionStart(swiper){
          if(mode !== 'animated') return;
          animateActiveImage(cards[swiper.realIndex], swiper.realIndex);
        }
      }
    });

    if(toggleWrap){
      toggleWrap.querySelectorAll('button').forEach((btn) => {
        btn.addEventListener('click', () => {
          if(btn.dataset.mode === mode) return;
          mode = btn.dataset.mode;
          toggleWrap.querySelectorAll('button').forEach((b) => {
            const on = b === btn;
            b.classList.toggle('active', on);
            b.setAttribute('aria-pressed', String(on)); // the class is the paint; this is what is announced
          });
          if(mode === 'animated'){
            animateActiveImage(cards[featuredSwiper.realIndex], featuredSwiper.realIndex); // immediate feedback that animated mode is now on
          }
        });
      });
    }

    // autoplay runs continuously; only pause while the section is scrolled out of view
    if(section && !REDUCED){
      new IntersectionObserver((entries) => {
        entries.forEach((e) => { e.isIntersecting ? featuredSwiper.autoplay.start() : featuredSwiper.autoplay.stop(); });
      }, { threshold: 0.3 }).observe(section);
    }
  })(); } catch(err) { console.error('[home.js] featuredSlider failed:', err); }

  /* ================= OUR JOURNEY: SCROLL-DRIVEN STORY CHAPTERS =================
     No buttons to click — each .journey-chapter upgrades its <img> to a
     <video> where the HTML marks it as one (data-type="video"), and an
     IntersectionObserver starts/stops playback so nothing ever plays
     off-screen.

     Everything visual is a single scrubbed ScrollTrigger timeline per
     chapter, running the full pass from 'top bottom' to 'bottom top', with
     the layers moving at different rates — that difference IS the parallax:

       .journey-media        drifts vertically, slowest, furthest "back"
       media img/video       scale, largest entering and leaving, smallest
                             at centre, so the frame settles as you arrive
                             and swells again as you go
       .journey-num          drifts fastest and brightens at centre — the
                             rate gap against the media is what sells depth
       .journey-content      rises in and keeps rising out, fading both ways

     The content is deliberately NOT .reveal-up any more. A once-only reveal
     plus a scrubbed fade would be two systems writing one opacity, and the
     scrub has to own it end to end for the exit fade to exist at all. */
  try { (function ourJourney(){
    const chapters = document.querySelectorAll('.journey-chapter');
    if(!chapters.length) return;
    const hasST = typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined';
    if(hasST) gsap.registerPlugin(ScrollTrigger);

    chapters.forEach((chapter) => {
      let video = null;
      if(chapter.dataset.type === 'video'){
        const img = chapter.querySelector('.journey-media img');
        video = document.createElement('video');
        video.src = chapter.dataset.src;
        video.poster = chapter.dataset.poster;
        video.muted = true;
        video.loop = true;
        video.playsInline = true;
        video.preload = 'none';
        img.replaceWith(video);
      }

      new IntersectionObserver((entries) => {
        entries.forEach((e) => {
          chapter.classList.toggle('in-view', e.isIntersecting);
          if(video && !REDUCED){
            if(e.isIntersecting) video.play().catch(() => {});
            else video.pause();
          }
        });
      }, { threshold: 0.5 }).observe(chapter);

      if(!hasST) return;

      const media = chapter.querySelector('.journey-media');
      const plate = chapter.querySelector('.journey-media img, .journey-media video');
      const num = chapter.querySelector('.journey-num');
      const scrim = chapter.querySelector('.journey-scrim');
      const copy = chapter.querySelectorAll('.journey-content-inner > *');

      // Normalised to duration 1 so every position below reads as a fraction
      // of the chapter's pass: 0 = entering the bottom, 1 = leaving the top.
      const tl = gsap.timeline({
        defaults: { ease: 'none' },
        scrollTrigger: { trigger: chapter, start: 'top bottom', end: 'bottom top', scrub: true }
      });

      // ±6% of the plate's own height, against the -10% bleed in the CSS.
      if(media) tl.fromTo(media, { yPercent: -6 }, { yPercent: 6, duration: 1 }, 0);

      // scale up → down → up, so the image is calmest exactly when the copy
      // is readable and most alive at both edges of the chapter.
      if(plate){
        tl.fromTo(plate, { scale: 1.16 }, { scale: 1.04, duration: .5 }, 0)
          .to(plate, { scale: 1.16, duration: .5 }, .5);
      }

      // Four times the media's drift: the near layer of the parallax.
      if(num){
        tl.fromTo(num, { yPercent: -24, opacity: .5 }, { yPercent: 0, opacity: 1, duration: .5 }, 0)
          .to(num, { yPercent: 24, opacity: .5, duration: .5 }, .5);
      }

      // Scrim lifts slightly at centre so the photograph reads at its best
      // where the chapter is fully in frame, and closes back down at the edges.
      if(scrim){
        tl.fromTo(scrim, { opacity: 1 }, { opacity: .84, duration: .5 }, 0)
          .to(scrim, { opacity: 1, duration: .5 }, .5);
      }

      /* Copy rises in and keeps travelling the same way as it leaves — never
         reversing direction, so it reads as one continuous pass behind the
         viewport rather than a bounce. Stagger is small: these are four lines
         of one thought, not four separate items.

         The positions below are not evenly spread across the chapter, and are
         not guesses — they are fitted to when this copy is actually on screen.
         Because it is anchored to the chapter's BOTTOM, it enters the viewport
         around 34% of the way through the pass and has left by ~84%, so both
         fades have to live inside that window: spread evenly across 0..1 the
         entrance plays out below the fold and the exit fade finishes above it,
         and the text appears to pop in and then cut off. Chapter height is
         92vh, so the window is a fixed fraction of the pass at any size. */
      if(copy.length){
        tl.fromTo(copy,
          { y: 90, opacity: 0 },
          { y: 0, opacity: 1, duration: .15, stagger: .02 }, .34)
          .to(copy,
            { y: -90, opacity: 0, duration: .15, stagger: .02 }, .64);
      }
    });
  })(); } catch(err) { console.error('[home.js] ourJourney failed:', err); }

  /* ================= EXPLORE: FULL-WIDTH IMAGE + VIDEO SLIDER =================
     Swiper (effect:'fade') owns the crossfade/autoplay/loop/pagination
     mechanics; each slide can be an image OR a muted video. Only the
     active slide's video plays — every other one is explicitly paused
     via Swiper's transition events, so five media items never fight for
     bandwidth/decode time at once. GSAP updates the cat/title/loc text. */
  try { (function exploreSlider(){
    const section = document.getElementById('explore');
    if(!section || typeof Swiper === 'undefined') return;
    const slidesData = [
      { type: 'image', src: 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=1800&q=80', cat: 'Residential', title: 'The RHL Residences', loc: 'Gulshan' },
      { type: 'video', src: 'assets/videos/skyline-commerce-tower.mp4', poster: 'https://images.unsplash.com/photo-1470723710355-95304d8aece4?auto=format&fit=crop&w=1800&q=80', cat: 'Commercial', title: 'Skyline Commerce Tower', loc: 'Tejgaon' },
      { type: 'image', src: 'https://images.unsplash.com/photo-1502005229762-cf1b2da7c5d6?auto=format&fit=crop&w=1800&q=80', cat: 'Residential', title: 'Aurora Waterfront Villas', loc: 'Banani' },
      { type: 'video', src: 'assets/videos/grand-exchange.mp4', poster: 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1800&q=80', cat: 'Mixed-Use', title: 'The Grand Exchange', loc: 'Dhanmondi' },
      { type: 'image', src: 'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?auto=format&fit=crop&w=1800&q=80', cat: 'Commercial', title: 'Horizon Business Park', loc: 'Tejgaon' }
    ];

    const slidesWrap = document.getElementById('exploreSlides');
    const catEl = document.getElementById('exploreCat');
    const titleEl = document.getElementById('exploreTitle');
    const locEl = document.getElementById('exploreLoc');
    const slideEls = [];

    slidesData.forEach((s) => {
      const slide = document.createElement('div');
      slide.className = 'swiper-slide'; // Swiper owns this element's transform/opacity (fade effect)
      const inner = document.createElement('div');
      inner.className = 'explore-slide'; // our pop-in scale/rise lives here instead — see CSS note

      if(s.type === 'video'){
        const video = document.createElement('video');
        video.src = s.src;
        video.poster = s.poster;
        video.muted = true;
        video.loop = true;
        video.playsInline = true;
        /* 'auto' pulled both clips down in full at page load, for a section
           that is four screens below the fold and whose videos do not play
           until their slide is active. 'metadata' costs a few KB and still
           gives play() a head start. */
        video.preload = 'metadata';
        inner.appendChild(video);
        const badge = document.createElement('span');
        badge.className = 'explore-media-badge';
        badge.textContent = '▶';
        inner.appendChild(badge);
      } else {
        const img = document.createElement('img');
        img.src = s.src;
        img.alt = s.title;
        img.loading = 'lazy';
        inner.appendChild(img);
      }

      slide.appendChild(inner);
      slidesWrap.appendChild(slide);
      slideEls.push(inner);
    });

    function setSlideMedia(i, playing){
      const s = slidesData[i];
      if(s.type !== 'video') return;
      const video = slideEls[i].querySelector('video');
      if(playing && !REDUCED){ video.currentTime = 0; video.play().catch(() => {}); }
      else video.pause();
    }

    function updateText(i){
      const s = slidesData[i];
      if(typeof gsap !== 'undefined'){
        gsap.to([catEl, titleEl, locEl], { opacity: 0, duration: .2, onComplete(){
          catEl.textContent = s.cat; titleEl.textContent = s.title; locEl.textContent = s.loc;
          gsap.to([catEl, titleEl, locEl], { opacity: 1, duration: .3 });
        }});
      } else {
        catEl.textContent = s.cat; titleEl.textContent = s.title; locEl.textContent = s.loc;
      }
    }

    // realIndex is clone-proof (loop:true duplicates slide DOM nodes for
    // seamless wraparound); slideEls/slidesData stay indexed 0..N-1 as
    // originally authored, so always look media up by realIndex.
    let prevRealIndex = 0;
    const exploreSwiper = new Swiper('.explore-swiper', {
      effect: 'fade',
      fadeEffect: { crossFade: true },
      loop: true,
      speed: 1300,
      autoplay: autoplay(6000),
      keyboard: KEYBOARD,
      pagination: { el: '#exploreDots', clickable: true },
      navigation: { prevEl: '#explorePrev', nextEl: '#exploreNext' },
      on: {
        slideChangeTransitionStart(swiper){
          setSlideMedia(prevRealIndex, false);
          setSlideMedia(swiper.realIndex, true);
          prevRealIndex = swiper.realIndex;
          updateText(swiper.realIndex);
        }
      }
    });

    setSlideMedia(0, true);

    if(!REDUCED){
      new IntersectionObserver((entries) => {
        entries.forEach((e) => { e.isIntersecting ? exploreSwiper.autoplay.start() : exploreSwiper.autoplay.stop(); });
      }, { threshold: 0.3 }).observe(section);
    }
  })(); } catch(err) { console.error('[home.js] exploreSlider failed:', err); }

  /* ================= MARQUEE ================= */
  const items = ["Award-Winning Design","Prime Locations","Sustainable Architecture","Trusted Partnerships","On-Time Delivery","Enduring Value"];
  const track = document.getElementById('marqueeTrack');
  if(track){
    let html = '';
    for(let r=0;r<2;r++){ items.forEach(t => { html += `<span>${t}</span><span class="dot">&#10022;</span>`; }); }
    track.innerHTML = html;
  }

})();
