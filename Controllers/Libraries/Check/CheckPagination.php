<?php
/**
 * Created by PhpStorm.
 * User: Matteo Scirea
 * Date: 09/01/2021
 * Time: 18:28
 */

namespace Controllers\Libraries\Check;


use Controllers\Libraries\Definitions\Costanti;

class CheckPagination {

    /**
     * Metodo per la verifica della validità del parametro page_num
     * @return array
     */
    public function __checkPageNumber() {

        if(!empty($_GET['page_num'])) {

            if(is_numeric($_GET['page_num'])) {

                if(is_int((int)$_GET['page_num'])) {

                    if($_GET['page_num'] >= Costanti::DEFAULT_ZERO) {

                        $code   = Costanti::SUCCESSO;
                        $esito  = Costanti::DEFAULT_TRUE;

                    } else {

                        $code   = Costanti::PAGE_NUM_NEGATIVE;
                        $esito  = Costanti::DEFAULT_FALSE;

                    }

                } else {

                    $code   = Costanti::PAGE_NUM_NOT_INTEGER;
                    $esito  = Costanti::DEFAULT_FALSE;

                }

            } else {

                $code   = Costanti::PAGE_NUM_NOT_NUMERIC;
                $esito  = Costanti::DEFAULT_FALSE;

            }

        } else {

            //Impostiamo il valore di default
            $_GET['page_num']   = Costanti::DEFAULT_ZERO;
            $code               = Costanti::SUCCESSO;
            $esito              = Costanti::DEFAULT_TRUE;

        }

        $responseResult = array(
            'code'  => $code,
            'esito' => $esito
        );

        //Liberiamo memoria
        unset($code, $esito);

        return $responseResult;

    }

    /**
     * Metodo per la verifica della validità del parametro page_size
     * @return array
     */
    public function __checkPageSize() {

        if(!empty($_GET['page_size'])) {

            if(is_numeric($_GET['page_size'])) {

                if(is_int((int)$_GET['page_size'])) {

                    if(
                        (int)$_GET['page_size'] >= Costanti::PAGE_SIZE_MIN &&
                        (int)$_GET['page_size'] <= Costanti::PAGE_SIZE_MAX
                    ) {

                        $code   = Costanti::SUCCESSO;
                        $esito  = Costanti::DEFAULT_TRUE;

                    } else {

                        $code   = Costanti::PAGE_SIZE_OUT_OF_RANGE;
                        $esito  = Costanti::DEFAULT_FALSE;

                    }

                } else {

                    $code   = Costanti::PAGE_SIZE_NOT_INTEGER;
                    $esito  = Costanti::DEFAULT_FALSE;

                }

            } else {

                $code   = Costanti::PAGE_SIZE_NOT_NUMERIC;
                $esito  = Costanti::DEFAULT_FALSE;

            }

        } else {

            //Impostiamo il valore di default
            $_GET['page_size']  = Costanti::DEFAULT_CENTO;
            $code               = Costanti::SUCCESSO;
            $esito              = Costanti::DEFAULT_TRUE;

        }

        $responseResult = array(
            'code'  => $code,
            'esito' => $esito
        );

        //Liberiamo memoria
        unset($code, $esito);

        return $responseResult;

    }

    /**
     * @param $__records
     * @param $__parameters
     * @return array
     */
    public function __makePagination($__records, $__parameters) {

        $prev_page = Costanti::DEFAULT_NULL;
        $next_page = Costanti::DEFAULT_NULL;
        $resultRecordPerPage = $__records;

        //Nella richiesta è stata indicata la paginazione
        if($__parameters['page_size'] != Costanti::DEFAULT_CENTO) {

            //Nella richiesta è stata indicata la dimensione della pagina
            if($__parameters['page_num'] > Costanti::DEFAULT_ZERO) {

                $recordIniziale = (($__parameters['page_num'] + Costanti::DEFAULT_UNO) * $__parameters['page_size']);

            } else {

                $recordIniziale = Costanti::DEFAULT_ZERO;

            }
            $resultRecordPerPage = $this->__makeRecordPagination($__records, $__parameters['page_size'], $recordIniziale);
            if($__parameters['page_num'] == Costanti::DEFAULT_ZERO) {

                $next_page = Costanti::DEFAULT_UNO;

            } elseif((($__parameters['page_num'] + Costanti::DEFAULT_UNO) * $__parameters['page_size']) >= count($__records)) {

                $prev_page = $__parameters['page_num'] - Costanti::DEFAULT_UNO;

            } else {

                $prev_page = $__parameters['page_num'] - Costanti::DEFAULT_UNO;
                $next_page = $__parameters['page_num'] + Costanti::DEFAULT_UNO;

            }

        } else {

            if(count($__records) > $__parameters['page_size']) {

                $resultRecordPerPage = $this->__makeRecordPagination($__records, $__parameters['page_size']);
                $next_page = Costanti::DEFAULT_UNO;
                $prev_page = Costanti::DEFAULT_NULL;

            }

        }

        if(
            $next_page != Costanti::DEFAULT_NULL &&
            $prev_page != Costanti::DEFAULT_NULL
        ) {

            $pageResult = [
                "next_page" => $next_page,
                "prev_page" => $prev_page
            ];

        } elseif(
            $next_page == Costanti::DEFAULT_NULL &&
            $prev_page != Costanti::DEFAULT_NULL
        ) {

            $pageResult = [
                "prev_page" => $prev_page
            ];

        } elseif(
            $next_page != Costanti::DEFAULT_NULL &&
            $prev_page == Costanti::DEFAULT_NULL
        ) {

            $pageResult = [
                "next_page" => $next_page
            ];

        } else {

            $pageResult = [];
        }

        $responseResults = [
            "nodes" => $resultRecordPerPage
        ];

        $resultReturn = array_merge($responseResults, $pageResult);

        unset($next_page, $prev_page);

        return $resultReturn;

    }

    /**
     * @param $__records
     * @param $__pageSize
     * @return array
     */
    public function __makeRecordPagination($__records, $__pageSize, $__initRecord = null) {

        if($__initRecord == Costanti::DEFAULT_NULL) {

            return $this->__manageRecordArrayInitial($__records, $__pageSize);

        } else {

            return $this->__manageRecordArrayInitiDefine($__records, $__pageSize, $__initRecord);
        }

    }

    /**
     * @param $__records
     * @param $__pageSize
     * @return array
     */
    public function __manageRecordArrayInitial($__records, $__pageSize) {

        $counter = Costanti::DEFAULT_ZERO;
        $returnArray = array();

        foreach ($__records as $record) {

            if($counter < $__pageSize) {

                array_push($returnArray, $record);
                $counter++;

            } else {

                break;

            }

        }

        return $returnArray;

    }

    /**
     * @param $__records
     * @param $__pageSize
     * @param $__initRecord
     * @return array
     */
    public function __manageRecordArrayInitiDefine($__records, $__pageSize, $__initRecord) {

        $counterStart = Costanti::DEFAULT_ZERO;
        $counter = Costanti::DEFAULT_ZERO;
        $returnArray = array();

        foreach ($__records as $record) {

            if($counterStart == $__initRecord) {

                if($counter < $__pageSize) {

                    array_push($returnArray, $record);
                    $counter++;

                } else {

                    break;

                }

            } else {

                $counterStart++;

            }

        }

        return $returnArray;

    }

}