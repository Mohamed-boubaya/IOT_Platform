<?php

namespace AppBundle\Entity;

/**
 * 
 *
 * 
 *
 * repository methods below.
 *
 */
class EntrepriseRepository extends \Doctrine\ORM\EntityRepository
{

function findAllEntreprises($filter, $filterValue, $order){

  $query = $this->createQueryBuilder('t');
        $query = $query
            ->select('t');
        if (($filter!="all")and($filter!="")and($filterValue!="")){
            if ($filter=="name"){
                $query=$query->where('upper(t.name) like :name')
                    ->setParameter('name','%'.strtoupper($filterValue).'%');
            }else if ($filter=="competition"){
                $query=$query->where(':competition IN (competitions)')
                    ->setParameter('competition','%'.strtoupper($filterValue).'%');}
        }
        if (($order=="")||($order=="name")){
            $query=$query->orderBy('t.name','ASC');
        }elseif ($order=="createdAt"){
            $query=$query->orderBy('t.createdAt','ASC');
        }
        return $query ->getQuery()->getResult();
    }



  function findEntrepriseBySlug($slug){
        $query = $this->createQueryBuilder('t');
        return $query
            ->select('t')
            ->where('t.slug like :slug')->setParameter('slug',$slug)
           
            ->getQuery()->getSingleResult();
    }
}
