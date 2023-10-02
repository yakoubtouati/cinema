<?php
session_start();
//  var_dump($_SERVER);
    // Mon serveur

    // Si les données arrivent au serveur via la méthode POST
    if ( $_SERVER['REQUEST_METHOD'] === "POST" )
    {

        $postClean = [];
        $errors    = [];

        // Pensons à la cybersécurité
        // Protéger le serveur contre les failles de type Xss
        foreach ($_POST as $key => $value) 
        {
            $postClean[$key] = htmlspecialchars(trim(addslashes($value)));
            // $postClean[$key] = strip_tags($value);
            // $postClean[$key] = htmlentities($value);
        }

        // Protéger le serveur contre les failles de type Csrf
        if ( 
            !isset($_SESSION['csrf_token']) || !isset($postClean['csrf_token']) ||
            empty($_SESSION['csrf_token'])  || empty($postClean['csrf_token']) ||
            $_SESSION['csrf_token'] !== $postClean['csrf_token']
        ) 
        {
            // Rediriger l'utilisateur vers la page de laquelle proviennent les informations
            // Arrêter l'exécution du script
            return header("Location: $_SERVER[HTTP_REFERER]");

            
        }
        
        // Protéger le serveur contre les robots spameurs
        if ( isset($postClean['honey_pot']) && !empty($postClean['honey_pot'])  ) 
        {
            // Rediriger l'utilisateur vers la page de laquelle proviennent les informations
            // Arrêter l'exécution du script
            return header("Location: $_SERVER[HTTP_REFERER]");

           
        }


        // Mettre en place les contraintes de validation des données provenant du formulaire

        // Commençons par le nom du film
        if ( isset($postClean['name']) ) 
        {
            if ( empty($postClean['name']) ) 
            {
                $errors['name'] = "Le nom du film est obligatoire.";
            }
            else if ( mb_strlen($postClean['name']) > 255 )
            {
                $errors['name'] = "Le nom ne doit pas dépasser 255 caractères.";
            }
        }

        // Commençons par le nom du/des acteurs
        if ( isset($postClean['actors']) ) 
        {
            if ( empty($postClean['actors']) ) 
            {
                $errors['actors'] = "Le nom du/des acteurs du film est obligatoire.";
            }
            else if ( mb_strlen($postClean['actors']) > 255 )
            {
                $errors['actors'] = "Le nom du/des acteurs ne doit pas dépasser 255 caractères.";
            }
        }
        
        // S'il y a des erreurs
        if ( count($errors) > 0 ) 
        {

            // Sauvegarder les messages d'erreur en session
            $_SESSION['form_errors']= $errors;
            // Sauvegarder les données précedemment envoyées en session
            
            // Rediriger l'utilisateur vers la page de laquelle proviennent les informations
            // Arrêter l'exécution du script
            return header("Location: $_SERVER[HTTP_REFERER]");
        }
        
        
        // Dans le cas contraire,
        
        // Arrondir la note à un chiffre après la virgule
        
        // Etablir une connexion avec la base de données
        
        // Effectuer la requête d'insertion des données en base
        
        // Créer un message flash de succès
        
        // Rediriger l'utilisateur vers la page d'accueil
        // Arrêter l'exécution du script
    }

    $_SESSION['csrf_token'] = bin2hex(random_bytes(30));

    
?>

<?php include __DIR__ . "/partials/head.php"; ?>

    <?php include __DIR__ . "/partials/nav.php"; ?>

    <!-- Le contenu spécifique à la page -->
    <main class="container my-5">
        <h1 class="text-center my-3 display-5">Nouveau film</h1>
            <?php if (isset ($_SESSION['form_errors']) && !empty($_SESSION['form_errors'])) :  ?>
                <div class="alert alert-danger text-center" >
                    <ul>
                        <?php foreach($_SESSION['form_errors'] as $error) : ?>
                            <li><?php echo $error?></li>
                        <?php endforeach ?>    
                    </ul>
                </div>
            <?php  endif ?>    
        <div class="container">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <form method="post">
                        <div class="mb-3">
                            <label for="name">Le nom du film <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="actors">Le nom du/des acteurs <span class="text-danger">*</span></label>
                            <input type="text" name="actors" id="actors" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="review">La note / 5</label>
                            <input type="number" min="0" max="5" step=".1" name="review" id="review" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="comment">Laissez un commentaire</label>
                            <textarea name="comment" id="comment" class="form-control" rows="4"></textarea>
                        </div>
                        <div class="mb-3 d-none">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        </div>
                        <div class="mb-3 d-none">
                            <input type="hidden" name="honey_pot" value="">
                        </div>
                        <div>
                            <input type="submit" class="btn btn-primary" value="Créer">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . "/partials/footer.php"; ?>

<?php include __DIR__ . "/partials/foot.php"; ?>