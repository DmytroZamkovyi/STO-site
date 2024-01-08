<?

class indexSchedule extends Curswork\ActionClass
{
    /**
     * Контроллер для виводу розкладу
     * 
     * @param $params - параметри запиту
     * @param $data - дані для відображення
     */
    public function action_index($params, $data)
    {
        $view = "schedule";
        $this->view->out($view, $data);
    }

    /**
     * Контроллер для отримання розкладу за період часу
     * 
     * @param $params - параметри запиту
     * @param $data - дані для відображення
     */
    public function action_schedule($params, $data)
    {
        header('HTTP/1.1 200 OK');
        header('Content-Type: application/json; charset=utf-8');
        $schedule = $this->model->get_schedule($this->request->post->month, $this->request->post->year);
        echo $schedule;
    }
}
