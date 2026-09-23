window.addEventListener('load', () => {
  const out = [];
  let el = document.getElementById('dbg-sidebar');
  let i = 0;
  while (el && i < 8) {
    const r = el.getBoundingClientRect();
    const cs = getComputedStyle(el);
    out.push(`${i} <${el.tagName.toLowerCase()}> x=${r.x.toFixed(0)} w=${r.width.toFixed(0)} cssW=${cs.width} minW=${cs.minWidth} flexDir=${cs.flexDirection} flexBasis=${cs.flexBasis} grow=${cs.flexGrow} shrink=${cs.flexShrink} jc=${cs.justifyContent} cls="${(el.getAttribute('class')||'').slice(0,70)}"`);
    el = el.parentElement; i++;
  }
  const dbg = document.createElement('pre');
  dbg.id = 'dbg-out';
  dbg.textContent = out.join('\n');
  document.body.appendChild(dbg);
});
