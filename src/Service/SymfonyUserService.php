<?php
namespace App\Service;

use App\Entity\SymfonyUser;

use Doctrine\ORM\EntityManagerInterface;

class SymfonyUserService{

    private $entity_manager = null;

    public function __construct(
        EntityManagerInterface $entity_manager
    ){
        $this->entity_manager = $entity_manager;
    }

    public function readOneByEmail($email){
        $user = $this->entity_manager->getRepository(SymfonyUser::class)->findOneBy(array(
            'email' => $email
        ));
        return $user;
    }

    public function create(
        $args
    ){
        $user = new SymfonyUser();

        if(!empty($args['name'])){
            $user->setName($args['name']);
        }
        if(!empty($args['surname'])){
            $user->setSurname($args['surname']);
        }
        if(!empty($args['username'])){
            $user->setUsername($args['username']);
            $user->setEmail($args['username']);
        }
        if(!empty($args['encoded_password'])){
            $user->setPassword($args['encoded_password']);
        }
        if(!empty($args['roles'])){
            $user->setRoles($args['roles']);
        }

        $this->entity_manager->persist($user);
        $this->entity_manager->flush();

        return $user;
    }

    public function update(
        SymfonyUser $user,
        $args
    ){
        if(!empty($args['name'])){
            $user->setName($args['name']);
        }
        if(!empty($args['surname'])){
            $user->setSurname($args['surname']);
        }
        if(!empty($args['username'])){
            $user->setUsername($args['username']);
            $user->setEmail($args['username']);
        }
        if(!empty($args['encoded_password'])){
            $user->setPassword($args['encoded_password']);
        }
        if(!empty($args['roles'])){
            $user->setRoles($args['roles']);
        }

        $this->entity_manager->persist($user);
        $this->entity_manager->flush();

        return $user;
    }

}
?>
