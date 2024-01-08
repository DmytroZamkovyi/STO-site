<?

class adminEmployeeModel extends Curswork\ActionClassModel
{
    /**
     * Метод моделі для отримання списку ролей
     * 
     * @return array
     */
    public function get_role_list(): array
    {
        $result = (array)$this->db->query('SELECT * FROM role');
        if ($result) {
            for ($i = 0; $i < count($result); $i++) {
                if ($result[$i]['role_name'] == 'employee') {
                    $result[$i]['active'] = true;
                } else {
                    $result[$i]['active'] = false;
                }
            }
            return $result;
        } else {
            return array();
        }
    }

    /**
     * Метод моделі для отримання списку СТО
     * 
     * @return array
     */
    public function get_sto_list(): array
    {
        $result = (array)$this->db->query('SELECT id, sto_name FROM sto');
        if ($result) {
            return $result;
        } else {
            return array();
        }
    }

    /**
     * Метод моделі для отримання списку відділень
     * 
     * @return array
     */
    public function get_branch_list(): array
    {
        $result = (array)$this->db->query('SELECT id, department_name FROM branch');
        if ($result) {
            return $result;
        } else {
            return array();
        }
    }

    /**
     * Метод моделі для отримання списку посад
     * 
     * @return array
     */
    public function get_position_list(): array
    {
        $result = (array)$this->db->query('SELECT id, position_name FROM position');
        if ($result) {
            return $result;
        } else {
            return array();
        }
    }

    /**
     * Метод моделі для id сто по назві
     * 
     * @param $sto_name - назва сто
     * @return bool|array
     */
    public function get_sto_id($sto_name): bool|array
    {
        $result = (array)$this->db->query('SELECT id FROM sto WHERE sto_name = \'' . $this->db->escape($sto_name) . '\';');
        if ($result) {
            return (int)$result[0]['id'];
        } else {
            return false;
        }
    }

    /**
     * Метод моделі для id відділення по назві
     * 
     * @param $branch_name - назва відділення
     * @return bool|array
     */
    public function get_branch_id($branch_name): bool|array
    {
        $result = (array)$this->db->query('SELECT id FROM branch WHERE department_name = \'' . $this->db->escape($branch_name) . '\';');
        if ($result) {
            return (int)$result[0]['id'];
        } else {
            return false;
        }
    }

    /**
     * Метод моделі для id посади по назві
     * 
     * @param $position_name - назва посади
     * @return bool|array
     */
    public function get_position_id($position_name): bool|array
    {
        $result = (array)$this->db->query('SELECT id FROM position WHERE position_name = \'' . $this->db->escape($position_name) . '\';');
        if ($result) {
            return (int)$result[0]['id'];
        } else {
            return false;
        }
    }

    /**
     * Метод моделі для створення працівника
     * 
     * @param $data - дані
     * @return bool
     */
    public function create($data): bool
    {
        foreach ($data as $key => $value) {
            $data[$key] = $this->db->escape($value);
        }
        $sql = 'INSERT INTO employee (id_role, id_sto, id_branch, id_position, first_name, last_name, fathers_name, phone_number, login, password, tariff) VALUES (' . $data['role'] . ', ' . $data['sto'] . ', ' . $data['branch'] . ', ' . $data['position'] . ', \'' . $data['first_name'] . '\', \'' . $data['last_name'] . '\', \'' . $data['father_name'] . '\', ' . $data['phone_number'] . ', \'' . $data['login'] . '\', \'' . $data['pwd'] . '\', ' . $data['tarif'] . ');';
        $this->db->query($sql);
        if ($this->db->last_error) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Метод моделі для пошуку працівників
     * 
     * @param $data - дані
     * @return array
     */
    public function search($data): array
    {
        foreach ($data as $key => $value) {
            $data[$key] = $this->db->escape($value);
        }

        $sql = 'SELECT employee.id AS employee_id, employee.phone_number AS employee_pn, * FROM employee, role, sto, branch, position';
        $sql .= ' WHERE';
        $sql .= ' employee.id_role = role.id AND';
        $sql .= ' employee.id_sto = sto.id AND';
        $sql .= ' employee.id_branch = branch.id AND';
        $sql .= ' employee.id_position = position.id';

        if (isset($data['first_name'])) {
            $sql .= ' AND employee.first_name LIKE \'%' . $data['first_name'] . '%\'';
        }
        if (isset($data['last_name'])) {
            $sql .= ' AND employee.last_name LIKE \'%' . $data['last_name'] . '%\'';
        }
        if (isset($data['father_name'])) {
            $sql .= ' AND employee.fathers_name LIKE \'%' . $data['father_name'] . '%\'';
        }
        if (isset($data['login'])) {
            $sql .= ' AND employee.login = \'' . $data['login'] . '\'';
        }
        if (isset($data['role'])) {
            $sql .= ' AND employee.id_role = ' . $data['role'];
        }
        if (isset($data['sto'])) {
            $sql .= ' AND employee.id_sto = ' . $data['sto'];
        }
        if (isset($data['branch'])) {
            $sql .= ' AND employee.id_branch = ' . $data['branch'];
        }
        if (isset($data['position'])) {
            $sql .= ' AND employee.id_position = ' . $data['position'];
        }

        $sql .= ';';

        $result = (array)$this->db->query($sql);

        for ($i = 0; $i < count($result); $i++) {
            unset($result[$i]['password']);
        }
        return $result;
    }

    /**
     * Метод моделі для отримання працівника
     * 
     * @param $id - id працівника
     * @return array
     */
    public function get_employee($id): array
    {
        $result = (array)$this->db->query('SELECT employee.id AS employee_id, employee.phone_number AS employee_pn, * FROM employee, role, sto, branch, position WHERE employee.id = ' . $id . ' AND employee.id_role = role.id AND employee.id_sto = sto.id AND employee.id_branch = branch.id AND employee.id_position = position.id;');
        if ($result) {
            unset($result[0]['password']);
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * Метод моделі для отримання імені працівника
     * 
     * @param $user_id - id працівника
     * @return string
     */
    public function get_account($user_id): string
    {
        $res = $this->db->query('SELECT first_name, last_name, fathers_name FROM employee WHERE id=' . $user_id . ';');
        if ($res) {
            return trim(implode(' ', $res[0]));
        }
        return '';
    }

    /**
     * Метод моделі для оновлення працівника
     * 
     * @param $data - дані
     * @return bool
     */
    public function update($data): bool
    {
        foreach ($data as $key => $value) {
            $data[$key] = $this->db->escape($value);
        }
        if ($data['pwd'] != '') {
            $sql = 'UPDATE employee SET id_role = ' . $data['role'] . ', id_sto = ' . $data['sto'] . ', id_branch = ' . $data['branch'] . ', id_position = ' . $data['position'] . ', first_name = \'' . $data['first_name'] . '\', last_name = \'' . $data['last_name'] . '\', fathers_name = \'' . $data['father_name'] . '\', phone_number = ' . $data['phone_number'] . ', login = \'' . $data['login'] . '\', password = \'' . $data['pwd'] . '\', tariff = ' . $data['tarif'] . ' WHERE id = ' . $data['user_id'] . ';';
        } else {
            $sql = 'UPDATE employee SET id_role = ' . $data['role'] . ', id_sto = ' . $data['sto'] . ', id_branch = ' . $data['branch'] . ', id_position = ' . $data['position'] . ', first_name = \'' . $data['first_name'] . '\', last_name = \'' . $data['last_name'] . '\', fathers_name = \'' . $data['father_name'] . '\', phone_number = ' . $data['phone_number'] . ', login = \'' . $data['login'] . '\', tariff = ' . $data['tarif'] . ' WHERE id = ' . $data['user_id'] . ';';
        }

        $this->db->query($sql);
        if ($this->db->last_error) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Метод моделі для видалення працівника
     * 
     * @param $id - id працівника
     * @return bool
     */
    public function delete($id): bool
    {
        $this->db->query('DELETE FROM employee WHERE id = ' . $id . ';');
        if ($this->db->last_error) {
            return false;
        } else {
            return true;
        }
    }
}
