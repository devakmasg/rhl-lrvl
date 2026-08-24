/* ================= NEWS DATA + NEWS-DETAIL RENDER =================
   Shared by news.html (static grid, browsable with no JS) and
   news-detail.html (one page, selected by ?n=<slug>, same pattern as
   project.html/project-detail.js). The three articles surfaced on the
   homepage are authored here first — see index.html's "Latest Updates"
   section — so if these ever disagree, this file is the source of truth
   and the homepage teaser is the one to fix. */
(function(){
  const G = 'https://images.unsplash.com/';
  const q = '?auto=format&fit=crop&w=1600&q=80';

  const ARTICLES = [
    {
      slug: 'rhl-trade-centre-tops-out',
      title: 'RHL Trade Centre tops out on schedule in Gulshan',
      date: '2026-08-12', dateLabel: '12 August 2026',
      category: 'Construction Update',
      image: 'https://images.unsplash.com/photo-1470723710355-95304d8aece4' + q,
      excerpt: 'Structural work on our eighteen-floor Gulshan Avenue office address is complete, with facade installation and interior fit-out now underway.',
      body: [
        'Structural work on RHL Trade Centre topped out this month, right on the schedule set at groundbreaking. The eighteen-floor Grade-A office development on Gulshan Avenue now moves into its facade and fit-out phase, with the first anchor tenant expected to begin interior works before the end of the quarter.',
        'The column-free 11,000 sq ft floorplates were the defining design decision for this tower — pushing the structural core to the northern edge kept every floor\'s avenue frontage entirely glazed, a detail that mattered more to prospective tenants than almost anything else in early leasing conversations.',
        'RHL Trade Centre is targeting LEED Gold certification and remains on track for a Q4 2027 handover.'
      ],
      related: ['gulshan-heights-handover', 'banani-lake-40-percent']
    },
    {
      slug: 'gulshan-heights-handover',
      title: 'Gulshan Heights: all 42 units handed over ahead of date',
      date: '2026-07-28', dateLabel: '28 July 2026',
      category: 'Handover',
      image: 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00' + q,
      excerpt: 'Every apartment at our flagship lakeside development changed hands three months ahead of its contracted completion date.',
      body: [
        'Gulshan Heights, RHL Properties\' forty-two-unit lakeside development, has now been fully handed over — three months ahead of the date written into buyers\' agreements at booking. All forty-two apartments had already been sold before construction finished.',
        'The building\'s double-height terraces, cut into a setback above the sixth floor rather than cantilevered from it, were the project\'s signature design feature and one of the most-cited reasons buyers gave for choosing the development over comparable listings in the area.',
        'This is the fourth RHL Properties residential handover in a row delivered on or ahead of its contracted date — a record the company considers as important as any design award.'
      ],
      related: ['rhl-trade-centre-tops-out', 'dhanmondi-garden-villas-sold-out']
    },
    {
      slug: 'banani-lake-40-percent',
      title: 'Banani Lake Residences crosses 40% construction milestone',
      date: '2026-07-05', dateLabel: '05 July 2026',
      category: 'Construction Update',
      image: 'https://images.unsplash.com/photo-1502005229762-cf1b2da7c5d6' + q,
      excerpt: 'Superstructure is complete to the fourth floor across all three terraced blocks at our Banani Lake development.',
      body: [
        'Banani Lake Residences has passed 40% construction progress, with superstructure complete to the fourth floor across all three terraced blocks. The development\'s defining move — stepping three blocks down the natural four-metre fall of the site rather than levelling it — is now visible on site for the first time.',
        'Landscaping of the shared mid-level courtyard, which every duplex in the development looks onto or passes through, begins once the final block tops out later this year.',
        'The twenty-six-duplex development remains on track for a Q2 2027 handover.'
      ],
      related: ['rhl-trade-centre-tops-out', 'rehab-award-2026']
    },
    {
      slug: 'dhanmondi-garden-villas-sold-out',
      title: 'Dhanmondi Garden Villas sells out within twelve months of launch',
      date: '2026-05-14', dateLabel: '14 May 2026',
      category: 'Sales',
      image: 'https://images.unsplash.com/photo-1449824913935-59a10b8d2000' + q,
      excerpt: 'All twelve private villas on our gated Dhanmondi plot found buyers within a year of first going to market.',
      body: [
        'Dhanmondi Garden Villas, our twelve-villa gated development, has sold its final unit — just under twelve months after the first villa went to market. Each villa carries its own walled garden and shares a central green that all twelve front onto, a layout that consistently came up as buyers\' primary reason for choosing the development.',
        'Handover for the final three villas is scheduled for later this quarter, alongside the shared landscaping and gatehouse works.'
      ],
      related: ['gulshan-heights-handover', 'rehab-award-2026']
    },
    {
      slug: 'rehab-award-2026',
      title: 'RHL Properties named Best Residential Developer at the 2026 REHAB Awards',
      date: '2026-03-02', dateLabel: '02 March 2026',
      category: 'Awards',
      image: 'https://images.unsplash.com/photo-1497366754035-f200968a6e72' + q,
      excerpt: 'Gulshan Heights\' design and on-time handover record earned RHL Properties its fourth industry award since 2019.',
      body: [
        'RHL Properties has been named Best Residential Developer at the 2026 Bangladesh Real Estate & Housing Awards, with the judging panel specifically citing Gulshan Heights\' design and its handover — delivered three months ahead of schedule — as the deciding factor.',
        'This is the company\'s fourth industry recognition since 2019, following awards for RHL Logistics Hub\'s commercial design and two prior REHAB Bangladesh customer-trust citations.',
        'Managing Director Md. Rezaul Haque accepted the award on behalf of the construction and design teams, noting that the recognition "belongs to everyone who kept a handover date rather than just announcing one."'
      ],
      related: ['gulshan-heights-handover', 'tejgaon-industrial-phase-1']
    },
    {
      slug: 'tejgaon-industrial-phase-1',
      title: 'Tejgaon Industrial Park: first four blocks structurally complete',
      date: '2026-02-18', dateLabel: '18 February 2026',
      category: 'Construction Update',
      image: 'https://images.unsplash.com/photo-1477959858617-67f85cf4f1df' + q,
      excerpt: 'Blocks one through four of our six-block light-industrial development are now structurally complete.',
      body: [
        'Construction has reached a key milestone at Tejgaon Industrial Park, with blocks one through four now structurally complete. The existing warehouse slab on site was retained and re-used as the shared yard surface, a decision that cut both cost and construction time compared to demolishing and repouring.',
        'Yard surfacing and the shared electrical substation follow once the remaining two blocks top out later this year. Enquiries for the first four blocks are already open.'
      ],
      related: ['banani-lake-40-percent', 'rhl-trade-centre-tops-out']
    },
    {
      slug: 'csr-scholarship-2026',
      title: 'RHL Properties launches a scholarship fund for site-worker families',
      date: '2026-01-20', dateLabel: '20 January 2026',
      category: 'Community',
      image: 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688' + q,
      excerpt: 'A new annual fund will cover school fees for the children of construction workers on active RHL Properties sites.',
      body: [
        'RHL Properties has launched an annual scholarship fund covering school fees and supplies for the children of construction workers currently employed on our active sites. The programme starts with twenty-five recipients across our Gulshan, Banani and Tejgaon developments.',
        'The fund is administered independently of site management to keep eligibility criteria — length of service and need — separate from any single project\'s staffing decisions.'
      ],
      related: ['rehab-award-2026', 'gulshan-heights-handover']
    },
    {
      slug: 'gulshan-park-avenue-launch',
      title: 'Gulshan Park Avenue opens for registration of interest',
      date: '2025-11-09', dateLabel: '09 November 2025',
      category: 'New Launch',
      image: 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750' + q,
      excerpt: 'Eighteen large-format residences overlooking the park are now open for early registration ahead of formal booking.',
      body: [
        'Gulshan Park Avenue, our eighteen-unit large-format residential development, is now open for registration of interest ahead of formal booking. Design is complete and land is fully assembled, with RAJUK approval expected within the year.',
        'Each of the eighteen residences averages 4,200 sq ft with a private lift lobby, and the podium level carries a residents\' club facing the park rather than the street.'
      ],
      related: ['dhanmondi-garden-villas-sold-out', 'banani-lake-40-percent']
    }
  ];

  const bySlug = new Map(ARTICLES.map((a) => [a.slug, a]));
  window.RHL_NEWS = { ARTICLES, bySlug };

  /* ---------------- news-detail.html render ---------------- */
  const root = document.getElementById('newsDetail');
  if(!root) return;

  const params = new URLSearchParams(location.search);
  const slug = params.get('n');
  const article = bySlug.get(slug);

  function el(id){ return document.getElementById(id); }
  function setText(id, value){ const n = el(id); if(n) n.textContent = value; }

  if(!article){
    root.hidden = true;
    const missing = el('newsMissing');
    if(missing) missing.hidden = false;
    document.title = 'Article not found | RHL Properties Ltd';
    return;
  }

  document.title = article.title + ' | RHL Properties Ltd';
  const meta = document.querySelector('meta[name="description"]');
  if(meta) meta.setAttribute('content', article.excerpt);

  setText('ndCategory', article.category);
  setText('ndTitle', article.title);
  setText('ndDate', article.dateLabel);
  const heroMedia = el('ndHeroMedia');
  if(heroMedia) heroMedia.style.backgroundImage = `url("${article.image}")`;

  const bodyEl = el('ndBody');
  if(bodyEl){
    bodyEl.innerHTML = '';
    article.body.forEach((para) => {
      const p = document.createElement('p');
      p.textContent = para;
      bodyEl.appendChild(p);
    });
  }

  // Share links — mailto and social share intents need no backend and work
  // offline in a static build; every href is built from the live page URL.
  const shareUrl = encodeURIComponent(location.href);
  const shareTitle = encodeURIComponent(article.title);
  const shareLinks = {
    ndShareFacebook: `https://www.facebook.com/sharer/sharer.php?u=${shareUrl}`,
    ndShareLinkedin: `https://www.linkedin.com/sharing/share-offsite/?url=${shareUrl}`,
    ndShareWhatsapp: `https://wa.me/?text=${shareTitle}%20${shareUrl}`,
    ndShareEmail: `mailto:?subject=${shareTitle}&body=${shareUrl}`
  };
  Object.keys(shareLinks).forEach((id) => { const a = el(id); if(a) a.href = shareLinks[id]; });

  // Related articles
  const relatedEl = el('ndRelated');
  if(relatedEl){
    relatedEl.innerHTML = '';
    (article.related || []).map((s) => bySlug.get(s)).filter(Boolean).forEach((a) => {
      const card = document.createElement('a');
      card.className = 'news-card';
      card.href = 'news-detail.html?n=' + a.slug;
      card.innerHTML =
        `<div class="news-media"><img src="${a.image}" alt="${a.title}" loading="lazy"></div>` +
        `<span class="news-date">${a.dateLabel}</span><h3>${a.title}</h3>`;
      relatedEl.appendChild(card);
    });
  }

  if(typeof ScrollTrigger !== 'undefined') ScrollTrigger.refresh();
})();
