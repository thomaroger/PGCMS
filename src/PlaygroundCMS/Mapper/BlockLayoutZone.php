<?php

/**
* @package : PlaygroundCMS
* @author : troger
* @since : 10/04/2014
*
* Classe qui permet de gérer le mapper de blockLayoutZone
**/

namespace PlaygroundCMS\Mapper;

class BlockLayoutZone extends EntityMapper
{
    
    /**
    * getEntityRepository : recupere l'entite blockLayoutZone
    *
    * @return PlaygroundCMS\Entity\BlockLayoutZone $blockLayoutZone
    */
    public function getEntityRepository()
    {
        if (null === $this->er) {
            $this->er = $this->em->getRepository('PlaygroundCMS\Entity\BlockLayoutZone');
        }

        return $this->er;
    }

    /**
    * getBlocksBelow : Permet de recuperer les blocs qui sont positionné après un bloc
    * @param BlockLayoutZone $blockLayoutZone 
    * @param int $position
    * 
    * @return array $results
    */
    public function getBlocksBelow($blockLayoutZone, $position)
    {
        $select  = " SELECT BLZ ";
        $from    = " FROM PlaygroundCMS\Entity\BlockLayoutZone BLZ";
        $where   = " WHERE BLZ.id != ".$blockLayoutZone->getId()." 
                     AND BLZ.layoutZone = ".$blockLayoutZone->getLayoutZone()->getId()."
                     AND BLZ.position >= ".$position;

        $query = $select.' '.$from.' '.$where;
        $results =  $this->em->createQuery($query)->getResult();

        return $results;
    }
}