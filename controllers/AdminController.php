<?php





/**
 * Contrôleur de la partie admin.
 */

class AdminController
{
    /**
     * Affiche la page d'administration.
     * @return void
     */
    public function showAdmin(): void
    {
        // On vérifie que l'utilisateur est connecté.
        $this->checkIfUserIsConnected();

        // On récupère les articles.
        $articleManager = new ArticleManager();
        $articles = $articleManager->getAllArticles();

        $commentManager = new CommentManager();
        $comments = $commentManager->getAllComments();

        // On affiche la page d'administration.
        $view = new View("Administration");
        $view->render("admin", [
            'articles' => $articles,
            'comments' => $comments
        ]);
    }

    /**
     * Vérifie que l'utilisateur est connecté.
     * @return void
     */
    private function checkIfUserIsConnected(): void
    {
        // On vérifie que l'utilisateur est connecté.
        if (!isset($_SESSION['user'])) {
            Utils::redirect("connectionForm");
        }
    }

    /**
     * Affichage du formulaire de connexion.
     * @return void
     */
    public function displayConnectionForm(): void
    {
        $view = new View("Connexion");
        $view->render("connectionForm");
    }

    /**
     * Connexion de l'utilisateur.
     * @return void
     */
    public function connectUser(): void
    {
        // On récupère les données du formulaire.
        $login = Utils::request("login");
        $password = Utils::request("password");

        // On vérifie que les données sont valides.
        if (empty($login) || empty($password)) {
            throw new Exception("Tous les champs sont obligatoires. 1");
        }

        // On vérifie que l'utilisateur existe.
        $userManager = new UserManager();
        $user = $userManager->getUserByLogin($login);
        if (!$user) {
            throw new Exception("L'utilisateur demandé n'existe pas.");
        }

        // On vérifie que le mot de passe est correct.
        if (!password_verify($password, $user->getPassword())) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            throw new Exception("Le mot de passe est incorrect : $hash");
        }

        // On connecte l'utilisateur.
        $_SESSION['user'] = $user;
        $_SESSION['idUser'] = $user->getId();

        // On redirige vers la page d'administration.
        Utils::redirect("admin");
    }

    /**
     * Déconnexion de l'utilisateur.
     * @return void
     */
    public function disconnectUser(): void
    {
        // On déconnecte l'utilisateur.
        unset($_SESSION['user']);

        // On redirige vers la page d'accueil.
        Utils::redirect("home");
    }

    /**
     * Affichage du formulaire d'ajout d'un article.
     * @return void
     */
    public function showUpdateArticleForm(): void
    {
        $this->checkIfUserIsConnected();

        // On récupère l'id de l'article s'il existe.
        $id = Utils::request("id", -1);

        // On récupère l'article associé.
        $articleManager = new ArticleManager();
        $article = $articleManager->getArticleById($id);

        // Si l'article n'existe pas, on en crée un vide.
        if (!$article) {
            $article = new Article();
        }

        // On affiche la page de modification de l'article.
        $view = new View("Edition d'un article");
        $view->render("updateArticleForm", [
            'article' => $article
        ]);
    }

    /**
     * Ajout et modification d'un article.
     * On sait si un article est ajouté car l'id vaut -1.
     * @return void
     */
    public function updateArticle(): void
    {
        $this->checkIfUserIsConnected();

        // On récupère les données du formulaire.
        $id = Utils::request("id", -1);
        $title = Utils::request("title");
        $content = Utils::request("content");

        // On vérifie que les données sont valides.
        if (empty($title) || empty($content)) {
            throw new Exception("Tous les champs sont obligatoires. 2");
        }

        // On crée l'objet Article.
        $article = new Article([
            'id' => $id, // Si l'id vaut -1, l'article sera ajouté. Sinon, il sera modifié.
            'title' => $title,
            'content' => $content,
            'id_user' => $_SESSION['idUser']
        ]);

        // On ajoute l'article.
        $articleManager = new ArticleManager();
        $articleManager->addOrUpdateArticle($article);

        // On redirige vers la page d'administration.
        Utils::redirect("admin");
    }


    /**
     * Suppression d'un article.
     * @return void
     */
    public function deleteArticle(): void
    {
        $this->checkIfUserIsConnected();

        $id = Utils::request("id", -1);

        // On supprime l'article.
        $articleManager = new ArticleManager();
        $articleManager->deleteArticle($id);

        // On redirige vers la page d'administration.
        Utils::redirect("admin");
    }

    public function monitoringAdmin(): void
    {
        $this->checkIfUserIsConnected();

        $sort = Utils::request("sort", "dateCreation");
        $order = strtoupper(Utils::request("order", "DESC"));

        $commentManager = new CommentManager();
        $comments = $commentManager->getAllCommentsForAdmin();

        $articleManager = new ArticleManager();
        $articles = $articleManager->getAllArticlesForAdmin();

        $articles = $this->sortArticles($articles, $sort, $order);

        $view = new View("Tableau de bord");
        $view->render("monitoringAdmin", ['articles' => $articles, 'sort' => $sort, 'order' => $order, 'comments' => $comments]);
    }

    private function sortArticles(array $articles, string $sort, string $order): array
    {
        usort($articles, function (Article $a, Article $b) use ($sort, $order) {
            $valueA = null;
            $valueB = null;

            switch ($sort) {
                case "title":
                    $valueA = $a->getTitle();
                    $valueB = $b->getTitle();
                    break;
                case "nbView":
                    $valueA = $a->getNbView();
                    $valueB = $b->getNbView();
                    break;
                case "nbComments":
                    $valueA = $a->getNbComments();
                    $valueB = $b->getNbComments();
                    break;
                case "dateCreation":
                    $valueA = $a->getDateCreation()->getTimestamp();
                    $valueB = $b->getDateCreation()->getTimestamp();
                    break;
                case "dateUpdate":
                    $valueA = $a->getDateUpdate() ? $a->getDateUpdate()->getTimestamp() : 0;
                    $valueB = $b->getDateUpdate() ? $b->getDateUpdate()->getTimestamp() : 0;
                    break;
            }

            if ($order === "ASC") {
                return ($valueA <=> $valueB);
            } else {
                return ($valueB <=> $valueA);
            }
        });

        return $articles;
    }

    public function adminComments(): void
    {
        $this->checkIfUserIsConnected();

        $articleId = (int) Utils::request("articleId", -1);

        $articleManager = new ArticleManager();
        $article = $articleManager->getArticleById($articleId);



        $commentManager = new CommentManager();
        $comments = $commentManager->getAllCommentsByArticleId($articleId);

        $view = new View("Commentaires de l'article " . $articleId);
        $view->render("adminComments", [
            'article' => $article,
            'comments' => $comments,
            'articleId' => $articleId
        ]);
    }

    public function deleteComment(): void
    {
        $this->checkIfUserIsConnected();

        $commentId = (int) Utils::request("commentId", -1);
        $articleId = (int) Utils::request("articleId", -1);

        $commentManager = new CommentManager();
        $commentManager->deleteCommentById($commentId);

        Utils::redirect("adminComments&articleId=" . $articleId);
    }
}
