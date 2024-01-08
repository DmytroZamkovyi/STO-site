<?

class managerOrder extends Curswork\ActionClass
{
    /**
     * Контроллер для відображення списку замовлень (головної сторінки менеджера/замовлення)
     * 
     * @param string $params - параметри
     * @param array $data - дані
     * @return void
     */
    public function action_index($params, $data)
    {
        $view = "index";
        $data['label'] = 'Замовлення';
        $data['menu'] = array(
            array(
                'name' => 'Замовленя',
                'href' => '/manager/order/index',
                'active' => true,
                'img' => '/data/icon/order_activ.png'
            ),
            array(
                'name' => 'Розклад',
                'href' => '/manager/index/schedule',
                'active' => false,
                'img' => '/data/icon/calendar.png'
            ),
            array(
                'name' => 'Вихід',
                'href' => '/index/index/logout',
                'active' => false,
                'img' => '/data/icon/logout.png'
            ),
        );
        $data['head'] = $this->load('index', 'index', 'head', '', $data);
        $data['title'] = 'Замовлення · ' . $this->config->projectName;
        $data['success'] = $this->session->success == null ? 'hidden' : '';
        unset($this->session->success);

        $this->view->out($view, $data);
    }

    /**
     * Контроллер для пошуку замовлень
     * 
     * @param string $params - параметри
     * @param array $data - дані
     * @return void
     */
    public function action_search($params, $data)
    {
        $data['first_name'] = isset($this->request->post->first_name) ? urldecode($this->request->post->first_name) : null;
        $data['last_name'] = isset($this->request->post->last_name) ? urldecode($this->request->post->last_name) : null;
        $data['father_name'] = isset($this->request->post->father_name) ? urldecode($this->request->post->father_name) : null;
        $data['phone_number'] = isset($this->request->post->phone_number) ? urldecode($this->request->post->phone_number) : null;
        $data['car'] = isset($this->request->post->car) ? urldecode($this->request->post->car) : null;
        $data['start_date'] = isset($this->request->post->start_date) ? urldecode($this->request->post->start_date) : null;
        $data['end_date'] = isset($this->request->post->end_date) ? urldecode($this->request->post->end_date) : null;
        $data['complete'] = isset($this->request->post->complete) ? urldecode($this->request->post->complete) : null;
        $data['pay'] = isset($this->request->post->pay) ? urldecode($this->request->post->pay) : null;

        if ($data['first_name'] == null) {
            unset($data['first_name']);
        } elseif (strlen($data['first_name']) < 3 || strlen($data['first_name']) > 40) {
            header('HTTP/1.1 400 Bad Request');
            return;
        }

        if ($data['last_name'] == null) {
            unset($data['last_name']);
        } elseif (strlen($data['last_name']) < 3 || strlen($data['last_name']) > 40) {
            header('HTTP/1.1 400 Bad Request');
            return;
        }

        if ($data['father_name'] == null) {
            unset($data['father_name']);
        } elseif (strlen($data['father_name']) < 3 || strlen($data['father_name']) > 40) {
            header('HTTP/1.1 400 Bad Request');
            return;
        }

        if ($data['phone_number'] == null) {
            unset($data['phone_number']);
        } else {
            preg_match_all('/^[ ]*\+?([0-9]{2})[-\( ]*([0-9]{1})[-\( ]*([0-9]{2})[-\) ]*([0-9]{3})[- ]*([0-9]{2})[- ]*([0-9]{2})[ ]*$/', $data['phone_number'], $matches);
            if (count($matches[0]) == 0) {
                header('HTTP/1.1 400 Bad Request');
                return;
            }
            $matches = array_slice($matches, 1);
            for ($i = 0; $i < count($matches); $i++) {
                $matches[$i] = (int)$matches[$i][0];
            }
            $data['phone_number'] = (int)implode($matches);
        }

        if ($data['car'] == null) {
            unset($data['car']);
        } elseif (strlen($data['car']) == 0 || strlen($data['car']) > 10) {
            header('HTTP/1.1 400 Bad Request');
            return;
        }

        if ($data['start_date'] == null) {
            unset($data['start_date']);
        } elseif (strlen($data['start_date']) != 10) {
            header('HTTP/1.1 400 Bad Request');
            return;
        }

        if ($data['end_date'] == null) {
            unset($data['end_date']);
        } elseif (strlen($data['end_date']) != 10) {
            header('HTTP/1.1 400 Bad Request');
            return;
        }

        if (isset($data['start_date']) && isset($data['end_date'])) {
            if (strtotime($data['start_date']) > strtotime($data['end_date'])) {
                header('HTTP/1.1 400 Bad Request');
                return;
            }
        }

        if ($data['complete'] == null) {
            unset($data['complete']);
        } elseif ($data['complete'] != 'true' && $data['complete'] != 'false') {
            header('HTTP/1.1 400 Bad Request');
            return;
        }

        if ($data['pay'] == null) {
            unset($data['pay']);
        } elseif ($data['pay'] != 'true' && $data['pay'] != 'false') {
            header('HTTP/1.1 400 Bad Request');
            return;
        }

        echo json_encode($this->model->search($data));
    }

    /**
     * Контроллер для редагування замовлення
     * 
     * @param string $params - параметри
     * @param array $data - дані
     * @return void
     */
    public function action_update($params, $data)
    {
        $data['order_id'] = $params;
        $data['discount'] = (int)urldecode($this->request->post->discount);

        if (!$this->model->update($data)) {
            header('HTTP/1.1 400 Bad Request');
            return;
        }
        header('HTTP/1.1 200 Ok');
    }

    /**
     * Контроллер для видалення замовлення
     * 
     * @param string $params - параметри
     * @param array $data - дані
     * @return void
     */
    public function action_delete($params, $data)
    {
        $data['order_id'] = (int)$params;
        $this->model->delete($data['order_id']);
        $this->response->redirect('/manager/order/index');
    }

    /**
     * Контроллер для створення замовлення
     * 
     * @param string $params - параметри
     * @param array $data - дані
     * @return void
     */
    public function action_add($params, $data)
    {
        $this->session->success = true;
    }

    /**
     * Контроллер для перегляду сторінки створення замовлення
     * 
     * @param string $params - параметри
     * @param array $data - дані
     * @return void
     */
    public function action_create($params, $data)
    {
        $view = "create";

        $data['title'] = 'Створення замовлення · ' . $this->config->projectName;

        $data['sto_list'] = $this->model->get_sto_list();

        $this->view->out($view, $data);
    }

    /**
     * Контроллер для перегляду сторінки редагування замовлення
     * 
     * @param string $params - параметри
     * @param array $data - дані
     * @return void
     */
    public function action_edit($params, $data)
    {
        $data['order_id'] = $params;
        $view = "edit";

        $data['order_info'] = $this->model->get_order($data['order_id']);
        if (!$data['order_info']) {
            header('HTTP/1.1 404 Not Found');
            $this->response->redirect('/index/index/404');
            return;
        }

        $data['task_list'] = $this->model->get_task($data['order_id']);
        if (!$data['task_list']) {
            header('HTTP/1.1 404 Not Found');
            $this->response->redirect('/index/index/404');
            return;
        }

        if (count($data['task_list']) == 0) {
            header("HTTP/1.1 400 Bad Request");
            $this->response->redirect('/manager/order/delete/' . $data['order_id']);
        }

        $data['sto_info'] = $this->model->get_sto($data['task_list'][0]['employee_id']);
        $data['title'] = 'Редагування замовлення · ' . $this->config->projectName;

        $this->view->out($view, $data);
    }

    /**
     * Контроллер для отримання списку завдань замовлення
     * 
     * @param string $params - параметри
     * @param array $data - дані
     * @return void
     */
    public function action_get_task($params, $data)
    {
        $data['order_id'] = $params;

        $data['task_list'] = $this->model->get_task($data['order_id']);
        if (!$data['task_list']) {
            header('HTTP/1.1 404 Not Found');
            $this->response->redirect('/index/index/404');
            return;
        }

        echo json_encode($data['task_list']);
    }

    /**
     * Контроллер для видалення завдання замовлення
     * 
     * @param string $params - параметри
     * @param array $data - дані
     * @return void
     */
    public function action_delete_task($params, $data)
    {
        $data['task_id'] = $params;

        if (!$this->model->delete_task($data['task_id'])) {
            header('HTTP/1.1 400 Bad Request');
        } else {
            header('HTTP/1.1 200 Ok');
        }
    }

    /**
     * Контроллер для отримання списку працівників СТО
     * 
     * @param string $params - параметри
     * @param array $data - дані
     * @return void
     */
    public function action_get_employee_list($params, $data)
    {
        $data['sto_name'] = urldecode($this->request->post->sto_name);

        $result = $this->model->get_employee_list($data['sto_name']);
        if (!$result) {
            header('HTTP/1.1 400 Bad Request');
            return;
        }
        echo json_encode($result);
    }

    /**
     * Контроллер для отримання списку послуг
     * 
     * @param string $params - параметри
     * @param array $data - дані
     * @return void
     */
    public function action_get_task_list($params, $data)
    {
        $result = $this->model->get_task_list();
        if (!$result) {
            header('HTTP/1.1 400 Bad Request');
            return;
        }
        echo json_encode($result);
    }

    /**
     * Контроллер для додавання завдання замовлення
     * 
     * @param string $params - параметри
     * @param array $data - дані
     * @return void
     */
    public function action_add_task($params, $data)
    {
        $data['order_id'] = urldecode($params);
        $data['name'] = explode(' ', urldecode($this->request->post->name));
        $data['task'] = urldecode($this->request->post->task);

        if (!$this->model->add_task($data)) {
            header('HTTP/1.1 400 Bad Request');
            return;
        }
        header('HTTP/1.1 200 Ok');
    }

    /**
     * Контроллер для пошуку клієнтів
     * 
     * @param string $params - параметри
     * @param array $data - дані
     * @return void - масив з клієнтами
     */
    public function action_search_client($params, $data)
    {
        $data['search'] = urldecode($this->request->post->search);

        $result = $this->model->search_client($data['search']);
        if (!$result) {
            header('HTTP/1.1 400 Bad Request');
            return;
        }
        echo json_encode($result);
    }

    /**
     * Контроллер для додавання клієнта
     * 
     * @param string $params - параметри
     * @param array $data - дані
     * @return void - масив з клієнтом
     */
    public function action_add_client($params, $data)
    {
        $data['first_name'] = urldecode($this->request->post->first_name);
        $data['last_name'] = urldecode($this->request->post->last_name);
        $data['fathers_name'] = $this->request->post->father_name;
        $data['phone_number'] = urldecode($this->request->post->phone_number);

        $search = $this->model->search_client_by_phone($data['phone_number']);

        if ($search) {
            header('HTTP/1.1 200 Ok');
            echo json_encode($search);
            return;
        }

        if (strlen($data['first_name']) < 3 || strlen($data['first_name']) > 40) {
            header('HTTP/1.1 400 Bad Request');
            return;
        }

        if (strlen($data['last_name']) < 3 || strlen($data['last_name']) > 40) {
            header('HTTP/1.1 400 Bad Request');
            return;
        }

        preg_match_all('/^[ ]*\+?([0-9]{2})[-\( ]*([0-9]{1})[-\( ]*([0-9]{2})[-\) ]*([0-9]{3})[- ]*([0-9]{2})[- ]*([0-9]{2})[ ]*$/', $data['phone_number'], $matches);
        if (count($matches[0]) == 0) {
            header('HTTP/1.1 400 Bad Request');
            return;
        }
        $matches = array_slice($matches, 1);
        for ($i = 0; $i < count($matches); $i++) {
            $matches[$i] = (int)$matches[$i][0];
        }
        $data['phone_number'] = (int)implode($matches);

        $add = $this->model->add_client($data);
        if (!$add) {
            header('HTTP/1.1 400 Bad Request');
            return;
        }
        echo json_encode($this->model->search_client_by_phone($data['phone_number']));
    }

    /**
     * Контроллер для пошуку автомобілів
     * 
     * @param string $params - параметри
     * @param array $data - дані
     * @return void - масив з автомобілями
     */
    public function action_search_car($params, $data)
    {
        $data['search'] = urldecode($this->request->post->search);

        $result = $this->model->search_car($data['search']);
        if (!$result) {
            header('HTTP/1.1 400 Bad Request');
            return;
        }
        echo json_encode($result);
    }

    /**
     * Контроллер для додавання автомобіля
     * 
     * @param string $params - параметри
     * @param array $data - дані
     * @return void - масив з автомобілем
     */
    public function action_add_car($params, $data)
    {
        $data['make'] = urldecode($this->request->post->make);
        $data['model'] = urldecode($this->request->post->model);
        $data['year'] = urldecode($this->request->post->car_year);
        $data['license_plate'] = urldecode($this->request->post->license_plate);
        $data['description'] = urldecode($this->request->post->description);

        $search = $this->model->search_car($data['license_plate']);

        if ($search) {
            header('HTTP/1.1 200 Ok');
            echo json_encode($search);
            return;
        }

        if (strlen($data['make']) < 3 || strlen($data['make']) > 40) {
            header('HTTP/1.1 400 Bad Request');
            return;
        }

        if (strlen($data['model']) < 3 || strlen($data['model']) > 40) {
            header('HTTP/1.1 400 Bad Request');
            return;
        }

        if (strlen($data['year']) != 4) {
            header('HTTP/1.1 400 Bad Request');
            return;
        }

        $add = $this->model->add_car($data);

        if (!$add) {
            header('HTTP/1.1 400 Bad Request');
            return;
        }
        echo json_encode($this->model->search_car($data['license_plate']));
    }

    /**
     * Контроллер для створення замовлення
     * 
     * @param string $params - параметри
     * @param array $data - дані
     * @return void - масив з замовленням
     */
    public function action_create_order($params, $data)
    {
        $data['client_id'] = urldecode($this->request->post->client_id);
        $data['car_id'] = urldecode($this->request->post->car_id);

        $result = $this->model->create_order($data);
        if (!$result) {
            header('HTTP/1.1 400 Bad Request');
            return;
        }
        echo json_encode($result);
    }
}
