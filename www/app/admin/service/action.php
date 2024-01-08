<?

class adminService extends Curswork\ActionClass
{
    /**
     * Контроллер для виводу сторінки зі списком послуг
     * 
     * @param $params - параметри запиту
     * @param $data - дані запиту
     * @return mixed
     */
    public function action_index($params, $data)
    {
        $view = "index";
        $data['label'] = 'Перегляд послуг';
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
                'active' => true,
                'img' => '/data/icon/service_activ.png'
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
        $data['title'] = 'Послуги · ' . $this->config->projectName;

        $this->view->out($view, $data);
    }

    /**
     * Контроллер для ствроення нової послуги
     * 
     * @param $params - параметри запиту
     * @param $data - дані запиту
     * @return mixed
     */
    public function action_create($params, $data)
    {
        $data['name'] = urldecode($this->request->post->name);
        $data['description'] = urldecode($this->request->post->description);
        $data['price'] = (float)urldecode($this->request->post->price);

        if ($this->model->create($data)) {
            header('HTTP/1.1 200 OK');
        } else {
            header('HTTP/1.1 400 Bad Request');
        }
    }

    /**
     * Контроллер для пошуку послуги
     * 
     * @param $params - параметри запиту
     * @param $data - дані запиту
     * @return mixed
     */
    public function action_search($params, $data)
    {
        $data['search'] = urldecode($this->request->post->search);

        $result = $this->model->search($data);

        if ($result !== false) {
            header('HTTP/1.1 200 OK');
            echo json_encode($result);
        } else {
            header('HTTP/1.1 400 Bad Request');
        }
    }

    /**
     * Контроллер для видалення послуги
     * 
     * @param $params - параметри запиту
     * @param $data - дані запиту
     * @return mixed
     */
    public function action_delete($params, $data)
    {
        $data['service_id'] = (int)$params;

        $this->model->delete($data);
        header('HTTP/1.1 200 OK');
        header('Location: /admin/service/index');
    }

    /**
     * Контроллер для оновлення послуги
     * 
     * @param $params - параметри запиту
     * @param $data - дані запиту
     * @return mixed
     */
    public function action_update($params, $data)
    {
        $data['service_id'] = urldecode($this->request->post->id);
        $data['name'] = urldecode($this->request->post->name);
        $data['description'] = urldecode($this->request->post->description);
        $data['price'] = (float)urldecode($this->request->post->price);

        $this->model->update($data);
        header('HTTP/1.1 200 OK');
    }

    /**
     * Контроллер для виводу сторінки редагування послуги
     * 
     * @param $params - параметри запиту
     * @param $data - дані запиту
     * @return mixed
     */
    public function action_edit($params, $data)
    {
        $data['service_id'] = (int)$params;
        $view = "edit";
        $data['title'] = 'Товар · ' . $this->config->projectName;

        $data['servise'] = $this->model->get_service($data['service_id']);

        $this->view->out($view, $data);
    }
}
