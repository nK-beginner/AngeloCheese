<?php
    require_once __DIR__.'/../Model/customer_model.php';

    class CustomerController {
        private $model;

        public function __construct($pdo) {
            $this->model = new Customer($pdo);
        }

        public function listCustomers() {
            $customers = $this->model->getAllCustomers();

            require '../App/View/customer_list_view.php';
        }
    }
?>