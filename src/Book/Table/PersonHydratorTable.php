<?php
/**
 * ZF 1 -> ZF 2 migration
 *
 * Example of a TableGateway that uses a resultSetPrototype.
 *
 * This offers the advantage that the objects return will be Person entities.
 *
 * @author Bart McLeod (mcleod@spaceweb.nl)
 */
namespace Book\Table;


use Book\Entity\PersonEntity;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature\FeatureSet;
use Zend\Db\TableGateway\Feature\RowGatewayFeature;
use Zend\Stdlib\Hydrator\ObjectProperty;

class PersonHydratorTable extends AbstractTableGateway  {
    protected $table = 'person';

    public function __construct($adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new HydratingResultSet(new ObjectProperty(), new PersonEntity());
    }
}