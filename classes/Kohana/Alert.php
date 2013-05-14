<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Created by JetBrains PhpStorm.
 * User: sfagnum
 * Date: 06.05.13
 * Time: 18:32
 * To change this template use File | Settings | File Templates.
 */

class Kohana_Alert
{

    const SUCCESS = 'success';
    const ERROR = 'error';
    const WARNING = 'warning';


    public $key = 'JJdl3l1';

    protected static $instance;

    /**
     * @var Session session instance
     */
    protected $sess;

    protected $data = array();

    protected $config;

    public static function instance()
    {
        if (! self::$instance)
        {
            self::$instance = new Alert;
        }
        return self::$instance;
    }

    function __construct()
    {
        $this->config = Kohana::$config->load('alert');
        $this->sess = Session::instance();
        $this->key = 'Alert:'.$this->key;
        $this->data = & $this->sess->as_array();
    }

    /**
     * Push message to Alert
     * Example: Alert::push('page saved', Alert::SUCCESS)
     * if $alert_type is null Alert use default message template
     * @param array|string $message
     * @param Alert $alert_type Alert::SUCCESS, Alert::ERROR etc.
     */
    public static function push($message, $alert_type = NULL)
    {
        list($data, $key) = array(self::instance()->data, self::instance()->key);
        if (! array_key_exists($key, $data))
        {
            self::instance()->data[$key] = array(':new' => array(), ':old' => array());
        }
        self::instance()->data[$key][':new'][] = array('message' => $message, 'type' => $alert_type);
    }

    public static function render()
    {
        list($data, $key) = array(self::instance()->data, self::instance()->key);
        if (! array_key_exists($key, $data))
        {
            self::instance()->data[$key] = array(':new' => array(), ':old' => array());
            return FALSE;
        }
        if (! count($data[$key][':new']))
        {
            return FALSE;
        }
        echo self::instance()->show();
    }

    function show()
    {
        $html = '';
        list($data, $key) = array(self::instance()->data, self::instance()->key);
        self::instance()->data[$key][':old'] = self::instance()->data[$key][':new'];

        foreach (self::instance()->data[$key][':new'] as $alert)
        {
            $view = $this->get_template($alert);
            $html .= $view->set('message', $alert['message']);
        }
        self::instance()->data[$key][':new'] = array();

        return $html;
    }


    private function get_template($alert)
    {
        if ($alert['type'])
        {
            return View::factory('Alert/'.$this->config['theme'].'/'.$alert['type']);
        }
        else
        {
            return View::factory('Alert/'.$this->config['theme'].'/default');
        }
    }

    public static function __callStatic($method, $parameters)
    {
        return call_user_func_array(array(self::instance(), $method), $parameters);
    }
}
