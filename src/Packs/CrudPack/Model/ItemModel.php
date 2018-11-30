<?php
namespace PrimBase\BasePack\Model;

use Prim\Model;

class ItemModel extends Model
{
    public function getAllItems(int $user_id)
    {
        $query = $this->prepare("SELECT id, name, description
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

    public function addItem(array $params)
    {
        $params['user_id'] = $this->user->id;

        $this->insert($params);
    }

    public function deleteItem(int $item_id, int $user_id)
    {
        $query = $this->prepare("DELETE FROM items WHERE id = ? AND user_id = ?");

        $query->execute([$item_id, $user_id]);
    }

    public function hideItem(int $id)
    {
        $this->update([
            'status' => -1
        ], [
            'id' => $id,
            'user_id' => $this->user->id,
        ]);
    }

    public function getItem(int $id, int $user_id)
    {
        $query = $this->prepare("SELECT id, name, description
        FROM items
        WHERE id = ? AND user_id = ?");

        $query->execute([$id, $user_id]);

        return $query->fetch();
    }

    public function updateItem(array $params)
    {
        $params['user_id'] = $this->user->id;

        $this->update($params);
    }
}