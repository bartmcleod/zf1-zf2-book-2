<?php
/**
 * ZF 1 -> ZF 2 migration
 *
 * Example of a TableGateway that uses a global adapter
 * and a RowGatewayFeature. This offers the following commodities:
 * - you can instantiate it without any arguments and it will work, if there is a global adapter
 * - rows returned from this gateway know how to save themselves to the database, whether they
 * are new or existing and they can also delete themselves.
 *
 * @author Bart McLeod (mcleod@spaceweb.nl)
 */
namespace Book\Table;


use Book\Mapper\PersonEasySaveMapper;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature\FeatureSet;
use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;
use Zend\Db\TableGateway\Feature\RowGatewayFeature;

class PersonEasySaveTable extends AbstractTableGateway  {
    protected $table = 'person';

    public function __construct()
    {
        $this->featureSet = new FeatureSet();
        $this->featureSet->addFeature(new RowGatewayFeature(new PersonEasySaveMapper()));
        $this->featureSet->addFeature(new GlobalAdapterFeature());
    }
}