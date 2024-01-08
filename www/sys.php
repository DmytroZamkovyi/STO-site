<?

namespace Curswork;

/**
 * Class ActionClassModel
 * 
 * Клас для роботи з моделлю
 * 
 * @package Curswork
 * @property ConfigClass $config - об'єкт з налаштуваннями
 * @property DBClass $db - об'єкт для роботи з базою даних
 * @property SessionClass $session - об'єкт для роботи з сесією
 * @property AuthClass $auth - об'єкт для роботи з авторизацією
 * @property RequestClass $request - об'єкт з даними запиту
 * @property ResponseClass $response - об'єкт для роботи з відповіддю
 */
class ActionClassModel
{
    public ?AuthClass $auth = null;
    public ?DBClass $db = null;
    public ?SessionClass $session = null;
    public ?ConfigClass $config = null;
    public ?RequestClass $request = null;
    public ?ResponseClass $response = null;
}

/**
 * Class ActionClass
 * 
 * Основний клас для роботи з системою
 * 
 * @package Curswork
 * @property ConfigClass $config - об'єкт з налаштуваннями
 * @property DBClass $db - об'єкт для роботи з базою даних
 * @property RequestClass $request - об'єкт з даними запиту
 * @property SessionClass $session - об'єкт для роботи з сесією
 * @property AuthClass $auth - об'єкт для роботи з авторизацією
 * @property ResponseClass $response - об'єкт для роботи з відповіддю
 * @property ViewClass $view - об'єкт для роботи з відображенням
 * @property string $module - назва модуля, перший рівень шляху
 * @property string $class - назва класу, другий рівень шляху
 * @property string $action - назва методу, третій рівень шляху
 * @property string|null $params - параметри, четвертий рівень шляху
 * @property string $dir - шлях до папки з поточним класом
 * @property object|null $model - об'єкт моделі
 * @method string load(string $module, string $class, string $action, ?string $params = '', array &$data = array(), bool $template = true) - функція для підключення контроллера
 * @method void load_model(string $module, string $class) - функція для підключення моделі
 * @method string extract_route(string $route) - функція для розбиття шляху на модуль, клас, метод та параметри
 * @method string start_route(string $module, string $class, string $action, ?string $params, array &$data = array(), bool $internal = false) - функція для запуску модуля, класу, методу з параметрами
 * @method void start() - точка входу в систему
 * @method void __construct(string $module, string $class, string $action, ?string $params, string $dir) - конструктор
 */
class ActionClass
{
    private static ?ConfigClass $_config = null;
    private static ?DBClass $_db = null;
    private static ?RequestClass $_request = null;
    private static ?SessionClass $_session = null;
    private static ?AuthClass $_auth = null;
    private static ?ResponseClass $_response = null;

    public ?ConfigClass $config = null;
    public ?DBClass $db = null;
    public ?RequestClass $request = null;
    public ?SessionClass $session = null;
    public ?AuthClass $auth = null;
    public ?ResponseClass $response = null;
    public ?ViewClass $view = null;

    public string $module = '';
    public string $class = '';
    public string $action = '';
    public ?string $params = null;

    public string $dir = '';
    public ?object $model = null;

    /**
     * ActionClass constructor.
     * 
     * @param string $module - назва модуля
     * @param string $class - назва класу
     * @param string $action - назва методу
     * @param string|null $params - параметри
     * @param string $dir - шлях до папки з поточним класом
     */
    public function __construct(string $module, string $class, string $action, ?string $params, string $dir)
    {
        $this->module = $module;
        $this->class = $class;
        $this->action = $action;
        $this->params = $params;
        $this->dir = $dir;

        $this->config = ActionClass::$_config;
        $this->db = ActionClass::$_db;

        if (ActionClass::$_request === null) {
            ActionClass::$_request = new RequestClass($this);
        }
        $this->request = ActionClass::$_request;

        if (ActionClass::$_session === null) {
            ActionClass::$_session = new SessionClass($this);
        }
        $this->session = ActionClass::$_session;

        if (ActionClass::$_auth === null) {
            ActionClass::$_auth = new AuthClass($this);
        }
        $this->auth = ActionClass::$_auth;

        if (ActionClass::$_response === null) {
            ActionClass::$_response = new ResponseClass($this);
        }
        $this->response = ActionClass::$_response;

        $this->view = new ViewClass($this);
    }

    /**
     * Точка входу в систему
     * 
     * @return void
     */
    public static function start()
    {
        if (ActionClass::$_config === null) {
            ActionClass::$_config = new ConfigClass('/config.php');
        }

        if (ActionClass::$_db === null) {
            ActionClass::$_db = new DBClass(ActionClass::$_config);
        }

        if (isset($_SERVER['REQUEST_URI'])) {
            $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
            $uri = rtrim($uri, '/');
            $uri = urldecode($uri);
            list($module, $class, $action, $params) = ActionClass::extract_route($uri);
        } else {
            exit('Variable $_SERVER[\'REQUEST_URI\'] not found');
        }
        echo ActionClass::start_route($module, $class, $action, $params) ?? '';
    }

    /**
     * Функція для розбиття шляху на модуль, клас, метод та параметри
     * 
     * @param string $route - шлях
     * @return array - масив з модулем, класом, методом та параметрами
     */
    public static function extract_route(string $route)
    {
        $load = explode('/', $route, 5);
        if (isset($load[1]) && strlen($load[1]) > 0) {
            $module = $load[1];
        } else {
            $module = 'index';
        }
        if (isset($load[2]) && strlen($load[2]) > 0) {
            $class = $load[2];
        } else {
            $class = 'index';
        }
        if (isset($load[3]) && strlen($load[3]) > 0) {
            $action = $load[3];
        } else {
            $action = 'index';
        }

        if (isset($load[4]) && strlen($load[4]) > 0) {
            $params = $load[4];
        } else {
            $params = '';
        }
        return [$module, $class, $action, $params];
    }

    /**
     * Функція для запуску модуля, класу, методу з параметрами
     * 
     * @param string $module - назва модуля
     * @param string $class - назва класу
     * @param string $action - назва методу
     * @param string|null $params - параметри
     * @param array $data - дані для виводу
     * @param bool $internal - чи внутрішній запит
     * @return string - результат виконання
     */
    public static function start_route(string $module, string $class, string $action, ?string $params, array &$data = array(), bool $internal = false)
    {
        if (!(bool)preg_match('/^[a-z0-9_]+$/', $module) || !(bool)preg_match('/^[a-z0-9_]+$/', $class) || !(bool)preg_match('/^[a-z0-9_]+$/', $action)) {
            exit;
        }

        $dir = __DIR__ . '/app/' . $module . '/' . $class;
        $file = $dir . '/action.php';
        $is_404 = false;
        $is_login_error = false;

        if (file_exists($file)) {
            require_once $file;
            $className = $module . ucfirst($class);
            if (class_exists($className)) {
                $className = new $className($module, $class, $action, $params, $dir);
                if ($className->auth->get_access() || $internal) {
                    $actionName = 'action_' . $action;
                    if (method_exists($className, $actionName)) {
                        $className->load_model($module, $class);
                        return $className->$actionName($params, $data);
                    } else {
                        $is_404 = true;
                    }
                } else {
                    $is_login_error = true;
                }
            } else {
                $is_404 = true;
            }
        } else {
            $is_404 = true;
        }

        if ($is_404) {
            header('HTTP/1.1 404 Not Found');
            $dir = __DIR__ . '/app/index/index';
            $file = $dir . '/action.php';
            require_once $file;
            $className = new \indexIndex('index', 'index', '404', '', $dir);
            return $className->action_404('', $data);
        }

        if ($is_login_error) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
                $className = new \indexIndex($module, $class, $action, $params, $dir);
                $className->load_model($module, $class);
                $className->auth();
                return '';
            }
            header('HTTP/1.1 401 Unauthorized');
            $dir = __DIR__ . '/app/index/index';
            $file = $dir . '/action.php';
            require_once $file;
            $className = new \indexIndex('index', 'index', 'login', '', $dir);
            return $className->action_login('', $data);
        }
        return '';
    }

    /**
     * Функція для підключення моделі
     * 
     * @param string $module - назва модуля
     * @param string $class - назва класу
     * @return void
     */
    public function load_model(string $module, string $class)
    {
        $class_name = get_class($this);
        $file = $this->dir . '/model.php';
        if (file_exists($file)) {
            require_once $file;
            $class_name .= 'Model';
            if (class_exists($class_name)) {
                $this->model = new $class_name($module, $class);
            } else {
                $this->model = new \stdClass();
            }
        } else {
            $this->model = new \stdClass();
        }

        $this->model->auth = $this->auth;
        $this->model->db = $this->db;
        $this->model->session = $this->session;
        $this->model->config = $this->config;
        $this->model->request = $this->request;
        $this->model->response = $this->response;
    }

    /**
     * Функція для підключення контроллера
     * 
     * @param string $module - назва модуля
     * @param string $class - назва класу
     * @param string $action - назва методу
     * @param string|null $params - параметри
     * @param array $data - дані для виводу
     * @param bool $template - чи виводити шаблон
     * @return string - результат виконання
     */
    public function load(string $module, string $class, string $action, ?string $params = '', array &$data = array(), bool $template = true)
    {
        if ($template) {
            ob_start();
        }
        $res = ActionClass::start_route($module, $class, $action, $params, $data, true);
        if ($template) {
            return ob_get_clean();
        }
        return $res;
    }
}


/**
 * Class ConfigClass
 * 
 * Клас для роботи з налаштуваннями
 * 
 * @package Curswork
 * @method void __construct(string $filename) - конструктор
 * @method string __get(string $name) - функція для отримання значення елементу
 * @method bool __isset(string $name) - функція для перевірки наявності елементу
 */
class ConfigClass
{
    private array $_config = [];

    /**
     * ConfigClass constructor.
     * 
     * @param string $filename - шлях до файлу з налаштуваннями
     */
    public function __construct($filename)
    {
        // Якщо php не зміг підключити файл - виникає помилка
        require_once __DIR__ . $filename;
        $this->_config = get_defined_vars();
        unset($this->_config['filename']);

        if (isset($this->timeZone)) {
            date_default_timezone_set($this->timeZone);
        }

        if (isset($this->timeExecution)) {
            set_time_limit($this->timeExecution);
        }
    }

    /**
     * Функція для отримання значення елементу
     * 
     * @param string $name - ключ елементу
     * @return mixed|null - значення елементу
     */
    public function __get(string $name)
    {
        return $this->_config[$name];
    }

    /**
     * Функція для перевірки наявності елементу
     * 
     * @param string $name - ключ елементу
     * @return bool - чи існує елемент
     */
    public function __isset(string $name)
    {
        return isset($this->_config[$name]);
    }
}


/**
 * Class DBClass
 * 
 * Клас для роботи з базою даних
 * 
 * @package Curswork
 * @property string $last_sql - останній запит
 * @property bool $last_error - чи була помилка
 * @property string $last_error_string - остання помилка
 * @property int $count_rows - кількість рядків
 * @method void __construct(ConfigClass $config) - конструктор
 * @method void __destruct() - деструктор
 * @method array|null query(string $query) - функція для виконання запиту до бази даних
 * @method string escape(string $data) - функція для екранування даних
 */
class DBClass
{
    private $_db = null;

    private string $_dbhost;
    private string $_dbname;
    private string $_dbuser;
    private string $_dbpswd;
    private string $_dbport;

    public string $last_sql = '';
    public bool $last_error = false;
    public string $last_error_string = '';
    public int $count_rows = 0;

    /**
     * DBClass constructor.
     * 
     * @param ConfigClass $config - об'єкт з налаштуваннями
     */
    public function __construct(ConfigClass &$config)
    {
        if (isset($config->dbhost)) {
            $this->_dbhost = $config->dbhost;
        } else {
            $this->_dbhost = 'localhost';
        }

        if (isset($config->dbname)) {
            $this->_dbname = $config->dbname;
        } else {
            exit('Не вказано назву бази даних');
        }

        if (isset($config->dbuser)) {
            $this->_dbuser = $config->dbuser;
        } else {
            $this->_dbuser = 'postgres';
        }

        if (isset($config->dbpswd)) {
            $this->_dbpswd = $config->dbpswd;
        } else {
            exit('Не вказано пароль користувача бази даних');
        }

        if (isset($config->dbport)) {
            $this->_dbport = $config->dbport;
        } else {
            $this->_dbport = '5432';
        }
    }

    /**
     * DBClass destructor.
     * 
     * @return void
     */
    public function __destruct()
    {
        if ($this->_db !== null) {
            register_shutdown_function(function ($db) {
                pg_close($db);
            }, $this->_db);
        }
    }

    /**
     * Функція для підключення до бази даних
     * 
     * @return void
     * @throws \RuntimeException - помилка підключення до бази даних
     */
    private function connect()
    {
        $connect_string = 'host=\'' . addslashes($this->_dbhost) . '\' ';
        $connect_string .= 'port=\'' . addslashes($this->_dbport) . '\' ';
        $connect_string .= 'user=\'' . addslashes($this->_dbuser) . '\' ';
        $connect_string .= 'dbname=\'' . addslashes($this->_dbname) . '\' ';
        $connect_string .= 'password=\'' . addslashes($this->_dbpswd) . '\' ';
        $connect_string .= 'options=\'--client_encoding=UTF8\' ';
        $this->_db = pg_connect($connect_string);
        if ($this->_db === false) {
            throw new \RuntimeException('Unable to connect to db');
        }
    }

    /**
     * Функція для виконання запиту до бази даних
     * 
     * @param string $query - запит
     * @return array|null - результат запиту
     */
    public function query(string $query)
    {
        if ($this->_db == null) {
            $this->connect();
        }
        $this->last_sql = $query;
        $res = @pg_query($this->_db, $query);
        if ($res === false) {
            $this->last_error = true;
            $this->last_error_string = pg_last_error($this->_db);
            return null;
        }
        $this->last_error = false;
        $this->last_error_string = '';
        $count_rows = pg_num_rows($res);
        if ($count_rows == 0) {
            $result = array();
        } else {
            $result = pg_fetch_all($res);
            if ($result === false) {
                $result = array();
            }
        }
        pg_free_result($res);
        return $result;
    }

    /**
     * Функція для екранування даних
     * 
     * @param string $data - дані
     * @return string - екрановані дані
     */
    public function escape(string $data)
    {
        if ($this->_db === null) {
            $this->connect();
        }
        return pg_escape_string($this->_db, $data);
    }
}


/**
 * Class AuthClass
 * 
 * Клас для роботи з авторизацією
 * 
 * @package Curswork
 * @method void __construct(ActionClass $action) - конструктор
 * @method bool get_access() - функція для перевірки доступу
 */
class AuthClass
{
    private ActionClass $_action;

    /**
     * AuthClass constructor.
     * 
     * @param ActionClass $action
     */
    public function __construct(ActionClass &$action)
    {
        $this->_action = $action;
    }

    /**
     * Функція для перевірки доступу
     * 
     * @return void
     */
    public function get_access()
    {
        if ($this->_action->session->system) {
            return true;
        }
        if ($this->_action->session->user_id != null) {
            $res = $this->_action->db->query('SELECT role.role_name as role FROM employee, role WHERE id_role = role.id AND employee.id = ' . $this->_action->session->user_id . ';');
            if ($res) {
                if ($res[0]['role'] == $this->_action->module || $this->_action->module == 'index') {
                    return true;
                }
                return false;
            }
            return false;
        }
        return false;
    }
}


/**
 * Class SessionClass
 * 
 * Клас для роботи з сесією
 * 
 * @package Curswork
 * @property string $session - ключ сесії
 * @property int|null $user_id - ідентифікатор користувача
 * @method void __construct(ActionClass $action) - конструктор
 * @method array load() - функція для завантаження сесії
 * @method void save() - функція для збереження сесії
 * @method void __set(string $key, $value) - функція для встановлення значення елементу
 * @method mixed|null __get(string $key) - функція для отримання значення елементу
 * @method bool __isset(string $key) - функція для перевірки наявності елементу
 * @method void __unset(string $key) - функція для видалення елементу
 * @method void __destruct() - деструктор
 */
class SessionClass
{
    public const ON_YEAR = 60 * 60 * 24 * 365;

    private ?ActionClass $_action = null;
    private string $_session = '';
    private ?int $_session_id = null;
    private array $_data = array();
    private ?int $_user_id = null;
    private array $_changed = array();

    /**
     * SessionClass constructor.
     * 
     * @package Curswork
     * @param ActionClass $action
     */
    function __construct(ActionClass &$action)
    {
        $this->_action = $action;
        $key = $action->config->sessionKey;
        $session = $action->request->cookie->{$key} ?? '';
        if (!(bool)preg_match('/^[a-z0-9]{128}$/', $session)) {
            $session = hash('sha512', $this->_action->config->salt . $action->request->ip . microtime());
            $action->request->cookie->{$key} = $session;
            setcookie($key, $session, time() + SessionClass::ON_YEAR, '/');
        }

        $this->_session = $session;
        $this->_data = $this->load();
    }

    /**
     * SessionClass destructor.
     * 
     * @return void
     */
    public function __destruct()
    {
        $this->save();
    }

    /**
     * Функція для завантаження сесії
     * 
     * @return array - дані сесії
     */
    public function load()
    {
        $sql = 'SELECT * FROM "session" WHERE session_key = \'' . $this->_session . '\';';
        $query = (array)$this->_action->db->query($sql);
        if (count($query) > 0) {
            $this->_session_id = (int)$query[0]['id'];
            $this->_user_id = $query[0]['id_employee'] === null ? null : (int)$query[0]['id_employee'];
            $data = unserialize($query[0]['data']);
        } else {
            $sql = 'INSERT INTO "session" (session_key, session_create_date, session_last_date, "data") VALUES (\'' . $this->_session . '\', now(), now(), \'' . serialize(array()) . '\') RETURNING id;';
            $query = (array)$this->_action->db->query($sql);
            $this->_session_id = (int)$query[0]['id'];
            $this->_user_id = null;
            $data = array();
        }
        return $data;
    }

    /**
     * Функція для збереження сесії
     * 
     * @return void
     */
    public function save()
    {
        if (count($this->_changed) > 0) {
            $user_id = (string)$this->_user_id;
            $data = $this->_data;
            unset($data['user_id']);
            $json = $this->_action->db->escape(serialize($data));
            $sql = 'UPDATE "session" SET "data" = \'' . $json . '\', session_last_date = now() WHERE session_key = \'' . $this->_session . '\';';
            $this->_action->db->query($sql);
        } else {
            $sql = 'UPDATE "session" SET session_last_date = now() WHERE session_key = \'' . $this->_session . '\';';
            $this->_action->db->query($sql);
        }
    }

    /**
     * Функція для встановлення значення елементу
     * 
     * @param string $key - ключ елементу
     * @param mixed $value - значення елементу
     */
    public function __set(string $key, $value)
    {
        if (!in_array($key, $this->_changed)) {
            $this->_changed[] = $key;
        }
        $this->_data[$key] = $value;
    }

    /**
     * Функція для отримання значення елементу
     * 
     * @param string $key - ключ елементу
     * @return mixed|null - значення елементу
     */
    public function __get(string $key)
    {
        if (isset($this->_data[$key])) {
            return $this->_data[$key];
        }

        if ($key == 'user_id') {
            return $this->_user_id;
        }

        if ($key == 'session') {
            return $this->_session;
        }

        return null;
    }

    /**
     * Функція для перевірки наявності елементу
     * 
     * @param string $key - ключ елементу
     * @return bool - чи існує елемент
     */
    public function __isset(string $key)
    {
        return isset($this->_data[$key]);
    }

    /**
     * Функція для видалення елементу
     * 
     * Фукнкція встановлює значення елементу в null.
     * 
     * @param string $key - ключ елементу
     * @return void
     */
    public function __unset(string $key)
    {
        if (!in_array($key, $this->_changed)) {
            $this->_changed[] = $key;
        }
        unset($this->_data[$key]);
    }
}


/**
 * Class ParameterClass
 * 
 * Клас для роботи з параметрами
 * 
 * @package Curswork
 * @property int $count - кількість елементів
 * @method void __construct(array $data) - конструктор
 * @method void __set(string $key, $value) - функція для встановлення значення елементу
 * @method mixed|null __get(string $key) - функція для отримання значення елементу
 * @method bool __isset(string $key) - функція для перевірки наявності елементу
 * @method void __unset(string $key) - функція для видалення елементу
 */
class ParameterClass
{
    private array $_data = array();
    private int $_count = 0;

    /**
     * ParameterClass constructor.
     * 
     * @param array $data - дані
     */
    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            $this->_data[$key] = $value;
            $this->_count++;
        }
    }

    /**
     * Функція для отримання кількості елементів
     * 
     * @return int - кількість елементів
     */
    public function count()
    {
        return $this->_count;
    }

    /**
     * Функція для встановлення значення елементу
     * 
     * @param string $key - ключ елементу
     * @param mixed $value - значення елементу
     */
    public function __set(string $key, $value)
    {
        if (isset($this->_data[$key])) {
            $this->_data[$key] = $value;
        } else {
            $this->_data[$key] = $value;
            $this->_count++;
        }
    }

    /**
     * Функція для отримання значення елементу
     * 
     * @param string $key - ключ елементу
     * @return mixed|null - значення елементу
     */
    public function __get(string $key)
    {
        return $this->_data[$key] ?? null;
    }

    /**
     * Функція для перевірки наявності елементу
     * 
     * @param string $key - ключ елементу
     * @return bool - чи існує елемент
     */
    public function __isset(string $key)
    {
        return isset($this->_data[$key]);
    }

    /**
     * Функція для видалення елементу
     * 
     * Фукнкція встановлює значення елементу в null.
     * 
     * @param string $key - ключ елементу
     */
    public function __unset(string $key)
    {
        if (isset($this->_data[$key])) {
            $this->_data[$key] = null;
            $this->_count--;
        }
    }
}


/**
 * Class RequestClass
 * 
 * Клас для роботи з даними запиту
 * 
 * @package Curswork
 * @property ParameterClass $get - параметри GET
 * @property ParameterClass $post - параметри POST
 * @property ParameterClass $cookie - параметри COOKIE
 * @property string $ip - IP-адреса
 * @method void __construct(ActionClass $action) - конструктор
 */
class RequestClass
{
    private ?ActionClass $_action = null;

    public ParameterClass $get;
    public ParameterClass $post;
    public ParameterClass $cookie;

    public string $ip = '';

    /**
     * RequestClass constructor.
     * 
     * @param ActionClass $action
     */
    public function __construct(ActionClass &$action)
    {
        $this->_action = $action;

        $this->cookie = new ParameterClass($_COOKIE);
        $this->get = new ParameterClass($_GET);
        $this->post = new ParameterClass($_POST);

        if (isset($_SERVER['REMOTE_ADDR'])) {
            $this->ip = $_SERVER['REMOTE_ADDR'];
        }
    }
}


/**
 * Class ResponseClass
 * 
 * Клас для роботи з відповіддю
 * 
 * @package Curswork
 * @property array $header - заголовки
 * @method void __construct(ActionClass $action) - конструктор
 * @method void redirect(string $url, int $status = 302) - функція для перенаправлення на сторінку
 */
class ResponseClass
{
    private ?ActionClass $_action = null;
    public array $header = array();

    /**
     * ResponseClass constructor.
     * 
     * @param ActionClass $action
     */
    public function __construct(ActionClass &$action)
    {
        $this->_action = $action;
    }

    /**
     * Функція для перенаправлення на сторінку
     * 
     * @param string $header - заголовок
     * @return void
     */
    public function redirect(string $url, int $status = 302)
    {
        header('Location: ' . $url, true, $status);
        exit();
    }
}


/**
 * Class ViewClass
 * 
 * Клас для роботи з відображенням
 * 
 * @package Curswork
 * @method void __construct(ActionClass $action) - конструктор
 * @method void out(string|null $view = null, array $data = array()) - функція для виводу з хедерами
 * @method string render(string $view, array $data = array()) - функція для виводу для рендерингу в'юшки
 */
class ViewClass
{
    private ?ActionClass $_action = null;

    /**
     * ViewClass constructor.
     * 
     * @param ActionClass $action
     */
    public function __construct(ActionClass &$action)
    {
        $this->_action = $action;
    }

    /**
     * Функція для виводу з хедерами
     * 
     * @param string|null $view - шаблон
     * @param array $data - дані для виводу
     * @return void
     */
    public function out(?string $view = null, array &$data = array())
    {
        foreach ($this->_action->response->header as $head) {
            header($head, false);
        }
        if (empty($view)) {
            return;
        }
        echo $this->render($view, $data);
    }

    /**
     * Функція для виводу для рендерингу в'юшки
     * 
     * @param string|null $view - шаблон
     * @param array $data - дані для виводу
     * @return void
     */
    public function render(string $view, array &$data = array())
    {
        $file = $this->_action->dir . '/view_' . $view . '.php';
        if (file_exists($file)) {
            extract($data);
            ob_start();
            require $file;
            return ob_get_clean();
        }
        return '';
    }
}
