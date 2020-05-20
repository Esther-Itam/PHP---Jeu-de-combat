<?php

include('classes/PersonnagesManager.php');

if (!$manager->exists((int) $_GET['hit']))
{
  $message = 'Le personnage que vous voulez frapper n\'existe pas !';
}

else
{
  $hitUsername = $manager->get((int) $_GET['hit']);
  
  $return = $username->hit($hitUsername); // On stocke dans $retour les éventuelles erreurs ou messages que renvoie la méthode frapper.
  
  switch ($return)
  {
    case Personnage::ME :
      $message = 'Mais... pourquoi voulez-vous vous frapper ???';
      break;
    
    case Personnage::PERSONNAGE_HIT :
      $message = 'Le personnage a bien été frappé !';
      
      $manager->update($username);
      $manager->update($hitUsername);
      
      break;
    
    case Personnage::PERSONNAGE_KILL :
      $message = 'Vous avez tué ce personnage !';
      
      $manager->update($username);
      $manager->delete($hitUsername);
      
      break;
  }
}

