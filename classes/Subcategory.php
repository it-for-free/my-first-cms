<?php

class Subcategory {
    
    public $id = null;
    
    public $name = null;
    
    public $description = null;
    
    public $category = null;
    
    public function __construct($data = []) {
        
        if (isset($data['id'])){
            $this->id = $data['id'];
        }
        if (isset($data['name'])){
            $this->name = $data['name'];
        }
        if (isset($data['description'])){
            $this->description = $data['description'];
        }
        if (isset ($data['category'])){
            $this->category = $data['category'];
        }
    }
    public function storeFormValues($params) {
    if (is_array($params)) {
        $this->__construct($params);
        $this->category = $params['categoryId']; // Убедитесь, что вы сохраняете правильный параметр
    }
}

    
    public static function getById($id){
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT * FROM subcategories WHERE id = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":id",$id,PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        $conn = null;
        if ($row){
            return new Subcategory($row);
        }
    }
    public static function getList($numRows = 1000000, $categoryId = null, $order = "name ASC"){
    $clause = $categoryId ? "WHERE categoryId = :categoryId" : "";
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM subcategories $clause ORDER BY $order LIMIT :numRows";
    $st = $conn->prepare($sql);
    if ($categoryId) {
        $st->bindValue(":categoryId", $categoryId, PDO::PARAM_INT);
    }
    $st->bindValue(":numRows", $numRows, PDO::PARAM_INT);
    $st->execute();
    $list = array();
    while ($row = $st->fetch()){
        $subcategory = new Subcategory($row);
        $list[] = $subcategory;
    }
    $sql = "SELECT FOUND_ROWS() as totalRows";
    $totalRows = $conn->query($sql)->fetch();
    $conn = null;
    return array(
        "results" => $list,
        "totalRows" => $totalRows[0]
    );
}

   public function insert() {
    if (!is_null($this->id)) {
        trigger_error("Subcategory::insert(): Attempt to insert a Subcategory object that already has its ID property set (to $this->id).", E_USER_ERROR);
    }

    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "INSERT INTO subcategories (name, description, categoryId) VALUES (:name, :description, :categoryId)";
    $st = $conn->prepare($sql);
    $st->bindValue(":name", $this->name, PDO::PARAM_STR);
    $st->bindValue(":description", $this->description, PDO::PARAM_STR);
    $st->bindValue(":categoryId", $this->category, PDO::PARAM_INT); // Убедитесь, что здесь передается categoryId
    $st->execute();
    $this->id = $conn->lastInsertId();
    $conn = null;
}


   public function update(){
       if (is_null($this->id)) trigger_error("Subcategory::update(): Attempt to "
              . "update a Subcategory object that does not have its ID property "
              . "set.", E_USER_ERROR);
        $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
        $sql = "UPDATE subcategories SET name=:name, description=:description, categoryId=:categoryId WHERE id = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":name", $this->name,PDO::PARAM_STR);
        $st->bindValue(":description", $this->description,PDO::PARAM_STR);
        $st->bindValue(":categoryId", $this->category, PDO::PARAM_INT);
        $st->bindValue(":id", $this->id,PDO::PARAM_INT);
        $st->execute();
        $conn = null;
   }
   public function delete(){
       if (is_null($this->id)) trigger_error("Subcategory::delete(): Attempt to "
              . "delete a Subcategory object that does not have its ID property "
              . "set.", E_USER_ERROR);
       $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
       $sql = "DELETE FROM subcategories WHERE id = :id LIMIT 1";
       $st = $conn->prepare($sql);
       $st->bindValue("id",$this->id,PDO::PARAM_INT);
       $st->execute();
       $conn = null;
   }
}