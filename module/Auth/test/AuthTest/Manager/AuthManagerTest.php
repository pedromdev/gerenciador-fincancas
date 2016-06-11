<?php

namespace AuthTest\Manager;

use Auth\Manager\AuthManagerConfig;
use Auth\Manager\AuthManager;

/**
 *
 * @author Pedro Marcelo
 */
class AuthManagerTest extends \PHPUnit_Framework_TestCase {
    
    public function testSeClasseExiste() {
        $this->assertTrue(
            class_exists('\Auth\Manager\AuthManager'),
            "Nao foi possivel encontrar a classe '\Auth\Manager\AuthManager'"
        );
    }
    
    public function testSeRetornaUmaInstanciaNoServiceManager() {
        $authManager = \AuthTest\Bootstrap::getServiceManager()->get('Auth\Manager\AuthManager');
        $this->assertInstanceOf('\Auth\Manager\AuthManager', $authManager);
        $authManager2 = \AuthTest\Bootstrap::getServiceManager()->get('AuthManager');
        $this->assertInstanceOf('\Auth\Manager\AuthManager', $authManager2);
    }
    
    public function testSeEhUmServiceLocator() {
        $authManager = \AuthTest\Bootstrap::getServiceManager()->get('AuthManager');
        $this->assertInstanceOf('\Zend\ServiceManager\ServiceLocatorInterface', $authManager);
    }
    
    public function testSeEhUmServiceLocatorAware() {
        $authManager = \AuthTest\Bootstrap::getServiceManager()->get('AuthManager');
        $this->assertInstanceOf('\Zend\ServiceManager\ServiceLocatorAwareInterface', $authManager);
    }
    
    /**
     * 
     * @param array $config
     * @dataProvider proverConfiguracoesSemFactoriesEInvokables
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testSeLancaExcecaoDeServicoNaoEncontrado(array $config) {
        $authConfig = new AuthManagerConfig($config);
        $authManager = new AuthManager(\AuthTest\Bootstrap::getServiceManager());
        $authManager->setAuthConfig($authConfig);
        $authManager->get('InexistentAdapter');
    }
    
    /**
     * 
     * @param array $config
     * @dataProvider proverConfiguracoesComAdaptadoresInvalidos
     * @expectedException \Auth\Exception\InvalidAuthAdapterException
     */
    public function testLancaExcecaoQuandoNaoEUmaInstanciaAuthAdapterInterface(array $config) {
        $authConfig = new AuthManagerConfig($config);
        $authManager = new AuthManager(\AuthTest\Bootstrap::getServiceManager());
        $authManager->setAuthConfig($authConfig);
        $authManager->get('FakeAdapter');
    }
    
    /**
     * 
     * @param array $config
     * @dataProvider proverConfiguracoesComUmAdaptadorValido
     */
    public function testSeRetornaUmaInstancia(array $config) {
        $authConfig = new AuthManagerConfig($config);
        $authManager = new AuthManager(\AuthTest\Bootstrap::getServiceManager());
        $authManager->setAuthConfig($authConfig);
        $authAdapterMock = $authManager->get('AuthAdapterMock');
        $this->assertInstanceOf('\Auth\Adapter\AuthAdapterInterface', $authAdapterMock);
        $this->assertTrue($authManager->hasInstance('AuthAdapterMock'), "O adapter recem chamado nao foi registrado no array das instancias");
        $this->assertEquals($authAdapterMock, $authManager->getInstance('AuthAdapterMock'));
    }
    
    public function proverConfiguracoesComAdaptadoresInvalidos() {
        return [
            [
                [
                    'auth_manager' => [
                        'factories' => [
                            'FakeAdapter' => function () {
                                return new \AuthTest\Mock\FakeAdapterMock();
                            },
                        ],
                    ],
                ],
            ],
            [
                [
                    'auth_manager' => [
                        'factories' => [
                            'FakeAdapter' => function () {
                                return new \AuthTest\Mock\FakeApaterWithLoginMethodMock();
                            },
                        ],
                    ],
                ],
            ],
            [
                [
                    'auth_manager' => [
                        'factories' => [
                            'FakeAdapter' => 'AuthTest\Mock\FakeAdapterFactoryMock',
                        ],
                    ],
                ],
            ],
            [
                [
                    'auth_manager' => [
                        'invokables' => [
                            'FakeAdapter' => 'AuthTest\Mock\FakeApaterWithLoginMethodMock',
                        ],
                    ],
                ],
            ],
            [
                [
                    'auth_manager' => [
                        'aliases' => [
                            'FakeAdapter' => 'Auth\FakeAdapter',
                        ],
                        'invokables' => [
                            'Auth\FakeAdapter' => 'AuthTest\Mock\FakeApaterWithLoginMethodMock',
                        ],
                    ],
                ],
            ],
        ];
    }
    
    public function proverConfiguracoesSemFactoriesEInvokables() {
        return [
            [
                [
                    'auth_manager' => [],
                ],
            ],
            [
                [
                    'auth_manager' => [
                        'aliases' => [
                            'FakeAdapter' => 'Auth\FakeAdapter',
                        ],
                    ],
                ],
            ],
        ];
    }
    
    public function proverConfiguracoesComUmAdaptadorValido() {
        return [
            [
                [
                    'auth_manager' => [
                        'factories' => [
                            'AuthAdapterMock' => 'AuthTest\Mock\AuthAdapterFactoryMock'
                        ],
                    ],
                ]
            ],
            [
                [
                    'auth_manager' => [
                        'factories' => [
                            'AuthAdapterMock' => function() {
                                return new \AuthTest\Mock\AuthAdapterMock();
                            },
                        ],
                    ],
                ]
            ],
            [
                [
                    'auth_manager' => [
                        'invokables' => [
                            'AuthAdapterMock' => 'AuthTest\Mock\AuthAdapterMock'
                        ],
                    ],
                ]
            ],
            [
                [
                    'auth_manager' => [
                        'aliases' => [
                            'AuthAdapterMock' => 'Auth\AuthAdapterMock',
                        ],
                        'invokables' => [
                            'Auth\AuthAdapterMock' => 'AuthTest\Mock\AuthAdapterMock'
                        ],
                    ],
                ]
            ],
        ];
    }
}