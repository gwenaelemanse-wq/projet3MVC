<?php

/**
 * Affichage de Liste des articles.
 */

?>

<div class="articleList">
    <?php foreach ($articles as $article) { ?>
        <article class="article">
            <h2><?= $article->getTitle() ?></h2>
            <span class="quotation">«</span>
            <p><?= $article->getContent(400) ?></p>
            <span class="views"><?= $article->getNbView() ?> vues</span>

            <div class="footer">
                <span class="info"> <?= ucfirst(Utils::convertDateToFrenchFormat($article->getDateCreation())) ?></span>
                <a class="info" href="index.php?action=showArticle&id=<?= $article->getId() ?>">Lire +</a>
            </div>
        </article>
    <?php } ?>
</div>
