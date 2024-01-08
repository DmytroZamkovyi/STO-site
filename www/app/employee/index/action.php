<?

class employeeIndex extends Curswork\ActionClass
{
    /**
     * Контролер для перенаправлення на головну сторінку працівника
     * 
     * @return void
     */
    public function action_index()
    {
        $this->response->redirect('/employee/task/index');
    }

    /**
     * Контролер для відображення розкладу сторінки працівника
     * 
     * @param array $params - параметри
     * @param array $data - дані
     * @return void
     */
    public function action_schedule($params, $data)
    {
        $view = "schedule";

        $data['label'] = 'Мій графік';
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
                'active' => false,
                'img' => '/data/icon/detail.png'
            ),
            array(
                'name' => 'Розклад',
                'href' => '/employee/index/schedule',
                'active' => true,
                'img' => '/data/icon/calendar_activ.png'
            ),
            array(
                'name' => 'Вихід',
                'href' => '/index/index/logout',
                'active' => false,
                'img' => '/data/icon/logout.png'
            ),
        );
        $data['head'] = $this->load('index', 'index', 'head', '', $data);

        $data['title'] = 'Розклад · ' . $this->config->projectName;
        $data['schedule'] = $this->load('index', 'schedule', 'index');

        $this->view->out($view, $data);
    }
}