<?php
/**
 * Created by PhpStorm.
 * User: Matteo Scirea
 * Date: 08/01/2021
 * Time: 12:50
 */

namespace Controllers\Libraries\Config;


use Controllers\Libraries\Definitions\Costanti;

class Config {

    /**
     * Metodo per il settaggio dei parametri di connessione al database
     * @return array
     */
    public function __setParametriConnessione() {

        $options    = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $charset = Costanti::CHARSET;

        $host   = Costanti::HOST_LOCALE;
        $dbName = Costanti::DB_NAME_LOCALE;
        $port   = Costanti::PORTA_LOCALE;
        $connString = "mysql:host=$host;dbname=$dbName;charset=$charset";

        //Liberiamo memoria
        unset($host, $dbName, $charset, $port);

        return array($connString, Costanti::USER_LOCALE, Costanti::PASSWORD_LOCALE, $options);

    }

    /**
     * Metodo per la connessione al database
     * @param $_connString
     * @param $_username
     * @param $_password
     * @param $_options
     * @return \PDO
     */
    public function __connessioneDatabase($_connString, $_username, $_password, $_options) {

        try {

            $pdo = new \PDO($_connString, $_username, $_password, $_options);
            return $pdo;

        }
        catch(\PDOException $e) {

            throw new \PDOException($e->getMessage(), (int)$e->getCode());

        }

    }

}