<?php

namespace AutowpTest\OAuth2\Client;

use Autowp\OAuth2\Client\Provider\Vk;

use League\OAuth2\Client\Token\AccessToken;

/**
 * @group Autowp\OAuth2\Client\Provider\Vk
 */
class ProviderTest extends \PHPUnit_Framework_TestCase
{
    
    public function testSetLangPropery()
    {
        $provider = new Vk();
        
        $provider->setLang('en');
        
        $token = new AccessToken([
            'access_token' => 'test-token'
        ]);
        
        $url = $provider->getResourceOwnerDetailsUrl($token);
        
        $this->assertContains('lang=en', $url);
    }
    
    public function testResetLangPropery()
    {
        $provider = new Vk();
    
        $provider->setLang('en');
        $provider->setLang(null);
    
        $token = new AccessToken([
            'access_token' => 'test-token'
        ]);
    
        $url = $provider->getResourceOwnerDetailsUrl($token);
    
        $this->assertNotContains('lang=en', $url);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetLangProperyThrownsInvalidArgumentExceptionOnInt()
    {
        $provider = new Vk();
    
        $provider->setLang(1);
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetLangProperyThrownsInvalidArgumentExceptionOnObject()
    {
        $provider = new Vk();
    
        $provider->setLang($provider);
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetLangProperyThrownsInvalidArgumentExceptionOnArray()
    {
        $provider = new Vk();
    
        $provider->setLang([]);
    }

}