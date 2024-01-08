<?

class indexIndexModel extends Curswork\ActionClassModel
{
    /**
     * Метод моделі для авторизації користувача
     * 
     * @param $login - логін користувача
     * @param $password - пароль користувача
     * @return bool
     */
    public function auth_user($login, $password)
    {
        $user_id = $this->db->query('SELECT "id" FROM employee WHERE login = \'' . $this->db->escape($login) . '\' and password = \'' . hash('md5', $this->db->escape($password)) . '\';');
        if ($user_id) {
            $user_id = (int)$user_id[0]['id'];
            $this->db->query('UPDATE session SET id_employee = ' . $user_id . ' WHERE session_key = \'' . $this->session->session . '\';');
            $this->session->load();
            return $this->auth->get_access();
        }
        return false;
    }

    /**
     * Метод моделі для виходу користувача
     * 
     * @return bool
     */
    public function logout()
    {
        $this->db->query('DELETE FROM "session" WHERE session_key = \'' . $this->session->session . '\';');
        return !$this->auth->get_access();
    }

    /**
     * Метод моделі для отримання ролі користувача
     * 
     * @param $user_id - id користувача
     * @return string
     */
    public function get_role($user_id)
    {
        $res = $this->db->query('SELECT role.role_name as role FROM employee, role WHERE id_role = role.id AND employee.id = ' . $user_id . ';');
        if ($res) {
            return $res[0]['role'];
        }
        return false;
    }

    /**
     * Метод моделі для отримання імені користувача
     * 
     * @return string
     */
    public function get_account()
    {
        $res = $this->db->query('SELECT first_name, last_name, fathers_name FROM employee WHERE id=' . $this->session->user_id . ';');
        if ($res) {
            return trim(implode(' ', $res[0]));
        }
        return '';
    }
}
