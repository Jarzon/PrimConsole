<?php
namespace PrimBase\BasePack\Model;

use Prim\Model;

class BaseModel extends Model
{
    public function getAllRows()
    {
        $query = $this->db->prepare("SELECT * FROM base_table");
        $query->execute();

        return $query->fetchAll();
    }
}