<?

class adminEmployee extends Curswork\ActionClass
{
    /**
     * Контроллер для відображення списку працівників
     * 
     * @param $params - параметри
     * @param $data - дані
     * @return mixed
     */
    public function action_index($params, $data)
    {
        $view = "index";
        $data['label'] = 'Перегляд працівників';
        $data['menu'] = array(
            array(
                'name' => 'Працівники',
                'href' => '/admin/employee/index',
                'active' => true,
                'img' => '/data/icon/employee_activ.png'
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
                'active' => false,
                'img' => '/data/icon/client.png'
            ),
            array(
                'name' => 'Вихід',
                'href' => '/index/index/logout',
                'active' => false,
                'img' => '/data/icon/logout.png'
            ),
        );
        $data['head'] = $this->load('index', 'index', 'head', '', $data);
        $data['title'] = 'Працівники · ' . $this->config->projectName;

        $data['role_list'] = $this->model->get_role_list();
        $data['sto_list'] = $this->model->get_sto_list();
        $data['branch_list'] = $this->model->get_branch_list();
        $data['position_list'] = $this->model->get_position_list();

        $this->view->out($view, $data);
    }

    /**
     * Контроллер для додаання нового працівника
     * 
     * @param $params - параметри
     * @param $data - дані
     */
    public function action_create($params, $data)
    {
        $data['role'] = urldecode($this->request->post->role);
        $data['sto'] = urldecode($this->request->post->sto);
        $data['branch'] = urldecode($this->request->post->branch);
        $data['position'] = urldecode($this->request->post->position);
        $data['first_name'] = urldecode($this->request->post->first_name);
        $data['last_name'] = urldecode($this->request->post->last_name);
        $data['father_name'] = urldecode($this->request->post->father_name);
        $data['phone_number'] = urldecode($this->request->post->phone_number);
        $data['login'] = urldecode($this->request->post->login);
        $data['pwd'] = urldecode($this->request->post->pwd);
        $data['tarif'] = urldecode($this->request->post->tarif);

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

        $data['role'] = (int)$data['role'];
        $data['sto'] = $this->model->get_sto_id($data['sto']);
        $data['branch'] = $this->model->get_branch_id($data['branch']);
        $data['position'] = $this->model->get_position_id($data['position']);
        $data['tarif'] = (float)$data['tarif'];
        $data['pwd'] = hash('md5', $data['pwd']);

        if ($this->model->create($data)) {
            header('HTTP/1.1 200 OK');
        } else {
            header('HTTP/1.1 400 Bad Request');
        }
    }

    /**
     * Контроллер для пошуку працівників
     * 
     * @param $params - параметри
     * @param $data - дані
     * @return mixed
     */
    public function action_search($params, $data)
    {
        if (isset($this->request->post->role)) {
            $data['role'] = (int)urldecode($this->request->post->role);
        }
        if (isset($this->request->post->sto)) {
            $data['sto'] = (int)$this->model->get_sto_id(urldecode($this->request->post->sto));
        }
        if (isset($this->request->post->branch)) {
            $data['branch'] = (int)$this->model->get_branch_id(urldecode($this->request->post->branch));
        }
        if (isset($this->request->post->position)) {
            $data['position'] = (int)$this->model->get_position_id(urldecode($this->request->post->position));
        }
        if (isset($this->request->post->first_name)) {
            $data['first_name'] = urldecode($this->request->post->first_name);
        }
        if (isset($this->request->post->last_name)) {
            $data['last_name'] = urldecode($this->request->post->last_name);
        }
        if (isset($this->request->post->father_name)) {
            $data['father_name'] = urldecode($this->request->post->father_name);
        }
        if (isset($this->request->post->login)) {
            $data['login'] = urldecode($this->request->post->login);
        }

        $result = $this->model->search($data);

        if (!$this->db->last_error) {
            header('HTTP/1.1 200 OK');
            echo json_encode($result);
        } else {
            header('HTTP/1.1 400 Bad Request');
        }
    }

    /**
     * Контроллер для відображення сторінки редагування працівника
     * 
     * @param $params - параметри
     * @param $data - дані
     * @return mixed
     */
    public function action_edit($params, $data)
    {
        $data['user_id'] = (int)$params;
        $view = "edit";
        $data['title'] = 'Працівники · ' . $this->config->projectName;

        $data['user_name'] = $this->model->get_account($data['user_id']);

        $data['role_list'] = $this->model->get_role_list();
        $data['sto_list'] = $this->model->get_sto_list();
        $data['branch_list'] = $this->model->get_branch_list();
        $data['position_list'] = $this->model->get_position_list();
        $data['employee'] = $this->model->get_employee($data['user_id']);

        $this->view->out($view, $data);
    }

    /**
     * Контроллер для оновлення працівника
     * 
     * @param $params - параметри
     * @param $data - дані
     */
    public function action_update($params, $data)
    {
        $data['user_id'] = (int)$params;
        $data['role'] = urldecode($this->request->post->role);
        $data['sto'] = urldecode($this->request->post->sto);
        $data['branch'] = urldecode($this->request->post->branch);
        $data['position'] = urldecode($this->request->post->position);
        $data['first_name'] = urldecode($this->request->post->first_name);
        $data['last_name'] = urldecode($this->request->post->last_name);
        $data['father_name'] = urldecode($this->request->post->father_name);
        $data['phone_number'] = urldecode($this->request->post->phone_number);
        $data['login'] = urldecode($this->request->post->login);
        $data['pwd'] = urldecode($this->request->post->pwd);
        $data['tarif'] = urldecode($this->request->post->tarif);

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

        $data['role'] = (int)$data['role'];
        $data['sto'] = $this->model->get_sto_id($data['sto']);
        $data['branch'] = $this->model->get_branch_id($data['branch']);
        $data['position'] = $this->model->get_position_id($data['position']);
        $data['tarif'] = (float)$data['tarif'];
        if ($data['pwd'] != '') {
            $data['pwd'] = hash('md5', $data['pwd']);
        } else {
            unset($data['pwd']);
        }

        if ($this->model->update($data)) {
            header('HTTP/1.1 200 OK');
        } else {
            header('HTTP/1.1 400 Bad Request');
        }
    }

    /**
     * Контроллер для видалення працівника
     * 
     * @param $params - параметри
     * @param $data - дані
     * @return mixed
     */
    public function action_delete($params, $data)
    {
        $data['user_id'] = (int)$params;
        $this->model->delete($data['user_id']);
        header('HTTP/1.1 200 OK');
        header('Location: /admin/employee/index');
    }
}
