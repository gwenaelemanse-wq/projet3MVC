<?php

//* Affichage du tableau récapitulatif des articles avec le nombre de vues, de commentaires, la date de création et de dernière modification.

?>


<h2>Tableau de bord</h2>

<div class="monitoringAdmin">
    <div class="articleLine">
        <div class="title">Titre
            <a href="index.php?action=monitoringAdmin&sort=title&order=ASC">▲</a>
            <a href="index.php?action=monitoringAdmin&sort=title&order=DESC">▼</a>
        </div>

        <div class="content">Nombre de vues
            <a href="index.php?action=monitoringAdmin&sort=nbView&order=ASC">▲</a>
            <a href="index.php?action=monitoringAdmin&sort=nbView&order=DESC">▼</a>
        </div>
        <div class="content">Nombre de commentaires
            <a href="index.php?action=monitoringAdmin&sort=nbComments&order=ASC">▲</a>
            <a href="index.php?action=monitoringAdmin&sort=nbComments&order=DESC">▼</a>
        </div>
        <div class="content">Date de création
            <a href="index.php?action=monitoringAdmin&sort=dateCreation&order=ASC">▲</a>
            <a href="index.php?action=monitoringAdmin&sort=dateCreation&order=DESC">▼</a>
        </div>
        <div class="content">Date de dernière modification
            <a href="index.php?action=monitoringAdmin&sort=dateUpdate&order=ASC">▲</a>
            <a href="index.php?action=monitoringAdmin&sort=dateUpdate&order=DESC">▼</a>
        </div>
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