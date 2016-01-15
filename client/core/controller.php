<?php
class Controller extends Config{

    protected $dbh;
    protected $config;
    protected $ajax=false;

    public function start(){


        if($module=$_GET['module']){
            switch($module){
                case "json":
                    $this->jsonController();
                    break;

            }
        }

    }

    private function jsonController(){
        include("json.php");
        header('Content-type: application/json');
        $json=new Json();

        switch($_GET['action']){

            default;
            case "getSummary";
                $json->getSummary();
                break;

            case "getUsers";
                $json->getUsers();
                break;

            case "getUserDetails";
                $json->getUserDetails();
                break;

            case "getUserRequests";
                $json->getUserRequests();
                break;

            case "getRequests";
                $json->getRequests();
                break;

            case "getRequestDetails";
                $json->getRequestDetails();
                break;

            case "getRequestErrors";
                $json->getRequestErrors();
                break;

            case "getErrors";
                $json->getErrors();
                break;

            case "getErrorStats";
                $json->getErrorStats();
                break;

            case "getErrorGroup";
                $json->getErrorGroup();
                break;

        }

    }


    public function insertHascheckRequest($argv){
        // convert string to json object
        $json=json_decode($argv[1]);

        $datetime=date ("Y-m-d H:i:s", mktime());

        //check if user exists
        $userExists=$this->dbh->prepare("SELECT userID FROM user WHERE userID=:userID");
        $userExists->bindParam("userID",$json->userID);
        $userExists->execute();
        if($userExists->fetch()){ // if user exists - update last ip
            $upd=$this->dbh->prepare("UPDATE user SET lastIP=:lastIP");
            $upd->bindParam("lastIP", $json->ip);
            $upd->execute();
        }else{ // otherwise, insert new user
            $ins=$this->dbh->prepare("INSERT INTO user (userID, timeAppeared, lastIP) VALUES (:userID, :timeAppeared, :lastIP)");
            $ins->bindParam("userID", $json->userID);
            $ins->bindParam("timeAppeared", $datetime);
            $ins->bindParam("lastIP", $json->ip);
            $ins->execute();
        }

        // update user_ip table
        $userIP=$this->dbh->prepare("SELECT userID FROM user_ip WHERE userID=:userID AND IP=:IP");
        $userIP->bindParam("userID",$json->userID);
        $userIP->bindParam("IP",$json->ip);
        $userIP->execute();

        if($userIP->fetch()){ // is user and ip already exists in table, update ip time
            $upd=$this->dbh->prepare("UPDATE user_ip SET time=:time WHERE userID=:userID AND IP=:IP");
            $upd->bindParam("time", $datetime);
            $upd->bindParam("userID", $json->userID);
            $upd->bindParam("IP", $json->ip);
            $upd->execute();

        }else{ // otherwise, insert new address
            $ins=$this->dbh->prepare("INSERT INTO user_ip (userID, IP, time) VALUES (:userID, :IP, :time)");
            $ins->bindParam("userID", $json->userID);
            $ins->bindParam("time", $datetime);
            $ins->bindParam("IP", $json->ip);
            $ins->execute();
        }

        // insert request into db
        $ins=$this->dbh->prepare("INSERT INTO request (reqTextLength, userID, timeRequested, timeProcessed) VALUES (:wordCounter, :userID, :timeRequested, :timeProcessed)");
        $ins->bindParam(":wordCounter",$json->wordCounter);
        $ins->bindParam(":userID",$json->userID);
        $ins->bindParam(":timeRequested",$json->requestTime);
        $ins->bindParam(":timeProcessed",$json->timeProcessed);
        $ins->execute();
        $reqID=$this->dbh->lastInsertId();

        // insert error into db
        foreach($json->report->errors as $error){
            // insert new error
            $ins=$this->dbh->prepare("INSERT INTO error (errorTypeID, errorPhrase, numOccur) VALUES (:errorTypeID, :errorPhrase, :numOccur)");
            $ins->bindParam(":errorTypeID",$error->severity);
            $ins->bindParam(":errorPhrase",$error->suspicious);
            $ins->bindParam(":numOccur",$error->occurrences);
            $ins->execute();
            $errorID=$this->dbh->lastInsertId();

            if($reqID && $errorID){
                // connect error with request
                $ins=$this->dbh->prepare("INSERT INTO request_error (errorID, reqID) VALUES (:errorID, :reqID)");
                $ins->bindParam(":errorID",$errorID);
                $ins->bindParam(":reqID",$reqID);
                $ins->execute();
            }

        }

    }
}