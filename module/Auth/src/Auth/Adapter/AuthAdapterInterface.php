<?php

namespace Auth\Adapter;

/**
 *
 * @author Pedro Marcelo
 */
interface AuthAdapterInterface {
    
    /**
     * Faz o login do usuário na aplicação
     * 
     * @param array $data
     */
    public function login(array $data);
}
