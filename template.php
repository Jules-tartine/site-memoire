<?php
function parseMarkdown($text) {
    // Conversion des liens Markdown [texte](url) en lien cliquable
    return preg_replace('/\[([^\]]+)\]\((https?:\/\/[^\)]+)\)/', '<a href="$2" target="_blank">$1</a>', $text);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Mon Mémoire</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
// Chargement du fichier JSON global
$data = file_get_contents("data/memoire.json");
$memoire = json_decode($data, true);
?>

<!-- Menu principal -->
<nav id="menu-principal">
    <ul>
        <?php
        if(isset($memoire["menu_principal"])){
            foreach($memoire["menu_principal"] as $menuItem){
                echo '<li><a href="#" data-page="'. $menuItem["pageId"] .'">'. $menuItem["title"] .'</a></li>';
            }
        }
        ?>
    </ul>
</nav>

<!-- Conteneur des pages -->
<div id="content">
    <?php
    if ($memoire && isset($memoire["pages"])) {
        foreach ($memoire["pages"] as $pageIndex => $page) {
            $pageId = isset($page["id"]) ? $page["id"] : "page-" . $pageIndex;
            // Seule la première page est affichée par défaut
            $activeClass = ($pageIndex === 0) ? "active" : "";

            echo "<section class='page $activeClass' id='" . $pageId . "'>";
            echo "<h1 class='page-title'>" . $page["title"] . "</h1>";

            // Parcours des parties
            foreach ($page["parts"] as $partIndex => $part) {
                $partId = $pageId . "-part-" . $partIndex;
                echo "<article class='part' id='".$partId."'>";

                // Aside sticky pour les références
                echo "<aside class='references'>";
                echo "<h2>Références</h2>";
                if (isset($part["references"]) && is_array($part["references"])) {
                    echo "<ol>"; // Utilisation d'une liste ordonnée pour la numérotation
                    $refIndex = 1;
                    foreach ($part["references"] as $ref) {
                        $refText = nl2br(parseMarkdown($ref["valeur"]));
                        echo "<li><span class='ref-index'>$refIndex.</span> " . $refText . "</li>";
                        $refIndex++;
                    }
                    echo "</ol>";
                }
                echo "</aside>";

                // Contenu principal de la partie
                echo "<div class='content'>";
                if (isset($part["contenu"])) {
                    foreach ($part["contenu"] as $blocIndex => $bloc) {
                        $blocId = $partId . "-bloc-" . $blocIndex;
                        if ($bloc["type"] === "texte") {
                            $convertedText = nl2br(parseMarkdown($bloc["valeur"]));
                            echo "<p id='".$blocId."'>" . $convertedText . "</p>";
                        } elseif ($bloc["type"] === "image") {
                            echo "<figure id='".$blocId."'>";
                            echo "<img src='" . $bloc["valeur"] . "' alt='" . $bloc["alt"] . "' />";
                            if(isset($bloc["legende"]) && $bloc["legende"] !== ""){
                                echo "<figcaption>" . $bloc["legende"] . "</figcaption>";
                            }
                            echo "</figure>";
                        }
                    }
                }
                echo "</div>"; // Fin du contenu principal

                echo "</article>";
            }
            echo "</section>";
        }
    } else {
        echo "<p>Erreur lors du chargement des données.</p>";
    }
    ?>
</div>

<script src="script.js"></script>
</body>
</html>
