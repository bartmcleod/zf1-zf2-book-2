<?php
/**
 * ZF 1 -> ZF 2 migration
 *
 * Example of a TableGateway that uses the RowGatewayFeature.
 *
 * This has the advantage that records returned know where they live
 * in the database, so they can update or delete themselves.
 *
 * @author Bart McLeod (mcleod@spaceweb.nl)
 */
namespace Book\Table;


use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature\FeatureSet;
use Zend\Db\TableGateway\Feature\RowGatewayFeature;

class PersonRowTable extends AbstractTableGateway  {
    protected $table = 'person';

    public function __construct($adapter)
    {
        $this->adapter = $adapter;
        $this->featureSet = new FeatureSet();
        $this->getFeatureSet()->addFeature(new RowGatewayFeature('id'));
    }
}