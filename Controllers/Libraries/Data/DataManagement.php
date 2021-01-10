<?php
/**
 * Created by PhpStorm.
 * User: Matteo Scirea
 * Date: 10/01/2021
 * Time: 10:44
 */

namespace Controllers\Libraries\Data;



use Controllers\Libraries\Definitions\Costanti;

class DataManagement {

    protected $drPointer;

    /**
     * DataManagement constructor.
     */
    public function __construct() {

        $this->drPointer = new DataRead();

    }

    /**
     * @return array|null
     */
    public function __getdataInformation(){

        $esito = Costanti::DEFAULT_NULL;
        $hmc = $this->__howManyChild($_GET['node_id']);
        $nodeTreeRecord = $this->__getIdNodeInfoFromNodeTreeTable($hmc);
        if($nodeTreeRecord != Costanti::DEFAULT_FALSE) {

            $nodeTreeNameResults = $this->__getNodeTreeNamesValues($nodeTreeRecord, $_GET);
            if($nodeTreeNameResults != Costanti::DEFAULT_NULL) {

                $esito = $nodeTreeNameResults;

            }

        }

        return $esito;

    }

    /**
     * @param $__nodeId
     * @return null|string
     */
    public function __howManyChild($__nodeId) {

        $esito = Costanti::DEFAULT_NULL;
        $result = $this->drPointer->__getNodeIdFromId($__nodeId);
        if($result != Costanti::DEFAULT_FALSE) {

            $iDiff = $result['iRight'] - $result['iLeft'];
            if($iDiff == Costanti::DEFAULT_UNO) {

                $esito = Costanti::SINGOLO;

            } elseif($iDiff > Costanti::DEFAULT_UNO) {

                if($result['level'] == Costanti::DEFAULT_UNO) {

                    $esito = Costanti::PRINCIPALE;

                } else {

                    $esito = Costanti::MULTIPLO;

                }

            }

        }

        return $esito;

    }

    /**
     * Metodo per recuperare il record dell'idNode
     * @param $__howManyChild
     * @return mixed
     */
    public function __getIdNodeInfoFromNodeTreeTable($__howManyChild) {

        return $this->drPointer->__getChildNodeTree($_GET['node_id'], $__howManyChild);

    }

    /**
     * @param $__ntr
     * @param $__paramRequest
     * @return array|null
     */
    public function __getNodeTreeNamesValues($__ntr, $__paramRequest) {

        return $this->drPointer->__getNodeTreeNamesChild($__ntr, $__paramRequest);

    }

}