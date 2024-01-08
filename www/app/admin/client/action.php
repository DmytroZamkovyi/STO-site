<?

class adminClient extends Curswork\ActionClass
{
    /**
     * Контроллер для відображення списку клієнтів
     * 
     * @param $params - параметри
     * @param $data - дані
     * @return mixed
     */
    public function action_index($params, $data)
    {
        $view = "index";
        $data['label'] = 'Перегляд клієнтів';
        $data['menu'] = array(
            array(
                'name' => 'Працівники',
                'href' => '/admin/employee/index',
                'active' => false,
                'img' => '/data/icon/employee.png'
            ),
            array(
                'name' => 'Послуги',
                'href' => '/admin/service/index',
                'active' => false,
                'img' => '/data/icon/service.png'
            ),
            array(
                'name' => 'Замовлення',
                'href' => '/admin/order/index',
                'active' => false,
                'img' => '/data/icon/order.png'
            ),
            array(
                'name' => 'Клієнти',
                'href' => '/admin/client/index',
                'active' => true,
                'img' => '/data/icon/client_activ.png'
            ),
            array(
                'name' => 'Вихід',
                'href' => '/index/index/logout',
                'active' => false,
                'img' => '/data/icon/logout.png'
            ),
        );
        $data['head'] = $this->load('index', 'index', 'head', '', $data);
        $data['title'] = 'Клієнти · ' . $this->config->projectName;

        $this->view->out($view, $data);
    }

    /**
     * Контроллер для додавання нового клієнта
     * 
     * @param $params - параметри
     * @param $data - дані
     * @return mixed
     */
    public function action_create($params, $data)
    {
        $data['first_name'] = urldecode($this->request->post->first_name);
        $data['last_name'] = urldecode($this->request->post->last_name);
        $data['father_name'] = urldecode($this->request->post->father_name);
        $data['phone_number'] = urldecode($this->request->post->phone_number);
        $data['discount'] = (int)urldecode($this->request->post->discount);

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

        if ($data['discount'] < 0 || $data['discount'] > 100) {
            header('HTTP/1.1 400 Bad Request');
            return;
        }

        if ($this->model->create($data)) {
            header('HTTP/1.1 200 OK');
        } else {
            header('HTTP/1.1 400 Bad Request');
        }
    }

    /**
     * Контроллер для пошуку клієнтів
     * 
     * @param $params - параметри
     * @param $data - дані
     * @return mixed
     */
    public function action_search($params, $data)
    {
        $data['first_name'] = $this->request->post->first_name;
        $data['last_name'] = $this->request->post->last_name;
        $data['father_name'] = $this->request->post->father_name;
        $data['phone_number'] = $this->request->post->phone_number;

        if ($data['first_name'] == ''){
            unset($data['first_name']);
        } else {
            $data['first_name'] = urldecode($data['first_name']);
        }

        if ($data['last_name'] == ''){
            unset($data['last_name']);
        } else {
            $data['last_name'] = urldecode($data['last_name']);
        }

        if ($data['father_name'] == ''){
            unset($data['father_name']);
        } else {
            $data['father_name'] = urldecode($data['father_name']);
        }

        if ($data['phone_number'] == ''){
            unset($data['phone_number']);
        } else {
            $data['phone_number'] = urldecode($data['phone_number']);
            preg_match_all('/^[ ]*\+?([0-9]{2})[-\( ]*([0-9]{1})[-\( ]*([0-9]{2})[-\) ]*([0-9]{3})[- ]*([0-9]{2})[- ]*([0-9]{2})[ ]*$/', $data['phone_number'], $matches);
            if (count($matches[0]) == 0) {
                unset($data['phone_number']);
            }
        }

        echo json_encode($this->model->search($data));
    }

    /**
     * Контроллер для відображення сторінки редагування клієнта
     * 
     * @param $params - параметри
     * @param $data - дані
     * @return mixed
     */
    public function action_edit($params, $data)
    {
        $data['user_id'] = (int)$params;
        $view = "edit";
        $data['title'] = 'Клієнти · ' . $this->config->projectName;

        $data['user'] = $this->model->get_client($data['user_id']);
        $data['order'] = $this->model->get_order($data['user_id']);

        $data['user_name'] = $data['user']['first_name'] . ' ' . $data['user']['last_name'] . ' ' . $data['user']['fathers_name'];

        $this->view->out($view, $data);
    }

    /**
     * Контроллер для оновлення даних клієнта
     * 
     * @param $params - параметри
     * @param $data - дані
     * @return mixed
     */
    public function action_update($params, $data)
    {
        $data['user_id'] = (int)urldecode($this->request->post->id);
        $data['first_name'] = urldecode($this->request->post->first_name);
        $data['last_name'] = urldecode($this->request->post->last_name);
        $data['father_name'] = urldecode($this->request->post->father_name);
        $data['phone_number'] = urldecode($this->request->post->phone_number);
        $data['discount'] = (int)urldecode($this->request->post->discount);

        if (strlen($data['first_name']) < 3 || strlen($data['first_name']) > 40) {
            header('HTTP/1.1 400 Bad Request');
        }

        if (strlen($data['last_name']) < 3 || strlen($data['last_name']) > 40) {
            header('HTTP/1.1 400 Bad Request');
        }

        if (strlen($data['father_name']) < 3 || strlen($data['father_name']) > 40) {
            header('HTTP/1.1 400 Bad Request');
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

        if ($data['discount'] < 0 || $data['discount'] > 100) {
            header('HTTP/1.1 400 Bad Request');
            return;
        }

        if ($this->model->update($data)) {
            header('HTTP/1.1 200 OK');
        } else {
            header('HTTP/1.1 400 Bad Request');
        }
    }
}
