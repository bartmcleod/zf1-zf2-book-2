<?php
/**
 * ZF 1 -> ZF 2 migration
 *
 * Example of a custom TableGateway.
 *
 * You do not need to and cannot pass the table name when you instantiate it.
 *
 * @author Bart McLeod (mcleod@spaceweb.nl)
 */
namespace Book\Table;


use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature\RowGatewayFeature;

class PersonTable extends AbstractTableGateway  {
    protected $table = 'person';

    public function __construct($adapter)
    {
        $this->adapter = $adapter;
    }
}