(function () {
  const titles = Array.from(document.querySelectorAll('h2, h3, h4'))
    .filter(h => /(Starter|Professional|Premium)/i.test(h.textContent || ''));

  if (!titles.length) return;

  function findCard(el) {
    let node = el;
    for (let i = 0; i < 8 && node; i++) {
      const style = window.getComputedStyle(node);
      const hasRounded = (parseFloat(style.borderTopLeftRadius) || 0) > 0;
      const hasShadow = style.boxShadow && style.boxShadow !== 'none';
      const hasBorder = style.borderStyle && style.borderStyle !== 'none';
      if ((hasRounded || hasShadow || hasBorder) && node.tagName === 'DIV') return node;
      node = node.parentElement;
    }
    return el.closest('div') || el.parentElement;
  }

  const cards = titles.map(findCard).filter(Boolean);
  cards.forEach(card => card.classList.add('pricing-card--narrow'));

  // Trova il contenitore comune per applicare la griglia compatta
  function commonAncestor(nodes) {
    if (!nodes.length) return null;
    const paths = nodes.map(n => {
      const arr = []; let cur = n;
      while (cur) { arr.unshift(cur); cur = cur.parentElement; }
      return arr;
    });
    let ancestor = null;
    for (let i = 0; i < paths[0].length; i++) {
      const candidate = paths[0][i];
      if (paths.every(p => p[i] === candidate)) ancestor = candidate; else break;
    }
    return ancestor;
  }

  const container = commonAncestor(cards);
  if (container) container.classList.add('pricing-grid--narrow');
})();
