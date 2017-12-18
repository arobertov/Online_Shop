<?php

namespace AppBundle\Repository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
	public function findSuperAdminUser(){
		$em = $this->getEntityManager();
		$query = $em->createQuery("SELECT u FROM AppBundle:User u JOIN u.roles r WHERE r.name = 'ROLE_SUPER_ADMIN'");
		try{
			return $query->getSingleResult();
		}   catch (\Doctrine\ORM\NoResultException $e) {
			return null;
		}
	}
}
