<?php
session_start();

//Etablir une connexion avec la base de données 
require __DIR__ . '/db/connexion.php';

//Effectuer la requete de sélection des données  
$req = $db->prepare("SELECT * FROM film ORDER BY created_at DESC");

//l'exécuter
$req->execute();

//récuperer les données 
$films = $req->fetchAll();

//fermer le curseur (optionnel)   
$req->closeCursor();



?>
<?php
include __DIR__ . "/partials/head.php";
?>
<?php
include __DIR__ . "/partials/nav.php"
?>
<main class="container">
    <h1 class="text-center my-3 display-5">Liste des films</h1>

    <?php if (isset($_SESSION["success"]) && !empty(($_SESSION["success"]))) : ?>
        <div class="alert alert-success text-center" role="alert">
            <?php echo $_SESSION['success']; ?>
        </div>
        <?php unset($_SESSION['success']) ?>
    <?php endif ?>



    <div class="d-flex justify-content-end align-items-center my-3">
        <a href="create.php" class="btn btn-primary"> Nouveau Film</a>
    </div>
        <?php if (count($films)>0) : ?>
        <div class="container">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <?php foreach ($films as $film) : ?>
                        <div class="card shadow my-4">
                            <div class="card-body">
                                <p class="card-text">Nom du Film : <?php echo stripslashes($film['name']); ?></p>
                                <p class="card-text">Nom du/des acteurs : <?php echo stripslashes($film['actors']); ?></p>
                                <a data-bs-toggle="modal" data-bs-target="#modal<?php echo $film['id']; ?>" href="" class="text-dark mx-3"><i class="fa-solid fa-eye"></i></a>
                                <a href="edit.php?film_id=<?php echo $film['id'];?>" class="text-secondary mx-3"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a onclick="return confirm('confirmer la suppression')" href="delete.php?film_id=<?php echo $film['id'];?>" class="text-danger mx-3"><i class="fa-solid fa-trash-can"></i></a>


                            </div>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="modal<?php echo $film['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5">Nom du Film : <?php echo stripslashes($film['name']) ?></h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>La note : <?php echo isset($film['review']) && ($film['review'] != "") ? $film['review'] : "Non renseigné"; ?></p>
                                        <p>Le commentaire : <?php echo isset($film['comment']) && !empty($film['comment']) ? nl2br(stripslashes($film['comment'])) : "pas de commentaire"; ?></p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Femer</button>

                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>

        </div>
        <?php  else :?>
            <p class="text-center mt-5">Aucun film ajouté a la liste pour l'instant </p>
        <?php endif ?>
    


</main>

<?php
include __DIR__ . "/partials/footer.php"
?>

<?php
include __DIR__ . "/partials/foot.php"
?>