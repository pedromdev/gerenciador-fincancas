<?php

namespace Base\Test;

/**
 *
 * @author PedromDev
 */
abstract class AbstractHttpControllerTestCase extends \Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase
{
    /**
     *
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    private $serviceLocator;

    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    /**
     *
     * @var array
     */
    private $tables = [];
    
    /**
     *
     * @var array
     */
    private static $config;
    
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        $configFile = getcwd() . '/config/application.config.php';
        
        if (!self::$config && is_file($configFile)) {
            self::$config = include $configFile;
        }
    }
    
    protected function setUp()
    {
        $this->initBeforeSetUp();
        
        if (!$this->application)
        {
            $this->application = $this->getServiceLocator()->get('Application');
            $this->setApplicationConfig($this->getServiceLocator()->get('ApplicationConfig'));
            $this->getApplication()->getServiceManager()->setAllowOverride(true);
        }
        
        if (is_null($this->entityManager))
        {
            $this->entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        parent::setUp();
    }
    
    abstract protected function initBeforeSetUp();
    
    protected function tearDown()
    {
        parent::tearDown();
        $this->truncateDatabase($this->popTables());
    }
    
    /**
     * 
     * @return \Doctrine\ORM\EntityManager;
     */
    public function getEntityManager()
    {
        if (!$this->entityManager->isOpen())
        {
            $this->entityManager = $this->entityManager->create($this->entityManager->getConnection(), $this->entityManager->getConfiguration());
        }
        return $this->entityManager;
    }

    private function truncateDatabase(array $tables = [])
    {
        if (!empty($tables))
        {
            $sql = "SET foreign_key_checks = 0; %s SET foreign_key_checks = 1;";
            $truncates = trim(str_repeat("TRUNCATE %s; ", count($tables)));
            $params = array_merge([$truncates], $tables);
            $finalSql = sprintf($sql, call_user_func_array('sprintf', $params));
            $this->getEntityManager()->getConnection()->exec($finalSql);
        }
    }
    
    /**
     * 
     * @return array
     */
    public function getTables()
    {
        return $this->tables;
    }
    
    /**
     * 
     * @param array $tables
     */
    public function setTables(array $tables)
    {
        $this->tables = $tables;
    }
    
    /**
     * 
     * @param array $tables
     */
    public function addTables(array $tables)
    {
        $this->setTables(array_unique(array_merge($this->getTables(), $tables)));
    }
    
    /**
     * 
     * @return array
     */
    public function popTables()
    {
        $tables = $this->getTables();
        $this->setTables([]);
        return $tables;
    }
    
    /**
     * 
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * 
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceManager
     * @return \Base\Test\AbstractHttpControllerTestCase
     */
    public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $serviceManager)
    {
        $this->serviceLocator = $serviceManager;
        return $this;
    }
    
    /**
     * Retorna o resultado da ação executada
     * 
     * @return mixed
     */
    public function getActionResult()
    {
        /* @var $mvcEvent \Zend\Mvc\MvcEvent */
        $mvcEvent = $this->getApplication()->getMvcEvent();
        return $mvcEvent->getResult();
    }
}
