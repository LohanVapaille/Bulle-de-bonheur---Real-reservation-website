<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulle de bonheur</title>
     <?php include 'always/head.html'; ?>
    <link rel="stylesheet" href="styles/style-index.css">
   
</head>
<body>
<?php include 'always/header.html'; ?>


    <div class='first h-screen flex flex-col justify-center items-center text-center text-white'>
        <h1 class="title font-bold mb-8">Envie de vous détendre à la campagne ?</h1>
        <div class='calltoaction'>
            <a href="tarifs.php" class="fadeInUp call bg-sky-600 hover:bg-sky-700 text-white font-bold py-3 px-8 rounded-full transition duration-300 transform hover:scale-105">
            Voir les préstations
        </a>
        <a href="creneaux.php" class="fadeInUp call bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-full transition duration-300 transform hover:scale-105">
            Voir les disponibilités
        </a></div>
    </div>
    

    <div class="carousel relative my-16">
        <button id="prev" class="btn flex items-center justify-center">‹</button>
        <img id="carousel-image" src="img/vuensemble.JPG" alt="Image 1" />
        <button id="next" class="btn flex items-center justify-center">›</button>
    </div>

    <div class='call calltoreserve bg-green-600 text-white py-16 px-8 text-center'>
        <div class="fade-in-up-on-scroll max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold mb-6">Comment ça marche ?</h2>
            <p class="text-xl mb-4">Demander un créneau en quelques clics !</p>
            <p class="text-lg mb-8">Choisissez la date et l'heure qui vous conviennent <strong>en fonction des plages horaires disponibles</strong>, et laissez-nous nous occuper du reste.</p>
            <div class='call calltoaction'><a href="creneaux.php" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-full transition duration-300 transform hover:scale-105">
            Voir les plages horaires disponibles
        </a>
           <a href="tarifs.php#tarifs" class="bg-sky-600 hover:bg-sky-700 text-white font-bold py-3 px-8 rounded-full transition duration-300 transform hover:scale-105">
            Voir les tarifs
        </a>
        </div>
        </div>
    </div>

    <div class='comment_container max-w-7xl mx-auto py-16 px-8'>
        <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Quelques avis sur notre cocon</h2>
        <div class="fade-in-up-on-scroll grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class='comment bg-white p-8 rounded-lg shadow-md'>
                <div class='info flex justify-between items-center mb-4'>
                    <p class="font-bold text-green-600">"Linda B"</p>
                    <p class="text-yellow-400">5/5</p>
                    <p class="text-gray-500 text-sm">août 2021</p>
                </div>
                <p class='text_comment text-gray-700'>
                    Nous avons passé une superbe après-midi chez Sandrine V, le balneo était parfait ainsi que la température. Le jardin est spacieux encore mieux que sur les photo. Merci encore pour votre accueil😊.
                </p>
            </div>

            <div class='comment bg-white p-8 rounded-lg shadow-md'>
                <div class='info flex justify-between items-center mb-4'>
                    <p class="font-bold text-green-600">"Ferdaousse E"</p>
                    <p class="text-yellow-400">5/5</p>
                    <p class="text-gray-500 text-sm">juin 2021</p>
                </div>
                <p class='text_comment text-gray-700'>
                    Endroit magnifique et hôte très sympathique et accueillant. Qui plus est, aux petits soins pour les locataires. Si vous souhaitez passer un moment calme et apaisant, foncez !
                </p>
            </div>

            <div class='comment bg-white p-8 rounded-lg shadow-md'>
                <div class='info flex justify-between items-center mb-4'>
                    <p class="font-bold text-green-600">"Ines C"</p>
                    <p class="text-yellow-400">5/5</p>
                    <p class="text-gray-500 text-sm">août 2024</p>
                </div>
                <p class='text_comment text-gray-700'>
                    Accueil très chaleureuse, Un endroit agréable. Nous avons passé un très bon moment de détente. Service au top
                </p>
            </div>
        </div>
    </div>

    <?php include 'always/footer.html'; ?>

    <script>
        const images = [
            "img/jardin.JPG",
            "img/terrasejacuzzi.JPG",
            "img/kioske.jpg",
            "img/kioske2.JPG",
            "img/traction.jpg",
            "img/pingpong.JPG",
            "img/jaccuzzihaut.JPG",
            "img/petanque.JPG"

            
        ];

        let currentIndex = 0;
        const imgElement = document.getElementById("carousel-image");
        const prevBtn = document.getElementById("prev");
        const nextBtn = document.getElementById("next");

        function showImage(index) {
            imgElement.src = images[index];
            imgElement.alt = `Image ${index + 1}`;
        }

        prevBtn.addEventListener("click", () => {
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            showImage(currentIndex);
        });

        nextBtn.addEventListener("click", () => {
            currentIndex = (currentIndex + 1) % images.length;
            showImage(currentIndex);
        });

    </script>
</body>
</html>