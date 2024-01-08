<?

class managerOrderModel extends Curswork\ActionClassModel
{
    /**
     * Метод моделі для отримання списку замовлень
     * 
     * @param $data - масив з даними для пошуку
     * @return array - масив з замовленнями
     */
    public function search($data)
    {

        $sql = 'SELECT "order".id, client.first_name, client.last_name, client.fathers_name, client.phone_number, car.make, car.model, car.license_plate, "order".create_date, "order".completed, "order".payment FROM "order", client, car WHERE "order".id_client = client.id AND "order".id_car = car.id';

        if (isset($data['first_name'])) {
            $sql .= ' AND client.first_name LIKE \'%' . $this->db->escape($data['first_name']) . '%\'';
        }
        if (isset($data['last_name'])) {
            $sql .= ' AND client.last_name LIKE \'%' . $this->db->escape($data['last_name']) . '%\'';
        }
        if (isset($data['father_name'])) {
            $sql .= ' AND client.fathers_name LIKE \'%' . $this->db->escape($data['father_name']) . '%\'';
        }
        if (isset($data['phone_number'])) {
            $sql .= ' AND client.phone_number = ' . $this->db->escape($data['phone_number']);
        }
        if (isset($data['car'])) {
            $sql .= ' AND car.license_plate LIKE \'%' . $this->db->escape($data['car']) . '%\'';
        }
        if (isset($data['start_date'])) {
            $sql .= ' AND "order".create_date >= \'' . $this->db->escape($data['start_date']) . '\'';
        }
        if (isset($data['end_date'])) {
            $sql .= ' AND "order".create_date <= \'' . $this->db->escape($data['end_date']) . '\'';
        }
        if (isset($data['complete'])) {
            $sql .= ' AND "order".completed = ' . $this->db->escape($data['complete']);
        }
        if (isset($data['pay'])) {
            $sql .= ' AND "order".payment = ' . $this->db->escape($data['pay']);
        }

        $sql .= ';';

        $result = $this->db->query($sql);

        if ($this->db->last_error) {
            return array();
        }

        for ($i = 0; $i < count($result); $i++) {
            $result[$i]['completed'] = $result[$i]['completed'] == 't';
            $result[$i]['payment'] = $result[$i]['payment'] == 't';
        }
        return $result;
    }

    /**
     * Метод моделі для отримання деталей замовлення
     * 
     * @param $id - id замовлення
     * @return bool|array - масив з деталями замовлення
     */
    public function get_order($id)
    {
        $sql = 'SELECT "order".create_date, "order".completed, "order".payment, "order".discount AS order_discount, car.make, car.model, car.license_plate, client.first_name, client.last_name, client.fathers_name, client.phone_number, client.discount FROM "order", car, client WHERE "order".id_car = car.id AND "order".id_client = client.id AND "order".id = ' . $this->db->escape($id) . ';';
        $order_result = $this->db->query($sql);
        if ($this->db->last_error) {
            return false;
        }
        if (count($order_result) == 0) {
            return false;
        }
        return $order_result[0];
    }

    /**
     * Метод моделі для отримання списку завдань по замовленню
     * 
     * @param $id - id замовлення
     * @return bool|array - масив з завданнями
     */
    public function get_task($id)
    {
        $sql = 'SELECT task.id, task.is_done, employee.id AS employee_id, employee.first_name, employee.last_name, employee.fathers_name, service.service_name, service.price FROM task, employee, service WHERE task.id_employee = employee.id AND task.id_service = service.id AND task.id_order = ' . $this->db->escape($id) . ';';
        $tasks_result = $this->db->query($sql);
        if ($this->db->last_error) {
            return false;
        }
        return $tasks_result;
    }

    /**
     * Метод моделі для отримання списку СТО
     * 
     * @param $employee_id - id працівника
     * @return bool|array - масив з СТО
     */
    public function get_sto($employee_id)
    {
        $sql = 'SELECT sto.id, sto.sto_name FROM employee, sto WHERE employee.id_sto = sto.id AND employee.id = ' . $this->db->escape($employee_id) . ';';
        $result = $this->db->query($sql);
        if ($this->db->last_error) {
            return false;
        }
        return $result[0];
    }

    /**
     * Метод моделі для видалення завдання по замовленню
     * 
     * @param $task_id - id завдання
     * @return bool - результат видалення
     */
    public function delete_task($task_id)
    {
        $sql = 'DELETE FROM task WHERE id = ' . $this->db->escape($task_id) . ';';
        $this->db->query($sql);
        if ($this->db->last_error) {
            return false;
        }
        return true;
    }

    /**
     * Метод моделі для отримання списку працівників СТО
     * 
     * @param $sto_name - назва СТО
     * @return bool|array - масив з працівниками
     */
    public function get_employee_list($sto_name)
    {
        $sql = 'SELECT employee.id, employee.first_name, employee.last_name, employee.fathers_name FROM employee, sto WHERE employee.id_sto = sto.id AND employee.id_role = 3 AND sto.sto_name = \'' . $this->db->escape($sto_name) . '\';';
        $result = $this->db->query($sql);
        if ($this->db->last_error) {
            return false;
        }
        return $result;
    }

    /**
     * Метод моделі для отримання списку послуг
     * 
     * @return bool|array - масив з послугами
     */
    public function get_task_list()
    {
        $sql = 'SELECT service.service_name FROM service;';
        $result = $this->db->query($sql);
        if ($this->db->last_error) {
            return false;
        }
        return $result;
    }

    /**
     * Метод моделі для додавання завдання по замовленню
     * 
     * @param $data - масив з даними для додавання
     * @return bool - результат додавання
     */
    public function add_task($data)
    {
        $sql = 'SELECT id FROM employee WHERE first_name = \'' . $this->db->escape($data['name'][0]) . '\'';
        if (isset($data['name'][1])) {
            $sql .= ' AND last_name = \'' . $this->db->escape($data['name'][1]) . '\'';
        }
        if (isset($data['name'][2])) {
            $sql .= ' AND fathers_name = \'' . $this->db->escape($data['name'][2]) . '\'';
        }
        $id_employee = $this->db->query($sql);
        if ($this->db->last_error) {
            return false;
        }
        if (count($id_employee) == 0) {
            return false;
        }
        $id_employee = $id_employee[0]['id'];

        $sql = 'SELECT id FROM service WHERE service_name = \'' . $this->db->escape($data['task']) . '\';';
        $id_service = $this->db->query($sql);
        if ($this->db->last_error) {
            return false;
        }
        if (count($id_service) == 0) {
            return false;
        }
        $id_service = $id_service[0]['id'];
        
        $sql = 'INSERT INTO task (id_order, id_service, id_employee, is_done) VALUES ('. $this->db->escape($data['order_id']) . ', ' . $id_service .', '. $id_employee .', false);';
        $this->db->query($sql);
        if ($this->db->last_error) {
            return false;
        }
        return true;
    }

    /**
     * Метод моделі для зміни деталей замовлення
     * 
     * @param $data - масив з даними для зміни
     * @return bool - результат зміни
     */
    public function update($data)
    {
        if ($data['discount'] < 0 || $data['discount'] > 100) {
            return false;
        }

        if ($data['discount'] == 0) {
            $data['discount'] = 'NULL';
        }

        $sql = 'UPDATE "order" SET discount = '. $this->db->escape($data['discount']) . ' WHERE id = '. $this->db->escape($data['order_id']) .';';

        $this->db->query($sql);
        if ($this->db->last_error) {
            return false;
        }
        return true;
    }

    /**
     * Метод моделі для втдалення замовлення
     * 
     * @param $id - id замовлення
     * @return void
     */
    public function delete($id)
    {
        
        $sql = 'DELETE FROM task WHERE id_order = ' . $this->db->escape($id) . ';';
        $this->db->query($sql);

        $sql = 'DELETE FROM "order" WHERE id = ' . $this->db->escape($id) . ';';
        $this->db->query($sql);
    }

    /**
     * Метод моделі для отримання списку СТО
     * 
     * @return bool|array - масив з СТО
     */
    public function get_sto_list()
    {
        $sql = 'SELECT id, sto_name FROM sto;';
        $result = $this->db->query($sql);
        if ($this->db->last_error) {
            return false;
        }
        return $result;
    }

    /**
     * Метод моделі для отримання списку послуг
     * 
     * @param $search - дані для пошуку
     * @return bool|array - масив з послугами
     */
    public function search_client($search)
    {
        $sql = 'SELECT * FROM client WHERE first_name LIKE \'%'. $this->db->escape($search) . '%\' OR last_name LIKE \'%'. $this->db->escape($search) . '%\' OR fathers_name LIKE \'%'. $this->db->escape($search) . '%\';';
        $result = $this->db->query($sql);
        if ($this->db->last_error) {
            return false;
        }
        if (count($result) != 1) {
            return false;
        }
        return $result;
    }

    /**
     * Метод моделі для пошуку клієнта по номеру телефона
     * 
     * @param $phone - номер телефона
     * @return bool|array - масив інформації про клієнта
     */
    public function search_client_by_phone($phone)
    {
        $sql = 'SELECT * FROM client WHERE phone_number = ' . $this->db->escape($phone) . ';';
        $result = $this->db->query($sql);
        if ($this->db->last_error) {
            return false;
        }
        if (count($result) != 1) {
            return false;
        }
        return $result;
    }

    /**
     * Метод моделі для додавання клієнта
     * 
     * @param $data - масив з даними для додавання
     * @return bool - результат додавання
     */
    public function add_client($data)
    {
        if (!isset($data['fathers_name'])) {
            $sql = 'INSERT INTO client (first_name, last_name, phone_number) VALUES (\'' . $this->db->escape($data['first_name']) . '\', \'' . $this->db->escape($data['last_name']) . '\', ' . $this->db->escape($data['phone_number']) . ');';
        } else {
            $sql = 'INSERT INTO client (first_name, last_name, fathers_name, phone_number) VALUES (\'' . $this->db->escape($data['first_name']) . '\', \'' . $this->db->escape($data['last_name']) . '\', \'' . $this->db->escape((string)$data['fathers_name']) . '\', ' . $this->db->escape($data['phone_number']) . ');';
        }
        $this->db->query($sql);
        if ($this->db->last_error) {
            return false;
        }
        return true;
    }

    /**
     * Метод моделі для пошуку автомобіля по номеру
     * 
     * @param $search - номер автомобіля
     * @return bool|array - масив з інформацією про автомобіль
     */
    public function search_car($search)
    {
        $sql = 'SELECT * FROM car WHERE license_plate = \'' . $this->db->escape($search) . '\';';
        $result = $this->db->query($sql);
        if ($this->db->last_error) {
            return false;
        }
        if (count($result) != 1) {
            return false;
        }
        return $result;
    }

    /**
     * Метод моделі для додавання автомобіля
     * 
     * @param $data - масив з даними для додавання
     * @return bool - результат додавання
     */
    public function add_car($data)
    {
        if (!isset($data['year']) && !isset($data['description'])) {
            $sql = 'INSERT INTO car (make, model, license_plate) VALUES (\'' . $this->db->escape($data['make']) . '\', \'' . $this->db->escape($data['model']) . '\', \'' . $this->db->escape($data['license_plate']) . '\');';
        } else {
            if (!isset($data['year'])) {
                $sql = 'INSERT INTO car (make, model, license_plate) VALUES (\'' . $this->db->escape($data['make']) . '\', \'' . $this->db->escape($data['model']) . '\', \'' . $this->db->escape($data['license_plate']) . '\');';
            } else {
                $sql = 'INSERT INTO car (make, model, license_plate, car_year) VALUES (\'' . $this->db->escape($data['make']) . '\', \'' . $this->db->escape($data['model']) . '\', \'' . $this->db->escape($data['license_plate']) . '\', ' . $this->db->escape($data['year']) . ');';
            }
    
            if (!isset($data['description'])) {
                $sql = 'INSERT INTO car (make, model, license_plate) VALUES (\'' . $this->db->escape($data['make']) . '\', \'' . $this->db->escape($data['model']) . '\', \'' . $this->db->escape($data['license_plate']) . '\');';
            } else {
                $sql = 'INSERT INTO car (make, model, license_plate, description) VALUES (\'' . $this->db->escape($data['make']) . '\', \'' . $this->db->escape($data['model']) . '\', \'' . $this->db->escape($data['license_plate']) . '\', \'' . $this->db->escape($data['description']) . '\');';
            }
        }

        if (isset($data['year']) && isset($data['description'])) {
            $sql = 'INSERT INTO car (make, model, license_plate, car_year, description) VALUES (\'' . $this->db->escape($data['make']) . '\', \'' . $this->db->escape($data['model']) . '\', \'' . $this->db->escape($data['license_plate']) . '\', ' . $this->db->escape($data['year']) . ', \'' . $this->db->escape($data['description']) . '\');';
        }

        $this->db->query($sql);
        if ($this->db->last_error) {
            return false;
        }
        return true;
    }

    /**
     * Метод моделі для отримання списку автомобілів
     * 
     * @param $search - дані для пошуку
     * @return bool|array - масив з автомобілями
     */
    public function create_order($data)
    {
        $sql = 'INSERT INTO "order" (id_client, id_car, create_date, completed, payment) VALUES (' . $this->db->escape($data['client_id']) . ', ' . $this->db->escape($data['car_id']) . ', now(), false, false) RETURNING id;';
        $result = $this->db->query($sql);
        if ($this->db->last_error) {
            return false;
        }
        return $result;
    }
}
