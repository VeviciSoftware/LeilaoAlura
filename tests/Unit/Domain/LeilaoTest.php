<?php

namespace Leilao\Tests\Model;

use LeilaoAlura\Model\Leilao;
use LeilaoAlura\Model\Lance;
use LeilaoAlura\Model\Usuario;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use DomainException;

class LeilaoTest extends TestCase
{
    public function testProporLanceEmLeilaoFinalizadoDeveLancarExcecao()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Este leilão já está finalizado');

        $leilao = new Leilao('Fiat 147 0KM');
        $leilao->finaliza();

        $leilao->recebeLance(new Lance(new Usuario(''), 1000));
    }

    #[DataProvider("dadosParaProporLances")]
    public function testProporLancesEmLeilaoDeveFuncionar(int $qtdEsperado, array $lances)
    {
        $leilao = new Leilao('Fiat 147 0KM');
        foreach ($lances as $lance) {
            $leilao->recebeLance($lance);
        }

        static::assertCount($qtdEsperado, $leilao->getLances());
    }

    public function testMesmoUsuarioNaoPodeProporDoisLancesSeguidos()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Usuário já deu o último lance');
        $usuario = new Usuario('Ganancioso');

        $leilao = new Leilao('Objeto inútil');

        $leilao->recebeLance(new Lance($usuario, 1000));
        $leilao->recebeLance(new Lance($usuario, 1100));
    }

    public static function dadosParaProporLances()
    {
        $usuario1 = new Usuario('Usuário 1');
        $usuario2 = new Usuario('Usuário 2');
        return [
            [1, [new Lance($usuario1, 1000)]],
            [2, [new Lance($usuario1, 1000), new Lance($usuario2, 2000)]],
        ];
    }
}
