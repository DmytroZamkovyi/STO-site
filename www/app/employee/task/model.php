<?

class employeeTaskModel extends Curswork\ActionClassModel
{
    /**
     * Метод моделі отримання завдань працівника
     * 
     * @param string $search - рядок для пошуку
     * @return array|bool
     */
    public function search($search)
    {
        $sql = 'SELECT task.id, car.make, car.model, car.license_plate, service.service_name FROM task, "order", service, car WHERE task.id_order = "order".id AND task.id_service = service.id AND "order".id_car = car.id AND (service.service_name LIKE \'%' . $this->db->escape($search) . '%\' OR service.description LIKE \'%' . $this->db->escape($search) . '%\' OR car.license_plate LIKE \'%' . $this->db->escape($search) . '%\' OR car.model LIKE \'%' . $this->db->escape($search) . '%\' OR car.make LIKE \'%' . $this->db->escape($search) . '%\') AND task.is_done = false AND task.id_employee = ' . $this->session->user_id . ';';
        $result = $this->db->query($sql);
        if ($this->db->last_error) {
            return false;
        } else {
            return $result;
        }
    }

    /**
     * Метод моделі відмітки завдання як виконаного
     * 
     * @param int $id - ідентифікатор завдання
     * @return bool
     */
    public function complete($id)
    {
        $sql = 'UPDATE task SET is_done = true WHERE id = ' . $this->db->escape($id) . ' AND id_employee = ' . $this->session->user_id . ';';
        $result = $this->db->query($sql);
        if ($this->db->last_error) {
            return false;
        } else {
            $this->check_order($id);
            return true;
        }
    }

    /**
     * Метод моделі перевірки виконання всіх завдань замовлення
     * 
     * @param int $task_id - ідентифікатор завдання
     * @return void
     */
    public function check_order($task_id)
    {
        $sql = 'SELECT id_order FROM task WHERE id = ' . $this->db->escape($task_id) . ';';
        $order_id = $this->db->query($sql);

        if (!$this->db->last_error) {
            $sql = 'SELECT id, is_done FROM task WHERE id_order = ' . $this->db->escape($order_id[0]['id_order']) . ';';
            $result = (array)$this->db->query($sql);
            if (!$this->db->last_error) {
                $is_done = true;
                foreach ($result as $task) {
                    if ($task['is_done'] == 'f') {
                        $is_done = false;
                    }
                }
                if ($is_done) {
                    $sql = 'UPDATE "order" SET completed = true WHERE id = ' . $this->db->escape($order_id[0]['id_order']) . ';';
                    $result = $this->db->query($sql);
                }
            }
        }
    }
}
