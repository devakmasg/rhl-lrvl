/* ================= STATS BANNER (shared: home + about) =================
   Animated stat counters and the stats-banner parallax background.
   Any page with a #statsBg / .stat .num block loads this after main.js. */
(function(){
  function safeBg(el, url){
    const img = new Image();
    img.onload = () => { el.style.backgroundImage = `url("${url}")`; };
    img.onerror = () => { /* keep existing gradient fallback already in CSS/inline style */ };
    img.src = url;
  }

  /* ================= ANIMATED COUNTERS ================= */
  const counters = document.querySelectorAll('.stat .num');
  function animateCounter(el){
    const target = parseFloat(el.dataset.target);
    const decimals = parseInt(el.dataset.decimals || '0', 10);
    const suffix = el.dataset.suffix || '';
    const duration = 1700, start = performance.now();
    function step(now){
      const p = Math.min((now - start) / duration, 1);
      const eased = 1 - Math.pow(1 - p, 3);
      const val = target * eased;
      el.textContent = (decimals ? val.toFixed(decimals) : Math.round(val).toLocaleString()) + suffix;
      if(p < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
  }
  if(counters.length){
    const counterObserver = new IntersectionObserver((entries) => {
      entries.forEach(e => { if(e.isIntersecting){ animateCounter(e.target); counterObserver.unobserve(e.target); } });
    }, { threshold: .6 });
    counters.forEach(c => counterObserver.observe(c));
  }

  /* ================= STATS PARALLAX BG ================= */
  const statsBg = document.getElementById('statsBg');
  if(statsBg){
    // data-bg is set from the CMS where the page offers it; the URL below is
    // the fallback for pages that don't, and for a fresh install.
    const bg = statsBg.dataset.bg || 'https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?auto=format&fit=crop&w=1800&q=80';
    safeBg(statsBg, bg);
    statsBg.style.background = statsBg.style.background || 'linear-gradient(135deg,#2a2822,#8a7143)';
    statsBg.style.backgroundSize = 'cover';
    statsBg.style.backgroundPosition = 'center';
  }

})();
