<?php

class Personnage
{
  private $damages;
  private $id;
  private $name;
  
  const ME = 1; // Constante renvoyée par la méthode `frapper` si on se frappe soi-même.
  const PERSONNAGE_KILL = 2; // Constante renvoyée par la méthode `frapper` si on a tué le personnage en le frappant.
  const PERSONNAGE_HIT = 3; // Constante renvoyée par la méthode `frapper` si on a bien frappé le personnage.
  
  public function validName()
  {
    return !empty($this->name);
  }
  
  public function __construct(array $datas)
  {
    $this->hydrate($datas);
  }
  
  public function hit(Personnage $username)
  {
    if ($username->id() == $this->id)
    {
      return self::ME;
    }
    
    // On indique au personnage qu'il doit recevoir des dégâts.
    // Puis on retourne la valeur renvoyée par la méthode : self::PERSONNAGE_TUE ou self::PERSONNAGE_FRAPPE
    return $username->receiveDamages();
  }
  
  public function hydrate(array $datas)
  {
    foreach ($datas as $key => $value)
    {
      $method = 'set'.ucfirst($key);
      
      if (method_exists($this, $method))
      {
        $this->$method($value);
      }
    }
  }
  
  public function receiveDamages()
  {
    $this->damages += 5;
    
    // Si on a 100 de dégâts ou plus, on dit que le personnage a été tué.
    if ($this->damages >= 100)
    {
      return self::PERSONNAGE_KILL;
    }
    
    // Sinon, on se contente de dire que le personnage a bien été frappé.
    return self::PERSONNAGE_HIT;
  }
  
  
  // GETTERS //
  
  public function id()
  {
    return $this->id;
  }
  
  public function name()
  {
    return $this->name;
  }

  public function damages()
  {
    return $this->damages;
  }
  
   
  public function setDamages($damages)
  {
    $damages = (int) $damages;
    
    if ($damages >= 0 && $damages <= 100)
    {
      $this->damages = $damages;
    }
  }
  
  public function setId($id)
  {
    $id = (int) $id;
    
    if ($id > 0)
    {
      $this->id = $id;
    }
  }
  
  public function setName($name)
  {
    if (is_string($name))
    {
      $this->name = $name;
    }
  }
}