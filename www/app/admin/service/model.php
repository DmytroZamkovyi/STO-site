<?
class adminServiceModel extends Curswork\ActionClassModel
{
    /**
     * Метод моделі для додавання нової послуги
     * 
     * @param $data - дані для додаання
     * @return bool
     */
    public function create($data)
    {
        $sql = 'INSERT INTO "service" (service_name, description, price) VALUES (\'' . $this->db->escape($data['name']) . '\', \'' . $this->db->escape($data['description']) . '\', ' . $this->db->escape($data['price']) . ');';
        var_dump($sql);
        $this->db->query($sql);

        if ($this->db->last_error) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Метод моделі для пошуку послуг
     * 
     * @param $data - дані для пошуку
     * @return mixed
     */
    public function search($data)
    {
        $sql = 'SELECT * FROM "service" WHERE service_name LIKE \'%' . $this->db->escape($data['search']) . '%\' OR description LIKE \'%' . $this->db->escape($data['search']) . '%\';';
        $result = $this->db->query($sql);

        if ($this->db->last_error) {
            return false;
        } else {
            return $result;
        }
    }

    /**
     * Метод моделі для отримання всіх послуг
     * 
     * @param $id - id послуги
     * @return mixed
     */
    public function get_service($id)
    {
        $sql = 'SELECT * FROM "service" WHERE id = ' . $this->db->escape($id) . ';';
        $result = $this->db->query($sql);

        if ($this->db->last_error) {
            return false;
        } else {
            return $result[0];
        }
    }

    /**
     * Метод моделі для видалення послуги
     * 
     * @param $data - дані для видалення
     * @return mixed
     */
    public function delete($data)
    {
        $sql = 'DELETE FROM "service" WHERE id = ' . $this->db->escape($data['service_id']) . ';';
        $this->db->query($sql);
    }

    /**
     * Метод моделі для оновлення послуги
     * 
     * @param $data - дані для оновлення
     * @return mixed
     */
    public function update($data)
    {
        $sql = 'UPDATE "service" SET service_name = \'' . $this->db->escape($data['name']) . '\', description = \'' . $this->db->escape($data['description']) . '\', price = ' . $this->db->escape($data['price']) . ' WHERE id = ' . $this->db->escape($data['service_id']) . ';';
        $this->db->query($sql);
    }
}
