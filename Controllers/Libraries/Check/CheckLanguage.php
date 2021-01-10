<?php
/**
 * Created by PhpStorm.
 * User: Matteo Scirea
 * Date: 09/01/2021
 * Time: 18:03
 */

namespace Controllers\Libraries\Check;


use Controllers\Libraries\Data\DataRead;
use Controllers\Libraries\Definitions\Costanti;

class CheckLanguage {

    protected $dataRead;

    /**
     * CheckLanguage constructor.
     */
    public function __construct(){

        $this->dataRead = new DataRead();

    }

    /**
     * Metodo per la verifica del parametro language nella chiamata (HTTTP GET)
     * @return array
     */
    public function __checkLanguageValue() {

        $result = $this->__checkLanguageParameter();
        if($result != Costanti::DEFAULT_FALSE) {

            $code   = Costanti::SUCCESSO;
            $esito  = $result;

        } else {

            $code   = Costanti::LANGUAGE_ERROR;
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
    public function __checkLanguageParameter() {

        return $this->dataRead->__getLanguageFromValueParameter($_GET['language']);

    }


}