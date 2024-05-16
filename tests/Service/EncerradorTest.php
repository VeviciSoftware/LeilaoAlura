<?php 

namespace LeilaoAlura\Tests\Service;

use PHPUnit\Framework\TestCase;
use LeilaoAlura\Model\Leilao;
use LeilaoAlura\Service\Encerrador;
use LeilaoAlura\Dao\Leilao as LeilaoDao;


class EncerradorTest extends TestCase {
    public function testLeiloesComMaisDeUmaSemanaDevemSerEncerrados() {
        $fiat147 = new Leilao('Fiat 147 0KM',
            new \DateTimeImmutable('8 days ago'),
        );

        $variante = new Leilao('Variant 1972 0KM',
            new \DateTimeImmutable('10 days ago'),
        );
        //O método CreateMock cria um objeto que simula a classe LeilaoDao através de reflection
        $leilaoDao = $this->createMock(LeilaoDao::class);
        $leilaoDao->method('recuperarNaoFinalizados')
            ->willReturn([$fiat147, $variante]);
        $leilaoDao->method('recuperarFinalizados')
            ->willReturn([$fiat147, $variante]);

        $leilaoDao->expects($this->exactly(2))
            ->method('atualiza')
            ->with($this->logicalOr($fiat147, $variante));

        $encerrador = new Encerrador($leilaoDao);
        $encerrador->encerra();

        //assert
        $leiloes = [$fiat147, $variante];
        static::assertCount(2, $leiloes);
        self::assertTrue($leiloes[0]->estaFinalizado());
        self::assertTrue($leiloes[1]->estaFinalizado());
        static::assertEquals('Fiat 147 0KM', $leiloes[0]->recuperarDescricao());
        static::assertEquals('Variant 1972 0KM', $leiloes[1]->recuperarDescricao());
    }
}


