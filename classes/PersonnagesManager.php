<?php

class PersonnagesManager
{
  private $db; // Instance de PDO
  
  public function __construct($db)
  {
    $this->setDb($db);
  }
  
  public function add(Personnage $username)
  {
    $q = $this->db->prepare('INSERT INTO personnages(name) VALUES(:name)');
    $q->bindValue(':name', $username->name());
    $q->execute();
    
    $username->hydrate([
      'id' => $this->db->lastInsertId(),
      'degats' => 0,
    ]);
  }
  
  public function count()
  {
    return $this->db->query('SELECT COUNT(*) FROM personnages')->fetchColumn();
  }
  
  public function delete(Personnage $username)
  {
    $this->db->exec('DELETE FROM personnages WHERE id = '.$username->id());
  }
  
  public function exists($info)
  {
    if (is_int($info)) // On veut voir si tel personnage ayant pour id $info existe.
    {
      return (bool) $this->db->query('SELECT COUNT(*) FROM personnages WHERE id = '.$info)->fetchColumn();
    }
    
    // Sinon, c'est qu'on veut vérifier que le nom existe ou pas.
    
    $q = $this->db->prepare('SELECT COUNT(*) FROM personnages WHERE name = :name');
    $q->execute([':name' => $info]);
    
    return (bool) $q->fetchColumn();
  }
  
  public function get($info)
  {
    if (is_int($info))
    {
      $q = $this->db->query('SELECT id, name, damages FROM personnages WHERE id = '.$info);
      $datas = $q->fetch(PDO::FETCH_ASSOC);
      
      return new Personnage($datas);
    }
    else
    {
      $q = $this->db->prepare('SELECT id, name, damages FROM personnages WHERE name = :name');
      $q->execute([':name' => $info]);
    
      return new Personnage($q->fetch(PDO::FETCH_ASSOC));
    }
  }
  
  public function getList($name)
  {
    $usernames = [];
    
    $q = $this->db->prepare('SELECT id, name, damages FROM personnages WHERE name <> :name ORDER BY name');
    $q->execute([':name' => $name]);
    
    while ($datas = $q->fetch(PDO::FETCH_ASSOC))
    {
      $usernames[] = new Personnage($datas);
    }
    
    return $usernames;
  }
  
  public function update(Personnage $username)
  {
    $q = $this->db->prepare('UPDATE personnages SET damages = :damages WHERE id = :id');
    
    $q->bindValue(':damages', $username->damages(), PDO::PARAM_INT);
    $q->bindValue(':id', $username->id(), PDO::PARAM_INT);
    
    $q->execute();
  }
  
  public function setDb(PDO $db)
  {
    $this->db = $db;
  }
}  
 