document.addEventListener('DOMContentLoaded', () => {
  const burger = document.querySelector('.btn-bg');
  const menubg = document.querySelector('.menu-bg');
  const back = document.querySelector('.menu-bg .back');
  const menuItems = document.querySelectorAll('.menu-bg ul li');

  back.addEventListener('click', () => {
    // Enlève les fadeIn pour éviter les conflits
    menuItems.forEach((item, index) => {
      item.classList.remove('fadeInUp-invers');
      item.style.animationDelay = `${index * 0.1}s`;
      item.classList.add('fadeOutUp');
    });

    // Attends la fin des animations avant de cacher
    setTimeout(() => {
      menubg.style.display = 'none';
      burger.style.display = 'flex';

      // Réinitialise les classes
      menuItems.forEach((item, index) => {
        item.classList.remove('fadeOutUp');
        item.classList.add('fadeInUp-invers');
        item.style.animationDelay = `${index * 0.1}s`;
      });
    }, 400 + menuItems.length * 100); // temps = anim + dernier delay
  });

  burger.addEventListener('click', () => {
    menubg.style.display = 'block';
    burger.style.display = 'none';
  });
});
