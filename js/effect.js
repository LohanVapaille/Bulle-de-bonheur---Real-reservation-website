
document.addEventListener('DOMContentLoaded', () => {  
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
      }
    });
  });

  document.querySelectorAll('.fade-in-up-on-scroll').forEach(elem => {
    observer.observe(elem);
  });
});
