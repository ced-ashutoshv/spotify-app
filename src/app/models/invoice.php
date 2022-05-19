<?php

use Phalcon\Db\Adapter\Mysql;

class InvoiceComponent
{
    public function calculate()
    {
        $connection = new Mysql(
            [
                'host'     => 'localhost',
                'username' => 'root',
                'password' => 'secret',
                'dbname'   => 'tutorial',
            ]
        );
        
        $invoice = $connection->exec(
            'SELECT * FROM Invoices WHERE inv_id = 1'
        );

        echo '<pre>'; print_r( $invoice ); echo '</pre>'; die;
        // ...
    }
}

$invoice = new InvoiceComponent();
$invoice->calculate();