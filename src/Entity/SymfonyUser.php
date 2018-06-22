<?php
namespace App\Entity;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\Operation;
use App\Entity\Goal;

/**
 * @ORM\Entity
 * @ORM\Table(name="SymfonyUser")
 */
class SymfonyUser implements UserInterface, EquatableInterface, \Serializable {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $name;
    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $surname;
    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    protected $username;
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $password;

    protected $plain_password;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $salt;
    /**
     * @ORM\Column(type="array", nullable=true)
     */
    protected $roles;
    /**
     * @ORM\Column(type="string", length=60, unique=true)
     */
    protected $email;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $subscription_date;

    public function __construct(){
        $this->subscription_date = new \DateTime("now");
        $this->roles = array('ROLE_USER');
    }

    // GETTERS

    public function getId(){
        return $this->id;
    }

    public function getName(){
        return $this->name;
    }

    public function getSurname(){
        return $this->surname;
    }

    public function getUsername(){
        return $this->username;
    }

    public function getEmail(){
        return $this->email;
    }

    public function getPassword(){
        return $this->password;
    }

    public function getPlainPassword(){
        return $this->plain_password;
    }

    public function getSalt(){
        return $this->salt;
    }

    public function getRoles(){
        return $this->roles;
    }

    // SETTERS

    public function eraseCredentials(){
    }

    public function setName($name){
        $this->name = $name;
        return $this;
    }

    public function setSurname($surname){
        $this->surname = $surname;
        return $this;
    }

    public function setUsername($username){
        $this->username = $username;
        return $this;
    }

    public function setPassword($password){
        $this->password = $password;
        return $this;
    }
    public function setPlainPassword($plain_password){
        $this->plain_password = $plain_password;
        return $this;
    }

    public function setSalt($salt){
        $this->salt = $salt;
        return $this;
    }

    public function setEmail($email){
        $this->email = $email;
        return $this;
    }

    public function setRoles($roles){
        $this->roles = $roles;
        return $this;
    }

    // FUNCTIONS

    public function toArray(){
        return array(
            'username' => $this->username,
            'name' => $this->name,
            'surname' => $this->surname,
            'email' => $this->email,
            'subscription_date' => $this->subscription_date
        );
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->salt,
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->salt
        ) = unserialize($serialized);
    }

    public function isEqualTo(UserInterface $user){
        return $this->id === $user->getId();
    }

    public function getClass(){
        return 'SymfonyUser';
    }
}
?>
