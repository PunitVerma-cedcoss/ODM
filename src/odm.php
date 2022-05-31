<?php

namespace PunitvermaCedcoss\Odm;

class ODMComponent extends MongoDB
{
    public $data;
    public $id;
    public $collection = "user_details";
    public function saveMe()
    {
        $collection = $this->collection;

        if (isset($this->id)) {
            $this->getConnection()->$collection->updateOne(
                [
                    '_id' => new \MongoDB\BSON\ObjectID($this->id)
                ],
                [
                    '$set' => $this->data
                ]
            );
        } else {
            $b = $this->getConnection()->$collection->insertone(
                $this->data
            );
            $this->id = json_decode(json_encode($b->getinsertedId()), true)['$oid'];
        }
    }
    public function findDoc($id = "", $collection = "", $conditions = [])
    {
        if (trim($collection) != '') $this->collection = $collection;

        $this->data = new \stdClass();
        $data = $this->getConnection();
        try {

            if ($conditions == []) {
                $x = json_decode(json_encode($data->$collection->findOne(
                    [
                        '_id' => new \MongoDB\BSON\ObjectID($id)
                    ]
                )), true);
                if ($x) {
                    $this->id = $x["_id"];
                    foreach ($x as $k => $v) {
                        if ($k !== "_id")
                            $this->data->$k = $v;
                    }
                }
            } else {
                $x = json_decode(json_encode($data->$collection->findOne(
                    $conditions
                )), true);

                if ($x) {
                    $this->id = $x["_id"];

                    foreach ($x as $k => $v) {
                        if ($k !== "_id")
                            $this->data->$k = $v;
                    }
                }
            }
        } catch (\Exception $e) {
            return $this->data;
        }

        return $this->data;

        // print_r($this->data);

        // print_r(get_object_vars($this));
    }
    public function __destruct()
    {
        echo "ended";
    }
}
