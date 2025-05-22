
document.addEventListener('DOMContentLoaded', () => {
  const burger = document.getElementById('burger');
  const menubg = document.querySelector('.menu-bg');
  const back = document.querySelector('.menu-bg .back');

  back.addEventListener('click', () => {
    menubg.style.display = 'none';
    burger.style.display = 'flex';
    console.log('zaza')
  });

  burger.addEventListener('click', () => {
    menubg.style.display = 'block';
    burger.style.display = 'none';
  });

  

  
});
