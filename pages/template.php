<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Affichage du texte et images</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../style.css">
    </head>
    <body>
        <main>
        <?php 
        // Charger et décoder le fichier JSON
        $data = file_get_contents("../data/introduction.json");
        $sections = json_decode($data, true);

        // Vérifier que les données existent
        if ($sections) {
            foreach ($sections as $section) {
                echo "<div class='section'>";
                echo "<h1 class=''>" . $section["titre"] . "</h1>";

                // Affichage des blocs de contenu
                foreach ($section["contenu"] as $bloc) {
                    if ($bloc["type"] === "texte") {
                        echo "<p>" . nl2br($bloc["valeur"]) . "</p>";
                    } elseif ($bloc["type"] === "image") {
                        echo "<img src='" . $bloc["valeur"] . "' alt='" . $bloc["alt"] . "' />";
                    }
                }

                echo "</div>";
            }
        } else {
            echo "<p>Erreur lors du chargement des données.</p>";
        }
        ?>
        </main>
    </body>
</html>
<style>
    .section {
        width: 50%;
    }
    </style>
<script>

</script>