/* ================= ADMIN — News create/edit form (A4.9) ================= */
(function(){
  const form = document.getElementById('newsForm');
  if(!form) return;
  const DATA = window.RHL_NEWS;

  const id = Number(new URLSearchParams(location.search).get('id'));
  const article = id ? DATA.find((a) => a.id === id) : null;

  if(article){
    document.getElementById('formTitle').textContent = 'Edit Article';
    document.getElementById('breadcrumbCurrent').textContent = 'Edit Article';
    document.getElementById('saveBtn').textContent = 'Save Changes';
    document.title = 'Edit Article | RHL Admin';
    form.querySelector('#artTitle').value = article.title;
    form.querySelector('#artCategory').value = article.category;
    form.querySelector('#artDate').value = article.date;
    form.querySelector('#artExcerpt').value = article.excerpt;
    form.querySelector('#artBody').value = article.body || '';
    document.getElementById('artPublished').checked = article.published;
  } else {
    form.querySelector('#artDate').value = new Date().toISOString().slice(0, 10);
  }

  const zone = document.getElementById('coverZone');
  const input = document.getElementById('coverInput');
  const chip = document.getElementById('coverChip');
  zone.addEventListener('click', () => input.click());
  input.addEventListener('change', () => { if(input.files[0]) chip.textContent = input.files[0].name; });

  form.setAttribute('novalidate', '');
  form.addEventListener('submit', (e) => {
    e.preventDefault();
    const bad = Array.from(form.elements).filter((el) => el.willValidate && !el.checkValidity());
    if(bad.length){ bad[0].focus(); AdminToast('Please fill in the required fields.', 'error'); return; }

    const values = {
      title: form.querySelector('#artTitle').value.trim(),
      category: form.querySelector('#artCategory').value,
      date: form.querySelector('#artDate').value,
      excerpt: form.querySelector('#artExcerpt').value.trim(),
      body: form.querySelector('#artBody').value.trim(),
      published: document.getElementById('artPublished').checked
    };

    if(article){
      Object.assign(article, values);
      AdminToast('Article updated.', 'success');
    } else {
      values.id = DATA.length ? Math.max(...DATA.map((a) => a.id)) + 1 : 1;
      DATA.push(values);
      AdminToast('Article published.', 'success');
    }
    setTimeout(() => { location.href = 'news.html'; }, 700);
  });
})();
