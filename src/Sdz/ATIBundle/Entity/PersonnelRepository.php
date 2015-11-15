<?php

namespace Sdz\ATIBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * PersonnelRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PersonnelRepository extends EntityRepository
{
	public function getPersonnels($nombreParPage,$page)
	{
		if ($page < 1) 
		{
			throw new \InvalidArgumentException('L\'argument $page ne peut être inférieur à 1 (valeur : "'.$page.'").');
		}
		
		$query = $this->createQueryBuilder('p')
					  ->orderBy('p.nom','ASC')
					  ->getQuery();
					  
		$query->setFirstResult(($page-1)*$nombreParPage)
			  ->setMaxResults($nombreParPage);
		
		
					  
		return new Paginator($query);
	}

	public function myFindOne($id)
	{
	// On passe par le QueryBuilder vide de l'EntityManager pour  l'exemple
	$qb = $this->createQueryBuilder('p');
	$qb->where('p.id = :id')
	   ->setParameter('id', $id);
	 
	return $qb->getQuery()->getResult();
	}
}