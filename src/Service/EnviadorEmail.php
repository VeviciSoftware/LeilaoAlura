<?php 

namespace LeilaoAlura\Service;

use LeilaoAlura\Dao\Leilao as LeilaoDao;
use LeilaoAlura\Model\Leilao;

class EnviadorEmail
{
    public function notificadorTerminoLeilao(Leilao $leilao) 
    {
        $sucesso = mail('usuario@email.com',
             'Leilão finalizado',
             'O leilão para ' . $leilao->recuperarDescricao() . ' foi finalizado'
        );

        if (!$sucesso) {
            throw new \DomainException('Erro ao enviar e-mail');
        }
    }
}