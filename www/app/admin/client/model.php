<?

class adminClientModel extends Curswork\ActionClassModel
{
    /**
     * Метод моделі для отримання списку клієнтів
     * 
     * @return array
     */
    public function create($data)
    {
        if ($data['discount'] == 0) {
            $data['discount'] = 'NULL';
        }
        $sql = 'INSERT INTO "client" (first_name, last_name, fathers_name, phone_number, discount) VALUES (\'' . $this->db->escape($data['first_name']) . '\', \'' . $this->db->escape($data['last_name']) . '\', \'' . $this->db->escape($data['father_name']) . '\', ' . $this->db->escape((string)$data['phone_number']) . ', ' . $this->db->escape((string)$data['discount']) . ');';
        $this->db->query($sql);
        return !$this->db->last_error;
    }

    /**
     * Метод моделі для пошуку працівників
     * 
     * @param $data - дані
     * @return array
     */
    public function search($data)
    {
        foreach ($data as $key => $value) {
            $data[$key] = $this->db->escape($value);
        }

        $sql = 'SELECT * FROM client';
        if (isset($data['first_name']) || isset($data['last_name']) || isset($data['father_name']) || isset($data['phone_number'])) {
            $sql .= ' WHERE ';
        }
        if (isset($data['first_name'])) {
            $sql .= 'client.first_name LIKE \'%' . $data['first_name'] . '%\'';
        }
        if (isset($data['last_name'])) {
            $sql .= ' AND client.last_name LIKE \'%' . $data['last_name'] . '%\'';
        }
        if (isset($data['father_name'])) {
            $sql .= ' AND client.fathers_name LIKE \'%' . $data['father_name'] . '%\'';
        }
        if (isset($data['phone_number'])) {
            $sql .= ' AND client.phone_number = ' . $data['phone_number'];
        }
        $sql .= ';';

        $result = (array)$this->db->query($sql);
        if ($result) {
            return $result;
        } else {
            return [];
        }
    }

    /**
     * Метод моделі для отримання даних акаунту клієнта
     * 
     * @param $id - id клієнта
     * @return array
     */
    public function get_client($id)
    {
        $sql = 'SELECT * FROM client WHERE id = ' . $this->db->escape($id) . ';';
        $result = (array)$this->db->query($sql);
        if ($result) {
            return $result[0];
        } else {
            return [];
        }
    }

    /**
     * Метод моделі для отримання списку замовлень клієнта
     * 
     * @param $id - id клієнта
     * @return array
     */
    public function get_order($id)
    {
        $sql = 'SELECT "order".id, service.service_name, "order".completed, "order".discount, "order".payment, car.make, car.model, car.license_plate, service.price, "order".create_date FROM task, "order", car, service WHERE task.id_order = "order".id AND "order".id_car = car.id AND task.id_service = service.id AND "order".id_client = 1;';
        $result = (array)$this->db->query($sql);
        if ($result) {
            $data = [];
            foreach ($result as $row) {
                if (isset($data[$row['id']])) {
                    $data[$row['id']]['services'] .= ', ' . $row['service_name'];
                    $data[$row['id']]['price'] += $row['price'];
                } else {
                    $data[$row['id']] = [
                        'id' => $row['id'],
                        'date' => $row['create_date'],
                        'completed' => $row['completed'],
                        'discount' => $row['discount'],
                        'payment' => $row['payment'],
                        'price' => $row['price'],
                        'car' => implode(' - ', [$row['make'], $row['model'], $row['license_plate']]),
                        'services' => $row['service_name']
                    ];
                }
            }
            return $data;
        } else {
            return [];
        }
    }

    /**
     * Метод моделі для оновлення даних клієнта
     * 
     * @param $data - дані
     * @return bool
     */
    public function update($data)
    {
        if ($data['discount'] == 0) {
            $data['discount'] = 'NULL';
        }

        if ($data['father_name'] == '') {
            $sql = 'UPDATE client SET first_name = \'' . $this->db->escape($data['first_name']) . '\', last_name = \'' . $this->db->escape($data['last_name']) . '\', fathers_name = NULL, phone_number = ' . $this->db->escape((string)$data['phone_number']) . ', discount = ' . $this->db->escape((string)$data['discount']) . ' WHERE id = ' . $this->db->escape($data['user_id']) . ';';
        } else {
            $sql = 'UPDATE client SET first_name = \'' . $this->db->escape($data['first_name']) . '\', last_name = \'' . $this->db->escape($data['last_name']) . '\', fathers_name = \'' . $this->db->escape($data['father_name']) . '\', phone_number = ' . $this->db->escape((string)$data['phone_number']) . ', discount = ' . $this->db->escape((string)$data['discount']) . ' WHERE id = ' . $this->db->escape($data['user_id']) . ';';
        }

        $this->db->query($sql);
        return !$this->db->last_error;
    }
}
