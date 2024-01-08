<?

class employeeDetail extends Curswork\ActionClass
{
    /**
     * Контроллер головної сторінки працівника вкладки "Деталі"
     * 
     * @param $params - параметри запиту
     * @param $data - дані, що передаються в шаблон
     * @return void
     */
    public function action_index($params, $data)
    {
        $view = "index";
        $data['label'] = 'Доступні деталі';
        $data['menu'] = array(
            array(
                'name' => 'Завдання',
                'href' => '/employee/task/index',
                'active' => false,
                'img' => '/data/icon/task.png'
            ),
            array(
                'name' => 'Деталі',
                'href' => '/employee/detail/index',
                'active' => true,
                'img' => '/data/icon/detail_activ.png'
            ),
            array(
                'name' => 'Розклад',
                'href' => '/employee/index/schedule',
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
        $data['title'] = 'Деталі · ' . $this->config->projectName;

        $this->view->out($view, $data);
    }

    /**
     * Контроллер пошуку деталей
     * 
     * @param $params - параметри запиту
     * @param $data - дані, що передаються в шаблон
     * @return void
     */
    public function action_search($params, $data)
    {
        $data['search'] = urldecode($this->request->post->search);
        echo json_encode($this->model->get_details($data['search']));
    }

    /**
     * Контроллер отримання деталі
     * 
     * @param $params - параметри запиту
     * @param $data - дані, що передаються в шаблон
     * @return void
     */
    public function action_get($params, $data)
    {
        $data['search'] = urldecode($this->request->post->search);
        echo json_encode($this->model->get_detail($data['search']));
    }

    /**
     * Контроллер відправки замовлення
     *! В контроллері відбувається симуляція відправки замовлення на сторонній сервіс
     * 
     * @param $params - параметри запиту
     * @param $data - дані, що передаються в шаблон
     * @return void
     */
    public function action_order($params, $data)
    {
        $data['data'] = urldecode($this->request->post->data);
        // Симуляці відправлення замовлення на сторонній сервіс
        echo $data['data'];
    }
}