<?php

class Json extends Config
{
    protected $dbh;
    protected $config;
    protected $ajax = false;

    public function getSummary()
    {
        $countUsers = $this->dbh->prepare('SELECT COUNT(userID) AS users FROM user');
        $countUsers->execute();
        $countUsers = $countUsers->fetch();
        $result['count']['users'] = (int) $countUsers['users'];

        $countRequests = $this->dbh->prepare('SELECT COUNT(reqID) AS requests FROM request');
        $countRequests->execute();
        $countRequests = $countRequests->fetch();
        $result['count']['requests'] = (int) $countRequests['requests'];

        $countErrors = $this->dbh->prepare('SELECT SUM(numOccur) AS errors FROM error');
        $countErrors->execute();
        $countErrors = $countErrors->fetch();
        $result['count']['errors'] = (int) $countErrors['errors'];

        $countErrors = $this->dbh->prepare('SELECT COUNT(DISTINCT errorPhrase) AS errors_distinct FROM error');
        $countErrors->execute();
        $countErrors = $countErrors->fetch();
        $result['count']['errors_distinct'] = (int) $countErrors['errors_distinct'];

        echo json_encode($result);
    }

    public function getUsers()
    {
        $result = array();

        // total records
        $countAll = $this->dbh->prepare('SELECT COUNT(userID) as recordsTotal FROM user');
        $countAll->execute();
        $countAll = $countAll->fetch();
        $result['recordsTotal'] = (int) $countAll['recordsTotal'];

        $columnArray = array('u.userID', 'u.timeAppeared', 'num_requests', 'u.lastIP');

        // order part
        $order = $_GET['order'];
        $order = $order[0];
        $orderColumn = $columnArray[$order['column']];
        $order['dir'] ? $orderDir = $order['dir'] : $orderDir = 'ASC';
        $orderSQL = "ORDER BY $orderColumn $orderDir";

        // where part
        $where = 'WHERE 1=1';
        $columns = $_GET['columns'];
        if ($columns) {
            $i = 0;
            foreach ($columns as $item) {
                if ($value = $item['search']['value']) {
                    if ($i == 2) {
                        $havingSQL = " HAVING COUNT(r.reqID)=:p$i";
                    } else {
                        $where .= ' AND '.$columnArray[$i]." LIKE :p$i";
                    }
                }
                ++$i;
            }
        }

        // count without pagination
        $addPagination = '';

        // add data
        $data = $this->dbh->prepare("SELECT u.userID AS id, UNIX_TIMESTAMP(u.timeAppeared) AS first_appear, u.lastIP AS last_ip, COUNT(r.reqID) as num_requests FROM user u  JOIN request r ON r.userID=u.userID $where GROUP BY u.userID $havingSQL $addPagination");
        if ($columns) {
            $i = 0;
            foreach ($columns as $item) {
                if ($value = $item['search']['value']) {
                    if ($i == 2) {
                        $value = $value;
                    } else {
                        $value = '%'.$value.'%';
                    }
                    $data->bindValue("p$i", $value);
                }
                ++$i;
            }
        }
        $data->execute();
        $result['recordsFiltered'] = $data->rowCount();

        // filtered records
        $start = $_GET['start'];
        $length = $_GET['length'];
        (is_numeric($start) && is_numeric($length)) ? $addPagination = "LIMIT $start,$length" : $addPagination = '';

        // add data
        $data = $this->dbh->prepare("SELECT u.userID AS id, UNIX_TIMESTAMP(u.timeAppeared) AS first_appear, u.lastIP AS last_ip, COUNT(r.reqID) as num_requests FROM user u  JOIN request r ON r.userID=u.userID $where GROUP BY u.userID $havingSQL $orderSQL $addPagination");
        if ($columns) {
            $i = 0;
            foreach ($columns as $item) {
                if ($value = $item['search']['value']) {
                    if ($i == 2) {
                        $value = $value;
                    } else {
                        $value = '%'.$value.'%';
                    }
                    $data->bindValue("p$i", $value);
                }
                ++$i;
            }
        }
        $data->execute();
        $data = $data->fetchAll();
        $result['data'] = array();
        foreach ($data as $item) {
            $result['data'][] = array(
                'id' => $item['id'],
                'first_appear' => date('Y-m-d', $item['first_appear']),
                'num_requests' => (int) $item['num_requests'],
                'last_ip' => $item['last_ip'],
            );
        }

        echo json_encode($result);
    }

    public function getUserRequests()
    {
        $id = $_GET['id'];

        if ($id) {
            $result = array();

            // total records
            $countAll = $this->dbh->prepare('SELECT COUNT(reqID) as recordsTotal FROM request WHERE userID=:userID');
            $countAll->bindParam('userID', $id);
            $countAll->execute();
            $countAll = $countAll->fetch();
            $result['recordsTotal'] = (int) $countAll['recordsTotal'];

            $columnArray = array('r.timeRequested', 'ROUND(r.timeProcessed,2)', 'numErrors');

            // order part
            $order = $_GET['order'];
            $order = $order[0];
            $orderColumn = $columnArray[$order['column']];
            $order['dir'] ? $orderDir = $order['dir'] : $orderDir = 'ASC';
            $orderSQL = "ORDER BY $orderColumn $orderDir";

            // where part
            $where = '';
            $columns = $_GET['columns'];
            if ($columns) {
                $i = 0;
                foreach ($columns as $item) {
                    if ($value = $item['search']['value']) {
                        if ($i == 2) {
                            $havingSQL = " HAVING COUNT(re.errorID)=:p$i";
                        } else {
                            $where .= ' AND CAST('.$columnArray[$i]." AS CHAR) LIKE :p$i";
                        }
                    }
                    ++$i;
                }
            }

            // count without pagination
            $data = $this->dbh->prepare("SELECT r.reqID AS id, r.timeRequested AS time,  ROUND(r.timeProcessed,2) AS processing, COUNT(re.errorID) AS numErrors
                                      FROM request r
                                      LEFT JOIN request_error re ON r.reqID=re.reqID
                                      WHERE r.userID=:userID
                                      $where
                                      GROUP BY r.reqID
                                      $havingSQL
                                    ");
            $data->bindParam('userID', $id);
            if ($columns) {
                $i = 0;
                foreach ($columns as $item) {
                    if ($value = $item['search']['value']) {
                        if ($i == 2) {
                            $value = $value;
                        } else {
                            $value = '%'.$value.'%';
                        }
                        $data->bindValue("p$i", $value);
                    }
                    ++$i;
                }
            }
            $data->execute();
            $result['recordsFiltered'] = $data->rowCount();

            // filtered records
            $start = $_GET['start'];
            $length = $_GET['length'];
            (is_numeric($start) && is_numeric($length)) ? $addPagination = "LIMIT $start,$length" : $addPagination = '';

            // add requests data
            $data = $this->dbh->prepare("SELECT r.reqID AS id, timeRequested AS time,ROUND(timeProcessed,2) AS processing, COUNT(re.errorID) as numErrors
                                        FROM request r
                                        LEFT JOIN request_error re ON re.reqID=r.reqID
                                        WHERE r.userID=:userID
                                        $where
                                        GROUP BY r.reqID
                                        $havingSQL
                                        $orderSQL
                                        $addPagination
                                        ");
            $data->bindParam('userID', $id);
            if ($columns) {
                $i = 0;
                foreach ($columns as $item) {
                    if ($value = $item['search']['value']) {
                        if ($i == 2) {
                            $value = $value;
                        } else {
                            $value = '%'.$value.'%';
                        }
                        $data->bindValue("p$i", $value);
                    }
                    ++$i;
                }
            }
            $data->execute();
            $data = $data->fetchAll();
            $result['data'] = array();
            foreach ($data as $item) {
                $result['data'][] = array(
                    'id' => $item['id'],
                    'time' => $item['time'],
                    'processing' => $item['processing'],
                    'numErrors' => (int) $item['numErrors'],
                );
            }

            echo json_encode($result);
        }
    }

    public function getUserDetails()
    {
        $id = $_GET['id'];

        if ($id) {

            // user info
            $user = $this->dbh->prepare('SELECT userID, UNIX_TIMESTAMP(timeAppeared) AS first_appear, lastIP FROM user WHERE userID=:userID');
            $user->bindParam('userID', $id);
            $user->execute();
            $user = $user->fetch();
            if ($user) {
                $result['id'] = $id;
                $result['first_appear'] = date('Y-m-d', $user['first_appear']);

                // add ip history
                $IPHistory = $this->dbh->prepare('SELECT IP FROM user_ip WHERE userID=:userID');
                $IPHistory->bindParam('userID', $id);
                $IPHistory->execute();
                $IPHistory = $IPHistory->fetchAll();
                foreach ($IPHistory as $item) {
                    $result['ip_history'][] = $item['IP'];
                }

                // add error stats
                $errorStats = $this->dbh->prepare('SELECT et.errorTypeID AS label, COUNT(e.errorID) AS value FROM error_type et
                                                  JOIN error e ON e.errorTypeID=et.errorTypeID
                                                  JOIN request_error re ON e.errorID=re.errorID
                                                  JOIN request r ON r.reqID=re.reqID
                                                  WHERE r.userID=:userID GROUP BY et.errorTypeID ORDER BY et.errorTypeID ASC');
                $errorStats->bindParam('userID', $id);
                $errorStats->execute();
                $errorStats = $errorStats->fetchAll();
                $result['error_stats'] = array();
                foreach ($errorStats as $item) {
                    $result['error_stats'][] = array('label' => $item['label'], 'value' => (int) $item['value']);
                }

                // add monthly stats (last 12 months)
                /* AND timeRequested >= NOW() - INTERVAL 12 MONTH */
                $monthlyStats = $this->dbh->prepare("SELECT CONCAT(YEAR(timeRequested),'-',MONTH(timeRequested)) AS month, COUNT(reqID) as requests
                                                    FROM request
                                                    WHERE userID=:userID
                                                    GROUP BY YEAR(timeRequested), MONTH(timeRequested)
                                                    ORDER BY timeRequested DESC LIMIT 0,12");
                $monthlyStats->bindParam('userID', $id);
                $monthlyStats->execute();
                $monthlyStats = $monthlyStats->fetchAll();
                $result['usage_stats']['monthly'] = array();
                foreach ($monthlyStats as $item) {
                    $result['usage_stats']['monthly'][] = array('month' => $item['month'], 'requests' => (int) $item['requests']);
                }

                // add request stats
                $numRequests = $this->dbh->prepare('SELECT COUNT(reqID) as num_requests FROM request WHERE userID=:userID');
                $numRequests->bindParam('userID', $id);
                $numRequests->execute();
                $numRequests = $numRequests->fetch();
                $result['request_stats']['num_requests'] = (int) $numRequests['num_requests'];

                // add error percentage
                $avgWordCount = $this->dbh->prepare('SELECT ROUND(AVG(reqTextLength),2) as avg_word_count FROM request WHERE userID=:userID');
                $avgWordCount->bindParam('userID', $id);
                $avgWordCount->execute();
                $avgWordCount = $avgWordCount->fetch();
                $result['request_stats']['error_percentage']['avg_word_count'] = (float) $avgWordCount['avg_word_count'];

                $numErrors = $this->dbh->prepare('SELECT SUM(e.numOccur) as num_error FROM error e
                                                JOIN request_error re ON e.errorID=re.errorID
                                                JOIN request r ON re.reqID=r.reqID
                                                WHERE r.userID=:userID');
                $numErrors->bindParam('userID', $id);
                $numErrors->execute();
                $numErrors = $numErrors->fetch();

                $result['request_stats']['error_percentage']['avg_error_count'] = round((int) $numErrors['num_error'] / (int) $numRequests['num_requests'], 2);

                echo json_encode($result);
            }
        }
    }

    public function getRequests()
    {
        $result = array();

        // total records
        $countAll = $this->dbh->prepare('SELECT COUNT(reqID) as recordsTotal FROM request');
        $countAll->execute();
        $countAll = $countAll->fetch();
        $result['recordsTotal'] = (int) $countAll['recordsTotal'];

        $columnArray = array('r.timeRequested', 'ROUND(r.timeProcessed,2)', 'numErrors');

        // order part
        $order = $_GET['order'];
        $order = $order[0];
        $orderColumn = $columnArray[$order['column']];
        $order['dir'] ? $orderDir = $order['dir'] : $orderDir = 'ASC';
        $orderSQL = "ORDER BY $orderColumn $orderDir";

        // where part
        $where = 'WHERE 1=1';
        $columns = $_GET['columns'];
        if ($columns) {
            $i = 0;
            foreach ($columns as $item) {
                if ($value = $item['search']['value']) {
                    if ($i == 2) {
                        $havingSQL = " HAVING COUNT(re.errorID)=:p$i";
                    } else {
                        $where .= ' AND CAST('.$columnArray[$i]." AS CHAR) LIKE :p$i";
                    }
                }
                ++$i;
            }
        }

        // count without pagination
        $addPagination = '';
        // add data
        $data = $this->dbh->prepare("SELECT r.reqID AS id, r.timeRequested AS time,  ROUND(r.timeProcessed,2) AS processing, COUNT(re.errorID) AS numErrors
                                      FROM request r
                                      LEFT JOIN request_error re ON r.reqID=re.reqID
                                      $where
                                      GROUP BY r.reqID
                                      $havingSQL
                                      $addPagination
                                    ");
        if ($columns) {
            $i = 0;
            foreach ($columns as $item) {
                if ($value = $item['search']['value']) {
                    if ($i == 2) {
                        $value = $value;
                    } else {
                        $value = '%'.$value.'%';
                    }
                    $data->bindValue("p$i", $value);
                }
                ++$i;
            }
        }
        $data->execute();
        $result['recordsFiltered'] = $data->rowCount();

        // filtered records
        $start = $_GET['start'];
        $length = $_GET['length'];
        (is_numeric($start) && is_numeric($length)) ? $addPagination = "LIMIT $start,$length" : $addPagination = '';

        // add data
        $data = $this->dbh->prepare("SELECT r.reqID AS id, r.timeRequested AS time,  ROUND(r.timeProcessed,2) AS processing, COUNT(re.errorID) AS numErrors
                                      FROM request r
                                      LEFT JOIN request_error re ON r.reqID=re.reqID
                                      $where
                                      GROUP BY r.reqID
                                      $havingSQL
                                      $orderSQL
                                      $addPagination
                                    ");
        if ($columns) {
            $i = 0;
            foreach ($columns as $item) {
                if ($value = $item['search']['value']) {
                    if ($i == 2) {
                        $value = $value;
                    } else {
                        $value = '%'.$value.'%';
                    }
                    $data->bindValue("p$i", $value);
                }
                ++$i;
            }
        }
        $data->execute();
        $data = $data->fetchAll();
        $result['data'] = array();
        foreach ($data as $item) {
            $result['data'][] = array(
                'id' => $item['id'],
                'time' => $item['time'].'',
                'processing' => $item['processing'],
                'numErrors' => $item['numErrors'],
            );
        }

        echo json_encode($result);
    }

    public function getRequestDetails()
    {
        $id = $_GET['id'];

        if ($id) {

            // request info
            $request = $this->dbh->prepare('SELECT r.userID AS user, r.timeRequested AS time, ROUND(r.timeProcessed,2) AS processing_time, r.reqTextLength AS word_count, COUNT(re.errorID) AS num_errors
                                            FROM request r
                                            JOIN request_error re ON r.reqID=re.reqID
                                            WHERE r.reqID=:reqID
                                            ');
            $request->bindParam('reqID', $id);
            $request->execute();
            $request = $request->fetch();
            if ($request) {
                $result = array(
                    'id' => $id,
                    'user' => $request['user'],
                    'time' => $request['time'],
                    'processing_time' => $request['processing_time'],
                    'word_count' => $request['word_count'],
                    'num_errors' => $request['num_errors'],
                );
                echo json_encode($result);
            }
        }
    }

    public function getRequestErrors()
    {
        $id = $_GET['id'];

        if ($id) {

            // total records
            $countAll = $this->dbh->prepare('SELECT COUNT(e.errorID) as recordsTotal
                                              FROM request r
                                              JOIN request_error re ON r.reqID=re.reqID
                                              JOIN error e ON re.errorID=e.errorID
                                              WHERE r.reqID=:reqID');
            $countAll->bindParam('reqID', $id);
            $countAll->execute();
            $countAll = $countAll->fetch();
            $result['recordsTotal'] = (int) $countAll['recordsTotal'];

            $columnArray = array('errorPhrase', 'errorTypeID', 'numOccur');

            // where part
            $where = '';
            $columns = $_GET['columns'];
            if ($columns) {
                $i = 0;
                foreach ($columns as $item) {
                    if ($value = $item['search']['value']) {
                        $where .= ' AND CAST('.$columnArray[$i]." AS CHAR) LIKE :p$i";
                    }
                    ++$i;
                }
            }

            // order part
            $order = $_GET['order'];
            $order = $order[0];
            $orderColumn = $columnArray[$order['column']];
            $order['dir'] ? $orderDir = $order['dir'] : $orderDir = 'ASC';
            $orderSQL = "ORDER BY $orderColumn $orderDir";

            // count without pagination
            $addPagination = '';

            // add error data
            $data = $this->dbh->prepare("SELECT *
                                         FROM request r
                                         JOIN request_error re ON r.reqID=re.reqID
                                         JOIN error e ON re.errorID=e.errorID
                                         WHERE r.reqID=:reqID
                                         $where
                                         $addPagination
                                        ");
            $data->bindParam('reqID', $id);
            if ($columns) {
                $i = 0;
                foreach ($columns as $item) {
                    if ($value = $item['search']['value']) {
                        if ($i == 2) {
                            $value = $value;
                        } else {
                            $value = '%'.$value.'%';
                        }
                        $data->bindValue("p$i", $value);
                    }
                    ++$i;
                }
            }
            $data->execute();
            $result['recordsFiltered'] = $data->rowCount();

            // filtered records
            $start = $_GET['start'];
            $length = $_GET['length'];
            (is_numeric($start) && is_numeric($length)) ? $addPagination = "LIMIT $start,$length" : $addPagination = '';

            // add error data
            $data = $this->dbh->prepare("SELECT *
                                         FROM request r
                                         JOIN request_error re ON r.reqID=re.reqID
                                         JOIN error e ON re.errorID=e.errorID
                                         WHERE r.reqID=:reqID
                                         $where
                                         $orderSQL
                                         $addPagination
                                        ");
            $data->bindParam('reqID', $id);
            if ($columns) {
                $i = 0;
                foreach ($columns as $item) {
                    if ($value = $item['search']['value']) {
                        if ($i == 2) {
                            $value = $value;
                        } else {
                            $value = '%'.$value.'%';
                        }
                        $data->bindValue("p$i", $value);
                    }
                    ++$i;
                }
            }
            $data->execute();
            $data = $data->fetchAll();
            $result['data'] = array();
            foreach ($data as $item) {
                $result['data'][] = array(
                    'id' => $item['errorID'],
                    'suspicious' => $item['errorPhrase'],
                    'type' => $item['errorTypeID'],
                    'numOccur' => (int) $item['numOccur'],
                );
            }

            echo json_encode($result);
        }
    }

    public function getErrors()
    {

        // total records
        $countAll = $this->dbh->prepare('SELECT COUNT(DISTINCT errorPhrase) AS recordsTotal FROM error');
        $countAll->execute();
        $countAll = $countAll->fetch();
        $result['recordsTotal'] = (int) $countAll['recordsTotal'];

        $columnArray=array("e.errorPhrase","e.errorTypeID","SUM(e.numOccur)");

        // where part
        $where = 'WHERE 1=1';
        $columns = $_GET['columns'];
        if ($columns) {
            $i = 0;
            foreach ($columns as $item) {
                if ($value = $item['search']['value']) {
                    if ($i == 2) {
                        $havingSQL = " HAVING SUM(e.numOccur)=:p$i";
                    } else {
                        $where .= ' AND CAST('.$columnArray[$i]." AS CHAR) LIKE :p$i";
                    }
                }
                ++$i;
            }
        }

        // order part
        $order = $_GET['order'];
        $order = $order[0];
        $orderColumn = $columnArray[$order['column']];
        $order['dir'] ? $orderDir = $order['dir'] : $orderDir = 'ASC';
        $orderSQL = "ORDER BY $orderColumn $orderDir";

        // filtered records
        $start = $_GET['start'];
        $length = $_GET['length'];
        (is_numeric($start) && is_numeric($length)) ? $addPagination = "LIMIT $start,$length" : $addPagination = '';
        $countFiltered = $this->dbh->prepare("SELECT e.errorPhrase AS suspicious
                                    FROM error e
                                    $where
                                    GROUP BY e.errorPhrase, e.errorTypeID
                                    $havingSQL");
        if ($columns) {
            $i = 0;
            foreach ($columns as $item) {
                if ($value = $item['search']['value']) {
                    if ($i == 2) {
                        $value = $value;
                    } else {
                        $value = '%'.$value.'%';
                    }
                    $countFiltered->bindValue("p$i", $value);
                }
                ++$i;
            }
        }
        $countFiltered->execute();
        $result['recordsFiltered'] = $countFiltered->rowCount();
        // pagination
        $start = $_GET['start'];
        $length = $_GET['length'];
        (is_numeric($start) && is_numeric($length)) ? $addPagination = "LIMIT $start,$length" : $addPagination = '';

        // add error data
        $data = $this->dbh->prepare("SELECT e.errorPhrase AS suspicious, e.errorTypeID AS type, SUM(e.numOccur) AS totalNumOccur
                                    FROM error e
                                    $where
                                    GROUP BY e.errorPhrase, e.errorTypeID
                                    $havingSQL
                                    $orderSQL
                                    $addPagination
                                    ");
        if ($columns) {
            $i = 0;
            foreach ($columns as $item) {
                if ($value = $item['search']['value']) {
                    if ($i == 2) {
                        $value = $value;
                    } else {
                        $value = '%'.$value.'%';
                    }
                    $data->bindValue("p$i", $value);
                }
                ++$i;
            }
        }
        $data->execute();
        $data = $data->fetchAll();
        $result['data'] = array();
        foreach ($data as $item) {
            $result['data'][] = array(
                'suspicious' => $item['suspicious'],
                'type' => $item['type'],
                'totalNumOccur' => $item['totalNumOccur'],
            );
        }

        echo json_encode($result);
    }

    public function getErrorStats()
    {
        // error types
        $errorTypes = $this->dbh->prepare('SELECT e.errorTypeID AS label, SUM(numOccur) AS value
                                            FROM error e
                                            GROUP BY e.errorTypeID
                                            ORDER BY e.errorTypeID ASC
                                            ');
        $errorTypes->execute();
        $errorTypes = $errorTypes->fetchAll();
        foreach ($errorTypes as $item) {
            $result['error_types'][] = array(
                'label' => $item['label'],
                'value' => (int) $item['value'],
            );
        }

        // error count
        $errors = $this->dbh->prepare('SELECT COUNT(DISTINCT errorPhrase) AS num_errors_distinct, SUM(numOccur) num_errors FROM error');
        $errors->execute();
        $errors = $errors->fetch();
        $result['error_count']['num_errors'] = (int) $errors['num_errors'];
        $result['error_count']['num_errors_distinct'] = (int) $errors['num_errors_distinct'];

        // average word count
        $averageWordCount = $this->dbh->prepare('SELECT ROUND(AVG(reqTextLength),2) AS avg_word_count FROM request');
        $averageWordCount->execute();
        $averageWordCount = $averageWordCount->fetch();
        $result['error_count']['error_percentage']['avg_word_count'] = (float) $averageWordCount['avg_word_count'];

        // total num of requests
        $totalRequests = $this->dbh->prepare('SELECT COUNT(reqID) AS totalRequests FROM request');
        $totalRequests->execute();
        $totalRequests = $totalRequests->fetch();
        $result['error_count']['error_percentage']['avg_error_count'] = round((int) $errors['num_errors'] / (int) $totalRequests['totalRequests'], 2);

        // Ovdje jednostavno ispi�emo 5 errora koji se najvi�e pojavljuju. Grupirati tablicu ERRORS po errorPhrase i onda napraviti SUM stupca numOccur. Sortirati po toj sumi i uzeti 5 najve�ih.
        // average
        $mostFrequent = $this->dbh->prepare('SELECT errorPhrase, SUM(numOccur) AS most_frequent
                                              FROM error
                                              GROUP BY errorPhrase
                                              ORDER BY most_frequent DESC LIMIT 0,5
                                              ');
        $mostFrequent->execute();
        $mostFrequent = $mostFrequent->fetchAll();
        foreach ($mostFrequent as $item) {
            $result['most_frequent'][] = $item['errorPhrase'];
        }

        echo json_encode($result);
    }

    public function getErrorGroup()
    {
        $group = $_GET['group'];

        $data = $this->dbh->prepare('SELECT e.errorPhrase AS suspicious, e.errorTypeID AS type, SUM(e.numOccur) AS totalNumOccur, COUNT(re.reqID) AS totalNumOccurReq
                                        FROM error e
                                        LEFT JOIN request_error re ON re.errorID=e.errorID
                                        WHERE e.errorPhrase=:errorPhrase
                                        GROUP BY e.errorPhrase, e.errorTypeID
                                        ');
        $data->bindParam('errorPhrase', $group);
        $data->execute();
        $data = $data->fetch();

        if ($data) {
            $result['suspicious'] = $group;
            $result['num_occur'] = (int) $data['totalNumOccur'];

            // total num of requests
            $totalRequests = $this->dbh->prepare('SELECT COUNT(reqID) AS totalRequests FROM request');
            $totalRequests->execute();
            $totalRequests = $totalRequests->fetch();
            $result['req_count'] = (int) $totalRequests['totalRequests'];

            // num of requests where is error
            $result['num_occur_req'] = (int) $data['totalNumOccurReq'];
            $result['type'] = $data['type'];

            echo json_encode($result);
        }
    }
}
