(() => {
  const form = document.querySelector('#vehicle-search form');
  if (!form || !window.fetch || !window.DOMParser) return;
  const status = document.querySelector('#search-status');
  let pending;
  let timer;
  let layout = 'grid';
  function applyLayout() {
    const toolbar = document.querySelector('.view-toggle');
    if (toolbar) toolbar.hidden = false;
    document.querySelector('.report-grid')?.classList.toggle('list-view', layout === 'list');
    document.querySelectorAll('[data-report-layout]').forEach(button => {
      button.setAttribute('aria-pressed', String(button.dataset.reportLayout === layout));
    });
  }
  document.addEventListener('click', event => {
    const button = event.target.closest('[data-report-layout]');
    if (!button) return;
    layout = button.dataset.reportLayout;
    applyLayout();
  });
  async function search() {
    clearTimeout(timer);
    pending?.abort();
    const request = new AbortController();
    pending = request;
    const query = new URLSearchParams(new FormData(form));
    for (const [key, value] of [...query]) if (!value) query.delete(key);
    const url = new URL('/', window.location.origin);
    url.search = query.toString();
    status.textContent = 'Searching reports…';
    document.querySelector('.report-dashboard')?.setAttribute('aria-busy', 'true');
    try {
      const response = await fetch(url, {signal: request.signal, headers: {'X-Requested-With':'XMLHttpRequest'}});
      if (!response.ok || new URL(response.url).pathname !== '/') throw new Error('Search unavailable');
      const documentResult = new DOMParser().parseFromString(await response.text(), 'text/html');
      const results = documentResult.querySelector('.report-dashboard');
      if (!results || documentResult.querySelector('.errors')) throw new Error('Check the filter dates and try again.');
      if (pending !== request) return;
      document.querySelector('.report-dashboard').replaceWith(document.importNode(results, true));
      history.replaceState(null, '', url);
      applyLayout();
      status.textContent = document.querySelector('.result-count').textContent.trim() + ' found. Results updated.';
    } catch (error) {
      if (error.name !== 'AbortError') status.textContent = 'Search could not update. Check your filters or connection, then try again.';
    } finally {
      if (pending === request) document.querySelector('.report-dashboard')?.removeAttribute('aria-busy');
    }
  }
  form.addEventListener('submit', event => { event.preventDefault(); search(); });
  form.querySelectorAll('select').forEach(select => select.addEventListener('change', search));
  form.querySelectorAll('input').forEach(input => input.addEventListener('input', () => {
    clearTimeout(timer);
    timer = setTimeout(search, 600);
  }));
  const toolbar = document.querySelector('.view-toggle');
  if (toolbar) toolbar.hidden = false;
})();
