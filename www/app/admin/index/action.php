<?

class adminIndex extends Curswork\ActionClass
{
    /**
     * Контроллер для перенаправлення на головну сторінку адміністратора
     * 
     * @param $params
     * @param $data
     * @return mixed
     */
    public function action_index($params, $data)
    {
        $this->response->redirect('/admin/employee/index');
    }
}
