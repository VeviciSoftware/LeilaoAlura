<?php

namespace Leilao\Tests\Model;

use LeilaoAlura\Model\Leilao;
use LeilaoAlura\Model\Lance;
use LeilaoAlura\Model\Usuario;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class LeilaoTest extends TestCase
{
    #[DataProvider('geraLances')]
    public function testLeilaoDeveReceberLances(
        int $qtdLances,
        Leilao $leilao,
        array $valores
    ) {
        static::assertCount($qtdLances, $leilao->getLances());

        foreach ($valores as $i => $valorEsperado) {
            static::assertEquals($valorEsperado, $leilao->getLances()[$i]->getValor());
        }
    }

    public function testLeilaoNaoDeveReceberLancesRepetidos()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Usuário já deu o último lance');
    
        $leilao = new Leilao('Variante 0KM');
        $ana = new Usuario('Ana');
    
        $leilao->recebeLance(new Lance($ana, 1000));
        $leilao->recebeLance(new Lance($ana, 1500));
    
        static::assertCount(1, $leilao->getLances());
        static::assertEquals(1000, $leilao->getLances()[0]->getValor());
    }   
    
    public function testLeilaoNaoDeveAceitarMaisDe5LancesPorUsuario() {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Usuário não pode dar mais de 5 lances por leilão');
        
        $leilao = new Leilao('Brasília Amarela 0KM');
        $joao = new Usuario('João');
        $maria = new Usuario('Maria');
    
        for ($i = 0; $i < 5; $i++) {
            $leilao->recebeLance(new Lance($joao, $i * 1000));
            $leilao->recebeLance(new Lance($maria, $i * 1000 + 500));
        }
    
        // Adiciona um sexto lance para o usuário João
        $leilao->recebeLance(new Lance($joao, 5000));
    
        static::assertCount(10, $leilao->getLances());
        static::assertEquals(4500, $leilao->getLances()[array_key_last($leilao->getLances())]->getValor());
    }

    public static function geraLances()
    {
        $joao = new Usuario('João');
        $maria = new Usuario('Maria');

        $leilaoCom2Lances = new Leilao('Fiat 147 0KM');
        $leilaoCom2Lances->recebeLance(new Lance($joao, 1000));
        $leilaoCom2Lances->recebeLance(new Lance($maria, 2000));

        $leilaoCom1Lance = new Leilao('Fusca 1972 0KM');
        $leilaoCom1Lance->recebeLance(new Lance($maria, 5000));

        return [
            '2-lances' => [2, $leilaoCom2Lances, [1000, 2000]],
            '1-lance' => [1, $leilaoCom1Lance, [5000]]
        ];
    }
}
