<?php
/**
 * Created by PhpStorm.
 * User: Matteo Scirea
 * Date: 09/01/2021
 * Time: 17:23
 */

namespace Controllers\Libraries\Check;


use Controllers\Libraries\Definitions\Costanti;

class CheckGetParameters {

    /**
     * Metodo per il controllo della presenza dei parametri obbligatori
     * @return array
     */
    public function __checkRequiredParameters() {

        if(!empty($_GET['node_id'])) {

            if(!empty($_GET['language'])) {

                $esito  = Costanti::DEFAULT_TRUE;
                $code   = Costanti::SUCCESSO;

            } else {

                $esito  = Costanti::DEFAULT_FALSE;
                $code   = Costanti::NO_LANGUAGE_VALUE_PRESENT;

            }

        } else {

            $esito  = Costanti::DEFAULT_FALSE;
            $code   = Costanti::NO_NODE_ID_VALUE_PRESENT;
        }

        $result = array(
            'code'  => $code,
            'esito' => $esito

        );

        //Liberiamo memoria
        unset($esito, $code);

        return $result;

    }

}