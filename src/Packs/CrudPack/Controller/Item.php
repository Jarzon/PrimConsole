<?php
namespace PrimBase\BasePack\Controller;

use Prim\Controller;
use PrimUtilities\Forms;

use PrimBase\BasePack\Model\ItemModel;

class Item extends Controller
{
    use \UserPack\Service\Controller;

    function __construct($view, $container)
    {
        parent::__construct($view, $container);

        //$this->verification();
    }

    private function getItemForms($infos = false) {
        $forms = new Forms($_POST);

        $forms->text('', 'name', '', $infos->name, 50, 0, ['required' => 'required']);
        $forms->text('', 'description', '', $infos->description, 50, 0);

        return $forms;
    }

    public function index($page = 0)
    {
        $baseModel = new ItemModel($this->db);

        $item =  new class{
            public $name = '';
            public $description = '';
        };

        $forms = $this->getItemForms($item);

        // if we have POST data to create a new item entry
        if (isset($_POST['submit_item'])) {
            try {
                $params = $forms->verification();

                $this->addVar('message', ['ok', 'the item have been added']);
            }
            catch (\Exception $e) {
                $this->addVar('message', ['error', $e->getMessage()]);
            }

            if(isset($params)) {
                $params[] = $this->user_id;

                $baseModel->addItem(...$params);
            }
        }

        $this->design('index', '', [
            'items' => $baseModel->getAllItems($this->user_id),
            'forms' => $forms->getForms()
        ]);
    }

    public function deleteItem(int $item_id)
    {
        $item = new ItemModel($this->db);

        if (isset($item_id)) {
            $item->deleteItem($item_id, $this->user_id);
        }

        $this->redirect('/products/');
    }

    public function showItem(int $item_id)
    {
        $item = new ItemModel($this->db);

        $infos = $item->getItem($item_id, $this->user_id);

        $forms = $this->getItemForms($infos);

        if (isset($_POST['submit_item'])) {

            try {
                $params = $forms->verification();

                $this->addVar('message', ['ok', 'the product have been updated']);
            }
            catch (\Exception $e) {
                $this->addVar('message', ['error', $e->getMessage()]);
            }

            $params[] = $item_id;
            $params[] = $this->user_id;

            $item->updateItem(...$params);
        }

        $this->design('edit', '', [
            'forms' => $forms->getForms(),
            'item' => $infos
        ]);
    }
}
