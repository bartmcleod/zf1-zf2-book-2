<?php
/**
 * ZF 1 -> ZF 2 migration
 *
 * Example of a RowGateway.
 *
 * This RowGateway plays the role of a mapper and in addition,
 * it knows whether it exists in the database or not, because the
 * table uses an auto_increment key. When the key exists, the record
 * exists. It also uses a Global database adapter.
 *
 * @author Bart McLeod (mcleod@spaceweb.nl)
 */

namespace Book\Mapper;


use Zend\Db\RowGateway\AbstractRowGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;

class PersonEasySaveMapper extends AbstractRowGateway {
    protected $primaryKeyColumn = 'id';
    protected $table = 'person';

    public function __construct()
    {
        $this->sql = new Sql(
            GlobalAdapterFeature::getStaticAdapter(),
            $this->table
        );
    }

    /**
     * @return bool
     */
    public function rowExistsInDatabase()
    {
        $this->processPrimaryKeyData();
        return ($this->primaryKeyData !== null);
    }

}