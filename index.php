<?php
include('config/db.php');

include('config/autoload.php');

include('classes/PersonnagesManager.php');



$manager = new PersonnagesManager($db);

if (isset($_SESSION['username'])) // Si la session perso existe, on restaure l'objet.
{
  $username = $_SESSION['username'];
}

if (isset($_POST['create']) && isset($_POST['name'])) // Si on a voulu créer un personnage.
{
  $username = new Personnage(['name' => $_POST['name']]); // On crée un nouveau personnage.
  
  if (!$username->validName())
  {
    $message = 'Le nom choisi est invalide.';
    unset($username);
  }
  elseif ($manager->exists($username->name()))
  {
    $message = 'Le nom du personnage est déjà pris.';
    unset($username);
  }
  else
  {
    $manager->add($username);
  }
}

elseif (isset($_POST['use']) && isset($_POST['name'])) // Si on a voulu utiliser un personnage.
{
  if ($manager->exists($_POST['name'])) // Si celui-ci existe.
  {
    $username = $manager->get($_POST['name']);
  }
  else
  {
    $message = 'Ce personnage n\'existe pas !'; // S'il n'existe pas, on affichera ce message.
  }
}

elseif (isset($_GET['hit'])) // Si on a cliqué sur un personnage pour le frapper.
{
  if (!isset($username))
  {
    $message = 'Merci de créer un personnage ou de vous identifier.';
  }
  
}

?>
<!DOCTYPE html>
<html>
  <head>
    <title>TP : Mini jeu de combat</title>
    
    <meta charset="utf-8" />
  </head>
  <body>
    <p>Nombre de personnages créés : <?= $manager->count() ?></p>
<?php
if (isset($message)) // On a un message à afficher ?
{
  echo '<p>', $message, '</p>'; // Si oui, on l'affiche.
}

if (isset($username)) // Si on utilise un personnage (nouveau ou pas).
{
?>
    <p><a href="?logout=1">Déconnexion</a></p>
    
    <fieldset>
      <legend>Mes informations</legend>
      <p>
        Nom : <?= htmlspecialchars($username->name()) ?><br />
        Dégâts : <?= $username->damages() ?>
      </p>
    </fieldset>
    
    <fieldset>
      <legend>Qui frapper ?</legend>
      <p>
<?php
$usernames = $manager->getList($username->name());

if (empty($usernames))
{
  echo 'Personne à frapper !';
}

else
{
  foreach ($usernames as $aUsername)
  {
    echo '<a href="?hit=', $aUsername->id(), '">', htmlspecialchars($aUsername->name()), '</a> (damages : ', $aUsername->damages(), ')<br />';
  }
}
?>
      </p>
    </fieldset>
<?php
}
else
{
?>
    <form action="" method="post">
      <p>
        Nom : <input type="text" name="name" maxlength="50" />
        <input type="submit" value="Créer ce personnage" name="create" />
        <input type="submit" value="Utiliser ce personnage" name="use" />
      </p>
    </form>
<?php
}
?>
  </body>
</html>
<?php
if (isset($username)) // Si on a créé un personnage, on le stocke dans une variable session afin d'économiser une requête SQL.
{
  $_SESSION['username'] = $username;
}