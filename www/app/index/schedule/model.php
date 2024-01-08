<?

class indexScheduleModel extends Curswork\ActionClassModel
{
    /**
     * Метод моделі для отримання розкладу користувача
     * 
     * @param $mouth - місяць
     * @param $year - рік
     * @return string
     */
    public function get_schedule($mouth, $year)
    {
        $schedule = $this->db->query('SELECT schedule_month, schedule_year, schedule.schedule FROM schedule, schedule_employee WHERE schedule.id = schedule_employee.id_schedule AND id_employee = ' . $this->session->user_id . ' AND schedule_month = ' . $mouth . ' AND schedule_year = ' . $year . ';');
        if ($schedule) {
            return json_encode($schedule[0]);
        }
        return "{}";
    }
}
