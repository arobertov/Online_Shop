<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use Doctrine\ORM\NoResultException;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
	/**
	 * @var EntityManagerInterface $em
	 */
	private $em;

	/**
	 * UserRepository constructor.
	 *
	 * @param EntityManagerInterface $em
	 */
	public function __construct(EntityManagerInterface $em ) {
		parent::__construct( $em, new Mapping\ClassMetadata(User::class) );
		$this->em = $em;
	}

	/**
	 * @param User $user
	 */
	public function createUser( User $user ){
		$em = $this->em;
		$em->persist($user);
		$em->flush();
	}

	/**
	 * @param User $user
	 */
	public function updateUser( User $user ){
		$this->em->persist( $user );
		$this->em->flush();
	}

	/**
	 * @param User $user
	 */
	public function deleteUser( User $user ){
		$this->em->remove( $user );
		$this->em->flush();
	}

	/**
	 * @param array $roleName
	 *
	 * @return Role|array
	 */
	public function findRoleUser(Array $roleName){
		$em = $this->em;
		return $em->getRepository(Role::class)->findOneBy($roleName);
	}

	/**
	 * @param $id
	 *
	 * @return mixed|null
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function findUserJoinAddress($id){
		$em = $this->em;
		$query = $em->createQuery("SELECT u, adr 
									  	FROM AppBundle:User u 
									  	JOIN u.address adr 
									  	WHERE u.id = :id")
		;
		$query->setParameter('id',$id);
		try{
			return $query->getSingleResult();
		} catch ( NoResultException $e) {
			return null;
		}
	}
}
