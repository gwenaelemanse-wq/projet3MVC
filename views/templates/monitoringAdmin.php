<?php

//* Affichage du tableau récapitulatif des articles avec le nombre de vues, de commentaires, la date de création et de dernière modification.

?>

<h2>Tableau de bord</h2>

<div class="monitoringAdmin">
    <div class="articleLine">
        <div class="title">Titre</div>
        <div class="content">Nombre de vues</div>
        <div class="content">Nombre de commentaires</div>
        <div class="content">Date de création</div>
        <div class="content">Date de dernière modification</div>
    </div>

    <?php foreach ($articles as $article) { ?>
        <div class="articleLine">
            <div class="title"><?= $article->getTitle() ?></div>
            <div class="content"><?= $article->getNbView() ?></div>
            <div class="content"><?= $article->getNbComments() ?></div>
            <div class="content"><?= Utils::convertDateToFrenchFormat($article->getDateCreation()) ?></div>
            <div class="content">
                <?= $article->getDateUpdate()
                    ? Utils::convertDateToFrenchFormat($article->getDateUpdate())
                    : "—"
                ?>
            </div>

        </div>
    <?php } ?>