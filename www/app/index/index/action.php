<?

class indexIndex extends Curswork\ActionClass
{
    /**
     * Контроллер для розподіленню по сторінкам ролей
     * 
     * @param $params - параметри запиту
     * @param $data - дані для відображення
     * @return void
     */
    public function action_index($params, $data)
    {
        $role = $this->model->get_role($this->session->user_id);
        if ($role) {
            $this->response->redirect('/' . $role . '/index/index');
        }
    }

    /**
     * Контроллер для для відобреження сторінки 404
     * 
     * @param $params - параметри запиту
     * @param $data - дані для відображення
     * @return void
     */
    public function action_404($params, $data)
    {
        $this->response->header[] = 'HTTP/1.1 404 Not Found';
        $data['title'] = '404 · ' . $this->config->projectName;
        $view = '404';
        $this->view->out($view, $data);
    }

    /**
     * Контроллер для для відобреження сторінки входу
     * 
     * @param $params - параметри запиту
     * @param $data - дані для відображення
     * @return void
     */
    public function action_login($params, $data)
    {
        $this->response->header[] = 'HTTP/1.1 401 Unauthorized';
        $data['title'] = 'Вхід · ' . $this->config->projectName;
        $view = 'login';
        $this->view->out($view, $data);
    }

    /**
     * Функція для авториззації користувача
     * 
     * @return void
     */
    public function auth()
    {
        if (!isset($this->request->post->login) || !isset($this->request->post->password)) {
            header('HTTP/1.1 400 Bad Request');
            return;
        }
        if (strlen($this->request->post->login) < 3 || strlen($this->request->post->password) < 3) {
            header('HTTP/1.1 400 Bad Request');
            return;
        }
        $auth = $this->model->auth_user(urlencode($this->request->post->login), urlencode($this->request->post->password));
        if ($auth) {
            header('HTTP/1.1 200 OK');
        } else {
            header('HTTP/1.1 401 Unauthorized');
        }
    }

    /**
     * Контроллер для для виходу з аккаунту
     * 
     * @param $params - параметри запиту
     * @param $data - дані для відображення
     * @return void
     */
    public function action_logout($params, $data)
    {
        $this->model->logout();
        unset($_COOKIE[$this->config->sessionKey]);
        setcookie($this->config->sessionKey, null, 0, '/');
        $this->response->redirect('/index/index/login');
    }

    /**
     * Контроллер для для відобреження хедера сайту
     * 
     * @param $params - параметри запиту
     * @param $data - дані для відображення
     * @return void
     */
    public function action_head($params, $data)
    {
        $view = "head";
        $data['account'] = $this->model->get_account();
        $this->view->out($view, $data);
    }
}
