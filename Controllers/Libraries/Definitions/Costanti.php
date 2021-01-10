<?php
/**
 * Created by PhpStorm.
 * User: Matteo Scirea
 * Date: 08/01/2021
 * Time: 12:31
 */

namespace Controllers\Libraries\Definitions;


class Costanti {

    //Valori di default
    const RADICE        = 'obrand_srl';
    const DEFAULT_NULL  = null;
    const DEFAULT_TRUE  = true;
    const DEFAULT_FALSE = false;
    const DEFAULT_ZERO  = 0;
    const DEFAULT_UNO   = 1;
    const DEFAULT_DUE   = 2;
    const DEFAULT_TRE   = 3;

    const PAGE_SIZE_MIN = 0;
    const PAGE_SIZE_MAX = 1000;

    const DEFAULT_CENTO = 100;

    const SINGOLO       = 'singolo';
    const MULTIPLO      = 'multiplo';
    const PRINCIPALE    = 'principale';

    //Esiti
    const SUCCESSO  = 0;

    //Server
    const IP_HOST_LOCALE    = '127.0.0.1';
    const LOCALHOST         = 'localhost';

    //Database
    const HOST_LOCALE       = '127.0.0.1';
    const PORTA_LOCALE      = '22';
    const DB_NAME_LOCALE    = 'obrand_db';
    const USER_LOCALE       = 'root';
    const PASSWORD_LOCALE   = '';
    const CHARSET           = 'utf8mb4';

    //Errori
    const NO_ID_NODE_LIST                   = 'Errore recupero lista node_id';
    const HTTP_REQUEST_NOT_GET              = 'Buffer $_GET non presente';
    const HTTP_REQUEST_WITHOUT_PARAMETERS   = 'Parametri obbligatori mancanti';
    const NO_NODE_ID_VALUE_PRESENT          = 'Parametro node_id non presente';
    const NO_LANGUAGE_VALUE_PRESENT         = 'Parametro language non presente';
    const NODE_ID_ERROR                     = 'ID nodo non valido';
    const LANGUAGE_ERROR                    = 'Language non valido';
    const PAGE_NUM_NOT_NUMERIC              = 'Parametro page_num non è un numero';
    const PAGE_NUM_NOT_INTEGER              = 'Parametro page_num non è un intero';
    const PAGE_NUM_NEGATIVE                 = 'Parametro page_num negativo';
    const PAGE_SIZE_NOT_NUMERIC             = 'Parametro page_size non è un numero';
    const PAGE_SIZE_NOT_INTEGER             = 'Parametro page_size non è un intero';
    const PAGE_SIZE_OUT_OF_RANGE            = 'Parametro page_size fuori range';

}