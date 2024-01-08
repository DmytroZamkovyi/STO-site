<?

class employeeTask extends Curswork\ActionClass
{
    /**
     * Контроллер головної сторінки працівника вкладки "Завдання"
     * 
     * @param $params - параметри запиту
     * @param $data - дані, що передаються в шаблон
     * @return void
     */
    public function action_index($params, $data)
    {
        $view = "index";
        $data['label'] = 'Мої завдання';
        $data['menu'] = array(
            array(
                'name' => 'Завдання',
                'href' => '/employee/task/index',
                'active' => true,
                'img' => '/data/icon/task_activ.png'
            ),
            array(
                'name' => 'Деталі',
                'href' => '/employee/detail/index',
                'active' => false,
                'img' => '/data/icon/detail.png'
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
        $data['title'] = 'Завдання · ' . $this->config->projectName;

        $this->view->out($view, $data);
    }

    /**
     * Контроллер пошуку завдань
     * 
     * @param $params - параметри запиту
     * @param $data - дані, що передаються в шаблон
     * @return void
     */
    public function action_search($params, $data)
    {
        $data['search'] = urldecode($this->request->post->search);
        echo json_encode($this->model->search($data['search']));
    }

    /**
     * Контроллер відмітки завдання як виконаного
     * 
     * @param $params - параметри запиту
     * @param $data - дані, що передаються в шаблон
     * @return void
     */
    public function action_complete($params, $data)
    {
        $data['id'] = (int)$params;
        if ($this->model->complete($data['id'])) {
            header('HTTP/1.1 200 OK');
        } else {
            header('HTTP/1.1 400 Bad Request');
        }
        echo $data['id'];
    }
}
