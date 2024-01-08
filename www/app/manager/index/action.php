<?

class managerIndex extends Curswork\ActionClass
{
    /**
     * Контроллер для перенаправлення на головну сторінку менеджера
     * 
     * @param $params - параметри
     * @param $data - дані
     */
    public function action_index($params, $data)
    {
        $this->response->redirect('/manager/order/index');
    }

    /**
     * Контроллер для відображення списку нових замовлень
     * 
     * @param $params - параметри
     * @param $data - дані
     */
    public function action_schedule($params, $data)
    {
        $view = "schedule";
        
        $data['label'] = 'Мій графік';
        $data['menu'] = array(
            array(
                'name' => 'Замовлень',
                'href' => '/manager/order/index',
                'active' => false,
                'img' => '/data/icon/order.png'
            ),
            array(
                'name' => 'Розклад',
                'href' => '/manager/index/schedule',
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
