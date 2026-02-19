<?php

//*Affichages et suppression des commentaires pour un article donné pour l'administareur.*

?>

<h3>Commentaires de l'article : <?= Utils::format($article->getTitle()) ?></h3>

<div class="adminComments">
    <?php if (empty($comments)) { ?>
        <p class="info">Aucun commentaire pour cet article.</p>
    <?php } else { ?>
        <ul>
            <?php foreach ($comments as $comment) { ?>
                <li>
                    <div class="detailComment">
                        <h3 class="info">Le <?= Utils::convertDateToFrenchFormat($comment->getDateCreation()) ?>, <?= Utils::format($comment->getPseudo()) ?> a écrit :</h3>
                        <p class="content"><?= Utils::format($comment->getContent()) ?></p>
                        <a class="submit"
                            href="index.php?action=deleteComment&commentId=<?= $comment->getId() ?>&articleId=<?= $articleId ?>"
                            <?= Utils::askConfirmation("Êtes-vous sûr de vouloir supprimer ?") ?>>
                            Supprimer
                        </a>


                    </div>
                </li>

            <?php } ?>

            <a class="submit" href="index.php?action=monitoringAdmin">Retour</a>
        </ul>
    <?php } ?>