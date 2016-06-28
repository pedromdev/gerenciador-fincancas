<?php

namespace ThemeTest\AssetManager\Asset;

use \Theme\AssetManager\Asset\Javascript;
use \Theme\AssetManager\Asset\QueueStatus;
use \ThemeTest\AssetManager\Mock\InvalidDependencyMock;

/**
 *
 * @author PedromDev
 */
class JavascriptTest extends \PHPUnit_Framework_TestCase
{
    public function testVerificarTipoDoAsset()
    {
        $javacript = new Javascript('unit-test');
        
        $this->assertEquals('text/javascript', $javacript->getType());
    }
    
    public function testSeExisteOAtributoId()
    {
        $javacript = new Javascript('unit-test');
        
        $this->assertTrue($javacript->hasAttribute('id'), 'Um identificador não foi adicionado');
        
        $this->assertEquals('unit-test', $javacript->getIdentifier());
        $this->assertEquals('unit-test', $javacript->getAttribute('id'));
    }
    
    public function testAdicionarAtributoComTipoDeValorNaoAceito()
    {
        $javacript = new Javascript('unit-test');
        
        $javacript->addAttribute('attr', true);
        
        $this->assertEquals('', $javacript->getAttribute('attr'));
    }
    
    /**
     * 
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Invalid attribute name. Accepted pattern: a-z0-9._-
     */
    public function testAdicionarAtributoComNomeNaoAceito()
    {
        $javacript = new Javascript('unit-test');
        
        $javacript->addAttribute('at/tr', 'hello-world');
    }
    
    public function testAdicionarESobrescreverAtributos()
    {
        $javacript = new Javascript('unit-test');
        
        $javacript->setAttributes([
            'id' => 'other-unit-test',
            'class' => 'javascript',
        ]);
        
        $this->assertTrue($javacript->hasAttribute('class'), 'O atributo class não foi adicionado');
        $this->assertEquals('other-unit-test', $javacript->getAttribute('id'));
        $this->assertEquals('javascript', $javacript->getAttribute('class'));
    }
    
    public function testRemoverAtributos()
    {
        $javacript = new Javascript('unit-test');
        
        $javacript->removeAttributes(['type']);
        
        $this->assertFalse(in_array('type', $javacript->getAttributes()), 'Alguns dos atributos não foram removidos');
    }
    
    public function testRemoverAtributosQueNaoExistem()
    {
        $javacript = new Javascript('unit-test');
        
        $countAttributes = count($javacript->getAttributes());
        $javacript->removeAttributes(['class']);
        
        $this->assertEquals($countAttributes, count($javacript->getAttributes()));
    }
    
    public function testInserirCaminhoDoArquivo()
    {
        $javacript = new Javascript('unit-test');
        
        $javacript->setPath('/path/to/javascript.js');
        
        $this->assertEquals('/path/to/javascript.js', $javacript->getPath());
        $this->assertEquals('<script type="text/javascript" id="unit-test" src="/path/to/javascript.js"></script>', "$javacript");
    }
    
    public function testEnfileirarJavascript()
    {
        $javacript = new Javascript('unit-test');
        
        $javacript->enqueue();
        
        $this->assertEquals(QueueStatus::ENQUEUED, $javacript->getStatus());
    }
    
    public function testAdicionarDependencia()
    {
        $javacript = new Javascript('unit-test');
        $javacript2 = new Javascript('unit-test2');
        
        $javacript->add($javacript2);
        
        $this->assertEquals($javacript2, $javacript->get('unit-test2'));
    }
    
    public function testEnfileirarJavascriptComDependencias()
    {
        $javacript = new Javascript('unit-test');
        $javacript2 = new Javascript('unit-test2');
        
        $javacript->add($javacript2);
        $javacript->enqueue();
        
        $this->assertTrue($javacript->hasDependencies(), 'Não foi possível detectar as dependências adicionadas');
        $this->assertEquals(QueueStatus::ENQUEUED, $javacript->getStatus());
        $this->assertEquals(QueueStatus::ENQUEUED, $javacript2->getStatus());
    }
    
    /**
     * 
     * @expectedException \Theme\AssetManager\DependencyException
     * @expectedExceptionCode 121
     * @expectedExceptionMessage The 'unit-test2' already exists in 'unit-test'
     */
    public function testAdicionarDependenciaJaInserida()
    {
        $javacript = new Javascript('unit-test');
        $javacript2 = new Javascript('unit-test2');
        
        $javacript->add($javacript2);
        $javacript->add($javacript2);
    }
    
    /**
     * 
     * @expectedException \Theme\AssetManager\DependencyException
     * @expectedExceptionCode 122
     * @expectedExceptionMessage Circular dependency detected: 'unit-test' - 'unit-test2'
     */
    public function testCausarEfeitoDeDependenciaCircular()
    {
        $javacript = new Javascript('unit-test');
        $javacript2 = new Javascript('unit-test2');
        
        $javacript->add($javacript2);
        $javacript2->add($javacript);
    }
    
    /**
     * 
     * @expectedException \Theme\AssetManager\DependencyException
     * @expectedExceptionCode 122
     * @expectedExceptionMessage Circular dependency detected: 'unit-test' - 'unit-test2'
     */
    public function testCausarEfeitoDeDependenciaCircularEmSubdependencias()
    {
        $javacript = new Javascript('unit-test');
        $javacript2 = new Javascript('unit-test2');
        $javacript3 = new Javascript('unit-test3');
        
        $javacript->add($javacript3);
        $javacript3->add($javacript2);
        $javacript2->add($javacript);
    }
    
    /**
     * 
     * @expectedException \Theme\AssetManager\DependencyException
     * @expectedExceptionCode 120
     * @expectedExceptionMessage Couldn't find the following dependency: 'not-found'
     */
    public function testCausarEfeitoDeDependenciaInexistente()
    {
        $javacript = new Javascript('unit-test');
        
        $javacript->get('not-found');
    }
    
    /**
     * 
     * @expectedException \Theme\AssetManager\DependencyException
     * @expectedExceptionCode 120
     * @expectedExceptionMessage Couldn't find the following dependency: 'not-found'
     */
    public function testCausarEfeitoDeDependenciaNaoEncontradaNasDependencias()
    {
        $javacript = new Javascript('unit-test');
        $javacript2 = new Javascript('unit-test2');
        
        $javacript->add($javacript2);
        
        $javacript->get('not-found');
    }
    
    /**
     * 
     * @expectedException \Theme\AssetManager\DependencyException
     * @expectedExceptionCode 123
     * @expectedExceptionMessage Dependency expected an 'Theme\AssetManager\AbstractAssetDependency' instance, but ThemeTest\AssetManager\Mock\InvalidDependencyMock given
     */
    public function testCausarEfeitoDeDependenciaInvalida()
    {
        $javascript = new Javascript('unit-test');
        $mock = new InvalidDependencyMock();
        
        $javascript->add($mock);
    }
    
    /**
     * 
     * @expectedException \Theme\AssetManager\DependencyException
     * @expectedExceptionCode 124
     * @expectedExceptionMessage Auto dependency detected: 'unit-test'
     */
    public function testCausarEfeitoDeAutoDependencia()
    {
        $javascript = new Javascript('unit-test');
        $javascript2 = new Javascript('unit-test');
        
        $javascript->add($javascript2);
    }
    
    public function testRemoverUmaDependencia()
    {
        $javascript = new Javascript('unit-test');
        $javascript2 = new Javascript('unit-test2');
        
        $javascript->add($javascript2);
        
        $this->assertTrue($javascript->remove('unit-test2'), 'Não removeu uma dependência existente');
    }
    
    public function testRemoverDependenciaInexistente()
    {
        $javascript = new Javascript('unit-test');
        
        $this->assertFalse($javascript->remove('not-found'), 'Foi removida uma dependência inexistente');
    }
    
    public function testRemoverTodasAsDependencias()
    {
        $javascript = new Javascript('unit-test');
        $javascript2 = new Javascript('unit-test2');
        
        $javascript->add($javascript2);
        $countDependencies = count($javascript->getAll());
        
        $this->assertTrue($javascript->removeAll(), 'Não removeu uma dependência existente');
        $this->assertNotEquals($countDependencies, count($javascript->getAll()));
    }
    
    public function testSeNomeDaDependenciaEhOIdentificador()
    {
        $javascript = new Javascript('unit-test');
        $javascript2 = new Javascript('unit-test2');
        
        $javascript->setName('other-unit-test');
        
        $this->assertEquals('other-unit-test', $javascript->getName());
        $this->assertEquals('unit-test2', $javascript2->getName());
    }
}
