<?php

namespace Sdz\ATIBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * EntreeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SortieRepository extends EntityRepository
{
	public function getSorties($nombreParPage,$page)
	{
		if ($page < 1)
		{
			throw new \InvalidArgumentException('L\'argument $page ne peut être inférieur à 1 (valeur : "'.$page.'").');
		}
		
		$query = $this->createQueryBuilder('s')
					  ->leftJoin('s.magasin','m')
					  ->addSelect('m')
					  ->leftJoin('s.produit','p')
					  ->addSelect('p')
					  ->leftJoin('s.user','u')
					  ->addSelect('u')
					  ->orderBy('p.date','DESC')
					  ->getQuery();
					  
		$query->setFirstResult(($page-1)*$nombreParPage)
			  ->setMaxResults($nombreParPage);
		
		
					  
		return new Paginator($query);
	}

	public function getSortiesMonth()
	{
		
		$query = $this->createQueryBuilder('s')
					  ->leftJoin('s.magasin','m')
					  ->addSelect('m')
					  ->leftJoin('s.produit','p')
					  ->addSelect('p')
					  ->leftJoin('s.user','u')
					  ->addSelect('u')
					  ->orderBy('p.date','DESC')
					  ->getQuery();
					  
					 
		
					  
		return $query;
	}
}
