<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prestation & Tarifs | Bulle de bonheur</title>
    <meta name="description" content="Ce petit coin de bonheur se situe à Chauffry, à 10 minutes de Coulommiers en Seine & Marne (77), avec jacuzzi, jardin, terrain de pétanque, badminton et tennis de table à Chauffry.">

    <?php include 'always/head.html'; ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="styles/tarifs.css">
    
    <style>

        body{
            background-image: url('img/fondpoint.png');
            background-attachment:fixed;
        }
        

    </style>
</head>
<body>
<?php include 'always/header.php'; ?>


    <section id='adresse'class=" adresse blog-section">
    <div class="blog-img fade-in-up-on-scroll">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d20193.82353783792!2d3.1616325425694716!3d48.82313641492287!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e8b7dfb6504985%3A0x76ea395a835c978c!2s77169%20Chauffry!5e1!3m2!1sfr!2sfr!4v1749302037725!5m2!1sfr!2sfr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
    <div class="blog-text fade-in-up-on-scroll">
      <h2>Adresse et contact</h2>
      <p> Ce petit coin de bonheur <strong>se situe à Chauffry</strong>, à 10 minutes de <strong>Coulommiers</strong> en <strong>Seine & Marne</strong> (77) <br><br>
    Si vous souhaitez plus de renseignements, n'hésitez pas à nous contacter : <br><br><ul>
      <li>Email : <a href="mailto:bulledebonheurchauffry@gmail.com" class="">bulledebonheurchauffry@gmail.com</a></li>
      <li>Tél : <a href="tel:33688168417" class="">+33 6 88 16 84 17</a></li>
    </ul>  </p>


      
    </div>
  </section>



    <?php include 'always/footer.html'; ?>

<script>

  window.addEventListener('load', () => {
  if (window.location.hash) {
    const el = document.querySelector(window.location.hash);
    if (el) {
      // Calcul pour centrer l'élément
      const rect = el.getBoundingClientRect();
      const absoluteElementTop = rect.top + window.pageYOffset;
      const middle = absoluteElementTop - (window.innerHeight / 2) + (rect.height / 2);

      window.scrollTo({
        top: middle,
        behavior: 'smooth' // ou 'auto' si tu veux pas d'animation
      });
    }
  }
});

</script>
</body>
</html>