<?php
/**
 * Created by PhpStorm.
 * User: Matteo Scirea
 * Date: 09/01/2021
 * Time: 17:58
 */

namespace Controllers\Libraries\Check;


use Controllers\Libraries\Data\DataRead;
use Controllers\Libraries\Definitions\Costanti;

class CheckNodeId {

    protected $dataRead;

    /**
     * CheckNodeId constructor.
     */
    public function __construct(){

        $this->dataRead = new DataRead();

    }

    /**
     * Metodo per la verifica del parametro node_id passato nella chiamata (HTTTP GET)
     * @return array
     */
    public function __checkNodeIdValue() {

        $result = $this->__checkNodeIdParameter();
        if($result != Costanti::DEFAULT_FALSE) {

            $code   = Costanti::SUCCESSO;
            $esito  = $result;

        } else {

            $code   = Costanti::NODE_ID_ERROR;
            $esito  = Costanti::DEFAULT_FALSE;

        }

        $responseArray = [
            'code'  => $code,
            'esito' => $esito
        ];

        //Liberiamo memoria
        unset($code, $esito);

        return $responseArray;

    }

    /**
     * Metodo per la verifica della validità del node_id passato come parametro
     * @return mixed
     */
    public function __checkNodeIdParameter() {

        return $this->dataRead->__getNodeIdFromId($_GET['node_id']);

    }

}