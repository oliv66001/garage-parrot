let card = document.querySelector('.card');

window.addEventListener('mousemove', e => {
  document.querySelectorAll('.card').forEach(card => {  
      const x = e.pageX - card.offsetLeft;
      const y = e.pageY - card.offsetTop;
      card.style.setProperty('--x', `${ x }px`);
      card.style.setProperty('--y', `${ y }px`);
  });
});


window.addEventListener('mousemove', e => {
  document.querySelectorAll('.card').forEach(card => {
      const x = e.pageX - card.offsetLeft;
      const y = e.pageY - card.offsetTop;
      card.style.setProperty('--x', `${ x }px`);
      card.style.setProperty('--y', `${ y }px`);
      card.style.setProperty('--w', `${ card.offsetWidth }px`);
      card.style.setProperty('--h', `${ card.offsetHeight }px`);
  });
});

