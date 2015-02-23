<?php

namespace Application\Bundle\FrontBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * RecordsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RecordsRepository extends EntityRepository {

    public function findOrganizationRecords($organizationID) {
        $query = $this->getEntityManager()
                ->createQuery("SELECT r from ApplicationFrontBundle:Records r "
                . "JOIN r.user u "
                . "JOIN u.organizations o "
                . "WHERE o.id =  :organization");
        $query->setParameter('organization', $organizationID);

        return $query->getResult();
    }

    public function findAudioRecordById($id) {
        return $this->getEntityManager()->createQuery("SELECT r as record, ar as audio, m.name as mediaType, p.name as projectTitle"
                                . " FROM ApplicationFrontBundle:Records r"
                                . " JOIN ApplicationFrontBundle:MediaTypes m WITH r.mediaType = m.id"
                                . " JOIN ApplicationFrontBundle:Projects p WITH r.project = p.id"
                                . " JOIN ApplicationFrontBundle:AudioRecords ar WITH ar.record = r.id "
                                . " Where r.id = $id"
                        )
                        ->getArrayResult();
    }

    public function findRecordsByType($typeRecordId, $typeId) {
        $where = "";
        $join = '';
        if ($typeId == 1) {
            $join = "JOIN r.audioRecord a ";
            $where = "WHERE a.id =  :typeRecordId";
        } elseif ($typeId == 2) {
            $join = "JOIN r.filmRecord f ";
            $where = "WHERE f.id =  :typeRecordId";
        } else {
            $join = "JOIN r.videoRecord v ";
            $where = "WHERE v.id =  :typeRecordId";
        }
        $query = $this->getEntityManager()
                ->createQuery("SELECT r from ApplicationFrontBundle:Records r "
                . "JOIN r.user u "
                . "JOIN u.organizations o "
                . $join
//        . $join
                . $where);
        $query->setParameter('typeRecordId', $typeRecordId);

        return $query->getSingleResult();
    }

    public function findRecordsByIds($ids) {
        $query = $this->getEntityManager()
                ->createQuery("SELECT r from ApplicationFrontBundle:Records r "
                . "WHERE r.id IN  (:ids)");
        $query->setParameter('ids', $ids);

        return $query->getResult();
    }

    public function findAllUniqueIds() {
        $uniqueids = $this->getEntityManager()->createQuery('SELECT r.uniqueId'
                        . ' from ApplicationFrontBundle:Records r'
                )->getScalarResult();
        $ids = array_map("current", $uniqueids);

        return $ids;
    }

    public function findRecordsByIdsArray($ids) {
        $query = $this->getEntityManager()->createQuery("SELECT r as record, ar as audio, m.name as mediaType, vr as video, fr as film, p.name as projectTitle"
                        . " FROM ApplicationFrontBundle:Records r"
                        . " LEFT JOIN ApplicationFrontBundle:MediaTypes m WITH r.mediaType = m.id"
                        . " LEFT JOIN ApplicationFrontBundle:Projects p WITH r.project = p.id"
                        . " LEFT JOIN ApplicationFrontBundle:AudioRecords ar WITH ar.record = r.id "
                        . " LEFT JOIN ApplicationFrontBundle:VideoRecords vr WITH vr.record = r.id "
                        . " LEFT JOIN ApplicationFrontBundle:FilmRecords fr WITH fr.record = r.id "
                        . " Where r.id = $ids"
                )
                ->getResult(2);
        return $query;
    }

    public function findOrganizationUniqueidRecords($organizationID, $unique_id) {
        $query = $this->getEntityManager()
                ->createQuery("SELECT r from ApplicationFrontBundle:Records r "
                . "JOIN r.user u "
                . "JOIN u.organizations o "
                . "WHERE o.id =  :organization AND r.uniqueId = :unique");
        $query->setParameter('organization', $organizationID);
        $query->setParameter('unique', $unique_id);
        return $query->getResult();
    }

    public function findOrganizationUniqueRecordsEdit($organizationID, $unique_id, $id) {
        $query = $this->getEntityManager()
        ->createQuery("SELECT r from ApplicationFrontBundle:Records r "
        . "JOIN r.user u "
        . "JOIN u.organizations o ");
        if ($id == 0) {
            $query->where('o.id =  :organization AND r.uniqueId = :unique');
        }else{
            $query->where('o.id =  :organization AND r.uniqueId = :unique AND r.id != :id');
            $query->setParameter('id', $id);
        }
        $query->setParameter('organization', $organizationID);
        $query->setParameter('unique', $unique_id);
        
        return $query->getResult();
    }

}
