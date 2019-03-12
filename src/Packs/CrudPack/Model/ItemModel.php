<?php
namespace PrimBase\BasePack\Model;

use Prim\Model;

class ItemModel extends Model
{
    public function getAllItems()
    {
        $query = $this->prepare("SELECT id, name, description
            FROM items
            WHERE status > 0 AND user_id = ?");
        $query->execute([$this->user->id]);

        return $query->fetchAll();
    }

    public function getItemsSelect()
    {
        $rows = $this->getAllItems();

        $output = [];
        foreach($rows as $row) {
            $output[$row->name_fac] = $row->id;
        }

        return $output;
    }

    public function addItem(array $params)
    {
        $params['user_id'] = $this->user->id;

        $this->insert('items', $params);
    }

    public function deleteItem(int $item_id)
    {
        $query = $this->prepare("DELETE FROM items WHERE id = ? AND user_id = ?");

        $query->execute([$item_id, $this->user->id]);
    }

    public function hideItem(int $id)
    {
        $this->update('items', [
            'status' => -1
        ], [
            'id' => $id,
            'user_id' => $this->user->id,
        ]);
    }

    public function getItem(int $id)
    {
        $query = $this->prepare("SELECT id, name, description
        FROM items
        WHERE id = ? AND user_id = ?");

        $query->execute([$id, $this->user->id]);

        return $query->fetch();
    }

    public function updateItem(int $id, array $params)
    {
        $where = [
            'id' => $id,
            'user_id' => $this->user->id
        ];

        $this->update('items', $params, $where);
    }
}