<?php
/**
 * Created by PhpStorm.
 * User: Matteo Scirea
 * Date: 09/01/2021
 * Time: 17:46
 */

require_once 'Controllers/Libraries/Definitions/Costanti.php';
require_once 'Controllers/Libraries/Config/Config.php';
require_once 'Controllers/Libraries/Data/DataRead.php';

$responseArray = array();

if(isset($_GET)) {

    if(!empty($_GET)) {

        require_once 'Controllers/Libraries/Check/CheckGetparameters.php';
        $cgp = new \Controllers\Libraries\Check\CheckGetParameters();
        $result = $cgp->__checkRequiredParameters();
        if($result['esito'] == \Controllers\Libraries\Definitions\Costanti::DEFAULT_TRUE) {

            require_once 'Controllers/Libraries/Check/CheckNodeId.php';
            $cni = new \Controllers\Libraries\Check\CheckNodeId();
            $result = $cni->__checkNodeIdValue();
            if($result['esito'] != \Controllers\Libraries\Definitions\Costanti::DEFAULT_FALSE) {

                require_once 'Controllers/Libraries/Check/CheckLanguage.php';
                $cl = new \Controllers\Libraries\Check\CheckLanguage();
                $result = $cl->__checkLanguageValue();
                if($result['esito'] != \Controllers\Libraries\Definitions\Costanti::DEFAULT_FALSE) {

                    require_once 'Controllers/Libraries/Check/CheckPagination.php';
                    $cp = new \Controllers\Libraries\Check\CheckPagination();
                    $result = $cp->__checkPageNumber();
                    if($result['esito'] == \Controllers\Libraries\Definitions\Costanti::DEFAULT_TRUE) {

                        $result = $cp->__checkPageSize();
                        if($result['esito'] == \Controllers\Libraries\Definitions\Costanti::DEFAULT_TRUE) {

                            require_once 'Controllers/Libraries/Data/DataManagement.php';
                            $dm = new \Controllers\Libraries\Data\DataManagement();
                            $result = $dm->__getdataInformation();
                            $resultPagination = $cp->__makePagination($result, $_GET);
                            $resultJson = json_encode($resultPagination);
                            echo $resultJson;

                        } else {

                            //Page_size non valido
                            $responseArray = [
                                'nodes' => \Controllers\Libraries\Definitions\Costanti::DEFAULT_ZERO,
                                'error' => $result['code']
                            ];

                        }

                    } else {

                        //Page_num non valido
                        $responseArray = [
                            'nodes' => \Controllers\Libraries\Definitions\Costanti::DEFAULT_ZERO,
                            'error' => $result['code']
                        ];

                    }

                } else {

                    //Language non valido
                    $responseArray = [
                        'nodes' => \Controllers\Libraries\Definitions\Costanti::DEFAULT_ZERO,
                        'error' => $result['code']
                    ];

                }

            } else {

                //Node Id errato
                $responseArray = [
                    'nodes' => \Controllers\Libraries\Definitions\Costanti::DEFAULT_ZERO,
                    'error' => $result['code']
                ];

            }

        } else {

            //Parametri obbligatori mancanti
            $responseArray = [
                'nodes' => \Controllers\Libraries\Definitions\Costanti::DEFAULT_ZERO,
                'error' => $result['code']
            ];

        }

    } else {

        //La chiamata è avvenuta senza parametri
        $responseArray = [
            'nodes' => \Controllers\Libraries\Definitions\Costanti::DEFAULT_ZERO,
            'error' => \Controllers\Libraries\Definitions\Costanti::HTTP_REQUEST_WITHOUT_PARAMETERS
        ];

    }

} else {

    //La chiamata è avvenuta senza http get
    $responseArray = [
        'nodes' => \Controllers\Libraries\Definitions\Costanti::DEFAULT_ZERO,
        'error' => \Controllers\Libraries\Definitions\Costanti::HTTP_REQUEST_NOT_GET

    ];

}

echo json_encode($responseArray);

?>