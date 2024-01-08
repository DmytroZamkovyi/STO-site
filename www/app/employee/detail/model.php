<?

class employeeDetailModel extends Curswork\ActionClassModel
{
    /**
     * Метод моделі для пошуку серед деталей
     * 
     * @param string $search - пошуковий запит
     * @return array
     */
    public function get_details($search)
    {
        $sql = 'SELECT * FROM "detail" WHERE detail_name LIKE \'%' . $this->db->escape($search) . '%\';';
        return $this->db->query($sql);
    }

    /**
     * Метод моделі для отримання даних деталі
     * 
     * @param string $search - пошуковий запит
     * @return array
     */
    public function get_detail($search)
    {
        $sql = 'SELECT * FROM "detail" WHERE detail_name = \'' . $this->db->escape($search) . '\';';
        $result = $this->db->query($sql);
        if ($this->db->last_error) {
            return false;
        } else {
            return $result[0];
        }
    }
}
