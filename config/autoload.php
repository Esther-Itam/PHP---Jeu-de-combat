<?php
include('classes/Personnage.php');

// On enregistre notre autoload.
function changeClass($classname)
{
  require($classname.'.php');
}

spl_autoload_register('changeClass');

session_start(); // On appelle session_start() APRÈS avoir enregistré l'autoload.

if (isset($_GET['logout']))
{
  session_destroy();
  header('Location: ./index.php');
  exit();
}


