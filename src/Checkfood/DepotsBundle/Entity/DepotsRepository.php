<?php

namespace Checkfood\DepotsBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * DepotsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DepotsRepository extends EntityRepository {

    public function DeleteAll() {
        $cmd = $this->getClassMetadata();
        $connection = $this->getEntityManager()->getConnection();
        $connection->beginTransaction();

        try {
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
            $connection->query('DELETE FROM ' . $cmd->getTableName());
            $connection->query('ALTER TABLE ' . $cmd->getTableName() . ' AUTO_INCREMENT = 1');
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollback();
        }
    }

}
