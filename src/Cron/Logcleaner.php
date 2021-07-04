<?php
/**
 *
 * Copyright 2020 LotsofPixels. All rights reserved.
 * https://www.lotsofpixels.nl
 * Amsterdam
 *
 */

namespace LotsofPixels\Logcleaner\Cron;

use \Psr\Log\LoggerInterface;

class Logcleaner {

    protected $logger;

    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }

	/**
    * cleanup the logs tables.
    *
    * @return void
    */

    public function execute() {

        $this->logger->info('Db Log cleaning cron started..!');
        //Get Object Manager Instance
        $objectManager  = \Magento\Framework\App\ObjectManager::getInstance();
        $resource       = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection     = $resource->getConnection();

        $tablesToTurncate = array(
                            'report_event',
                            'report_viewed_product_index',
                            'report_compared_product_index',
                            'customer_visitor',
                            'report_viewed_product_aggregated_daily',
                            'report_viewed_product_aggregated_monthly',
                            'report_viewed_product_aggregated_yearly',
                            'product_alert_stock',
                            'search_query',
                            'catalogsearch_fulltext_scope1',
                            'sales_bestsellers_aggregated_yearly',
                            'sales_bestsellers_aggregated_monthly',
                            'sales_bestsellers_aggregated_daily',
                            'mst_search_report_log'
                        );
        foreach($tablesToTurncate as $_key => $value){

            $tableName  = $resource->getTableName($value);
            $sql        = "TRUNCATE ".$tableName;
            
            try{
                $connection->query($sql);
                $this->logger->info($tableName.' cleaned up.');
            }catch(\Exception $e){
                $this->logger->critical($e);
            }
        }
        $this->logger->info('Db Log cleaning cron ended..!');

        return $this;
    }

}