<?php
namespace PrimBase\BasePack\Controller;

use Prim\Controller;
use Jarzon\Forms;

use PrimBase\BasePack\Model\ItemModel;

class Item extends Controller
{
    use \UserPack\Service\Controller;

    function __construct($view, $container)
    {
        parent::__construct($view, $container);

        $this->itemModel = $this->getModel('ItemModel', 'ItemPack');

        $this->verification();
    }

    private function getItemForms() {
        $forms = new Forms($_POST);

        $forms
            ->text('name')->max(50)->required()
            ->text('description')->max(50);

        return $forms;
    }

    public function index($page = 0)
    {
        $baseModel = new ItemModel($this->db);

        $forms = $this->getItemForms();

        // if we have POST data to create a new item entry
        if (isset($_POST['submit_item'])) {
            try {
                $params = $forms->verification();

                $_SESSION['flash_message'] = ['ok', 'the item have been added'];
            }
            catch (\Exception $e) {
                $_SESSION['flash_message'] = ['error', $e->getMessage()];
            }

            if(isset($params)) {
                $params[] = $this->user_id;

                $baseModel->addItem($params);
            }
        }

        $this->design('index', '', [
            'items' => $baseModel->getAllItems(),
            'forms' => $forms->getForms()
        ]);
    }

    public function deleteItem(int $item_id)
    {
        $item = new ItemModel($this->db);

        if (isset($item_id)) {
            $item->deleteItem($item_id);
        }

        $this->redirect('/products/');
    }

    public function showItem(int $item_id)
    {
        $item = new ItemModel($this->db);

        $infos = $item->getItem($item_id);

        $forms = $this->getItemForms($infos);

        $forms->updateValues($item);

        if (isset($_POST['submit_item'])) {

            try {
                $params = $forms->verification();

                $_SESSION['flash_message'] = ['ok', 'the product have been updated'];
            }
            catch (\Exception $e) {
                $_SESSION['flash_message'] = ['error', $e->getMessage()];
            }

            $item->updateItem($item_id, ...$params);
        }

        $this->design('edit', '', [
            'forms' => $forms->getForms(),
            'item' => $infos
        ]);
    }
}
