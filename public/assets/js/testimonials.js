/* ================= TESTIMONIALS (shared: home + testimonials page) =================
   Swiper handles the carousel mechanics — touch drag, autoplay, index
   tracking, pagination. Autoplay pauses only once scrolled out of view
   (not on hover, which used to make it look "stuck" the moment you
   read it) via the same IntersectionObserver pattern used site-wide. */
(function(){
  const track = document.getElementById('testiTrack');
  if(!track || typeof Swiper === 'undefined') return;
  const section = track.closest('.testimonials');
  const swiperEl = track.closest('.swiper');

  // A quote block that advances on a timer is the clearest case there is for
  // honouring the preference: it can move on mid-sentence while being read.
  const reduced = matchMedia('(prefers-reduced-motion: reduce)').matches;

  const testiSwiper = new Swiper(swiperEl, {
    loop: true,
    speed: 700,
    autoplay: reduced ? false : { delay: 6000, disableOnInteraction: false },
    keyboard: { enabled: true, onlyInViewport: true },
    pagination: { el: '#testiDots', clickable: true },
    navigation: { prevEl: '#testiPrev', nextEl: '#testiNext' }
  });

  if(!reduced){
    new IntersectionObserver((entries) => {
      entries.forEach((e) => { e.isIntersecting ? testiSwiper.autoplay.start() : testiSwiper.autoplay.stop(); });
    }, { threshold: 0.3 }).observe(section);
  }
})();
