<?php

namespace AuthTest\Manager;

use Auth\Manager\AuthManagerConfig;

/**
 *
 * @author Pedro Marcelo
 */
class AuthManagerConfigTest extends \PHPUnit_Framework_TestCase {
    
    public function testSeClasseExiste() {
        $this->assertTrue(
            class_exists('\Auth\Manager\AuthManagerConfig'),
            "Não foi possível encontrar a classe '\Auth\Manager\AuthManagerConfig'"
        );
    }
    
    public function testSeRetornaUmaInstanciaNoServiceManager() {
        $authConfig = \AuthTest\Bootstrap::getServiceManager()->get('Auth\Manager\AuthManagerConfig');
        $this->assertInstanceOf('\Auth\Manager\AuthManagerConfig', $authConfig);
    }
    
    /**
     * @expectedException \Auth\Exception\ConfigurationNotFoundException
     * @dataProvider configuracaoVazia
     */
    public function testSeLancaExcecaoQuandoNaoHaConfiguracoesDoAuthManager($config) {
        new AuthManagerConfig($config);
    }
    
    public function testSeAsConfiguracoesForamInicializadas() {
        $authConfig = \AuthTest\Bootstrap::getServiceManager()->get('Auth\Manager\AuthManagerConfig');
        $this->assertTrue(is_array($authConfig->getAliases()), "Nao foram configurados nenhum aliases");
        $this->assertTrue(is_array($authConfig->getFactories()), "Nao foram configuradas nenhuma factories");
        $this->assertTrue(is_array($authConfig->getInvokables()), "Nao foram configurados nenhum invokables");
    }
    
    /**
     * @expectedException \BadMethodCallException
     * @dataProvider configuracaoComPropriedadeInvalida
     */
    public function testSeLancaExcecaoQuandoPassaUmaConfiguracaoInexistente($config) {
        new AuthManagerConfig($config);
    }
    
    public function configuracaoVazia() {
        return [
            [
                [],
            ],
        ];
    }
    
    public function configuracaoComPropriedadeInvalida() {
        return [
            [
                [
                    'auth_manager' => [
                        'inexistent_key' => []
                    ]
                ]
            ]
        ];
    }
}
