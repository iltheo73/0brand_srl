<?php
/**
 * Created by PhpStorm.
 * User: Matteo Scirea
 * Date: 08/01/2021
 * Time: 12:47
 */

namespace Controllers\Libraries\Data;


use Controllers\Libraries\Config\Config;
use Controllers\Libraries\Definitions\Costanti;

class DataRead{

    protected $conn;

    /**
     * DataRead constructor.
     */
    public function __construct(){

        $gc = new Config();
        list($connString, $username, $password, $options) = $gc->__setParametriConnessione();
        $this->conn = $gc->__connessioneDatabase($connString, $username, $password, $options);

        //Liberiamo memoria
        unset($gc, $_host, $_dbName, $_username, $_password);

    }

    /**
     * Metodo per recuperare tutti gli idNode dalla tabella node_tree
     * @return mixed
     */
    public function __getNodeIdFromNodeTree(){

        $stmt = $this->conn->query("SELECT * FROM node_tree");
        return $stmt->fetchAll();

    }

    /**
     * Metodo per recuperare tutti i linguaggi presenti nella tabella node_tree_names
     * @return array
     */
    public function __getLanguage() {

        $stmt = $this->conn->query("SELECT DISTINCT language FROM node_tree_names");
        return $stmt->fetchAll();
    }

    /**
     * Metodo per verificare se il node_id passato come parametro è valido
     * @param $__idNode
     * @return mixed
     */
    public function __getNodeIdFromId($__idNode) {

        $stm = $this->conn->prepare("SELECT * FROM node_tree WHERE idNode = :id");
        $stm->bindParam(":id", $__idNode, \PDO::PARAM_INT);
        $stm->execute();

        return $stm->fetch(\PDO::FETCH_ASSOC);

    }

    /**
     * Metodo per verificare se il valore del parametro language è valido
     * @param $__language
     * @return mixed
     */
    public function __getLanguageFromValueParameter($__language) {

        $stm = $this->conn->prepare("SELECT DISTINCT * FROM node_tree_names WHERE language = :language");
        $stm->bindParam(":language", $__language, \PDO::PARAM_STR);
        $stm->execute();

        return $stm->fetch(\PDO::FETCH_ASSOC);

    }

    /**
     * Metodo per recuperare tutti i figli partendo dal nodo richiesto
     * @param $__idNode
     * @param $__howManyChild
     * @return array
     */
    public function __getChildNodeTree($__idNode, $__howManyChild) {

        $query = "
            SELECT DISTINCT Child.idNode, Child.`level`, Child.iLeft, Child.iRight
            FROM node_tree as Child, node_tree as Parent 
            WHERE Parent.iLeft <= Child.iLeft AND Parent.iRight >= Child.iRight 
        ";

        if($__howManyChild == Costanti::SINGOLO) {

            $query .= " AND Child.idNode = :idNode";
        }

        if($__howManyChild == Costanti::MULTIPLO) {

            $query .= " AND Child.idNode >= :idNode";

        }

        $query .= " GROUP BY Child.idNode, Child.level,  Child.iLeft, Child.iRight";
        $query .= " ORDER BY Child.level ASC";
        $stm = $this->conn->prepare($query);
        $stm->bindParam(":idNode", $__idNode, \PDO::PARAM_INT);
        $stm->execute();

        return $stm->fetchAll(\PDO::FETCH_ASSOC);

    }

    /**
     * Metodo per ordinamento array in base ad un determinato campo
     * @param $data
     * @param $field
     * @return mixed
     */
    public function orderBy($data, $field){

        $code = "return strnatcmp(\$a['$field'], \$b['$field']);";
        usort($data, create_function('$a,$b', $code));
        return $data;

    }

    /**
     * @param $__nodeTreeRecord
     * @param $__paramRequest
     * @return array|null
     */
    public function __getNodeTreeNamesChild($__nodeTreeRecord, $__paramRequest) {

        $resultArray = array();

        if(is_array($__nodeTreeRecord)) {

            $__nodeTreeRecord = $this->orderBy($__nodeTreeRecord, 'iLeft');
            $pageRequest = $__paramRequest['page_num'];
            $sizeRequest = (int)$__paramRequest['page_size'];

            $maxLevel = $this->__getMaxLevel();

            foreach ($__nodeTreeRecord as $item) {

                //$query = "SELECT * FROM node_tree_names WHERE idNode >= :idLeft AND idNode <= :idRight";
                $query = "SELECT * FROM node_tree_names WHERE idNode = :idNode";

                if(!empty($__paramRequest['search_keyword'])) {

                    $query .= " AND NodeName LIKE :keyword";

                }

                $query .= " LIMIT :page_size OFFSET :page_num";

                $stm = $this->conn->prepare($query);
                //$stm->bindParam(":idLeft", $item['iLeft'], \PDO::PARAM_INT);
                //$stm->bindParam(":idRight", $item['iRight'], \PDO::PARAM_INT);
                $stm->bindParam(":idNode", $item['idNode'], \PDO::PARAM_INT);
                if(!empty($__paramRequest['search_keyword'])) {

                    $stm->bindParam(":keyword", $__paramRequest['search_keyword'], \PDO::PARAM_STR);

                }

                $stm->bindParam(":page_size", $sizeRequest, \PDO::PARAM_INT);
                $stm->bindParam(":page_num", $pageRequest, \PDO::PARAM_INT);
                $stm->execute();

                $esito = $stm->fetch(\PDO::FETCH_ASSOC);

                if($esito != Costanti::DEFAULT_FALSE) {

                    $childNode = Costanti::DEFAULT_NULL;

                    if($item['level'] == Costanti::DEFAULT_UNO) {

                        $childNode = $maxLevel - Costanti::DEFAULT_UNO;

                    }

                    if($item['level'] == Costanti::DEFAULT_DUE) {

                        if(($item['iRight'] - $item['iLeft']) == Costanti::DEFAULT_UNO) {

                            $childNode = Costanti::DEFAULT_ZERO;

                        }

                        if(($item['iRight'] - $item['iLeft']) > Costanti::DEFAULT_UNO) {

                            $childNode = Costanti::DEFAULT_UNO;

                        }

                    }

                    if($item['level'] == Costanti::DEFAULT_TRE) {

                        $childNode = Costanti::DEFAULT_ZERO;

                    }

                    $infoNodo = [
                        "node_id"           => $item['idNode'],
                        "name"              => $esito["NodeName"],
                        "children_count"    => (int)$childNode
                    ];

                    array_push($resultArray, $infoNodo);
                    unset($infoNodo, $childNode);

                }

            }

        }

        return $resultArray;

    }

    /**
     * @return array
     */
    public function __getMaxLevel() {

        $stmt = $this->conn->query("SELECT MAX(level) as level FROM node_tree");
        $result = $stmt->fetchAll();
        return $result[0]['level'];
    }

}