<?php
namespace PrimBase\BasePack\Model;

use Prim\Model;

class ItemModel extends Model
{
    public function getAllItems(int $user_id)
    {
        $query = $this->db->prepare("SELECT id, name, description
            FROM items
            WHERE status > 0 AND user_id = ?");
        $query->execute([$user_id]);

        return $query->fetchAll();
    }

    public function getItemsSelect(int $user_id)
    {
        $rows = $this->getAllItems($user_id);

        $output = [];
        foreach($rows as $row) {
            $output[$row->name_fac] = $row->id;
        }

        return $output;
    }

    public function addItem(string $name, string $description, int $user_id)
    {
        $sql = "INSERT INTO items (name, description, user_id)
            VALUES (?, ?, ?)";
        $query = $this->db->prepare($sql);

        $query->execute([$name, $description, $user_id]);
    }

    public function deleteItem(int $item_id, int $user_id)
    {
        $sql = "DELETE FROM items WHERE id = ? AND user_id = ?";
        $query = $this->db->prepare($sql);

        $query->execute([$item_id, $user_id]);
    }

    public function hideItem(int $id, int $user_id)
    {
        $sql = "UPDATE items
        SET status = 0
        WHERE id = ? AND user_id = ?";
        $query = $this->db->prepare($sql);
        $query->execute([$id, $user_id]);
    }

    public function getItem(int $id, int $user_id)
    {
        $sql = "SELECT id, name, description
        FROM items
        WHERE id = ? AND user_id = ?";
        $query = $this->db->prepare($sql);

        $query->execute([$id, $user_id]);

        return $query->fetch();
    }

    public function updateItem(string $name, string $description, int $id, int $user_id)
    {
        $sql = "UPDATE items
        SET name = ?, description = ?
        WHERE id = ? AND user_id = ? AND user_id = ?";
        $query = $this->db->prepare($sql);
        $query->execute([$name, $description, $id, $user_id]);
    }
}