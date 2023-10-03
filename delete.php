<?php
session_start();

// si l'identifiant du film a supprimer n'existe pas ou sa valeur est vide 

if ( !isset($_GET['film_id']) || empty($_GET['film_id'])  ){

    // rediriger l'utilisateur vers la page d'accueil
    // puis arreter l'éxecution de script 
    return header("Location:index.php");

}

// dans le cas contraire 

    // protéger le serveur contre les failes de type xss 
    $filmId =(int) htmlspecialchars($_GET['film_id']);

    // Etablir une connexion avec la bas de données 
    require __DIR__ ."/db/connexion.php";

    // effectuer la requete permettent du séléctionner le film dont l'identifiant a été récupéré depuis l'url 

    $req=$db->prepare("SELECT * FROM film WHERE id=:id");

    $req->bindValue(":id",$filmId);

    $req->execute();

    // compte le nombre d'enregistrement  récupéré de la base 
    $row=$req->rowCount();
    
    // si ce nombre n'est pas égal a 1 , c'est que film n'éxiste pas 
    if ( $row !=1){
        // rediriger l'utilisateur vers la page d'accueil 
        // puis arréter l'éxécution du script 

        return header("Location:index.php");
    }

    // dans le cas contraire 
    
        // Effectuer la requete de suppression du film en bas
        $deleteReq=$db->prepare("DELETE FROM film WHERE id=:id");
        $deleteReq->bindValue(":id",$filmId);
        $deleteReq->execute();

        $deleteReq->closeCursor();


    // Générer le message flash de succées de l'opération de suppression 
    $_SESSION['success']="Le film a été suprimer avec succés";

    // rediriger vers la page d'accueil et arréter l'exécution de script 
    return header("Location:index.php");

     
        ?>