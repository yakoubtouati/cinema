<?php

/* Connexion à une base MySQL avec l'invocation de pilote */
$dsnDb = 'mysql:dbname=projetCinema;host=127.0.0.1;port=8889';
$userDb = 'root';
$passwordDb = 'root';

try
{
    $db = new PDO($dsnDb, $userDb, $passwordDb);
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}
catch ( \PDOException $e)
{
  die("Erreur de connexion :" .$e->getMessage());
}

?>