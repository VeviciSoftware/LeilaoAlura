<?php 

namespace LeilaoAlura\Tests\Service;

use PHPUnit\Framework\TestCase;
use LeilaoAlura\Model\Leilao;
use LeilaoAlura\Service\Encerrador;
use LeilaoAlura\Dao\Leilao as LeilaoDao;


class LeilaoDaoMock extends LeilaoDao {

    private $leiloes = [];
    public function salva(Leilao $leilao): void {
        $this->leiloes[] = $leilao;

    }

    public function recuperarNaoFinalizados(): array {
        return array_filter($this->leiloes, function (Leilao $leilao) {
            return !$leilao->estaFinalizado();
        });
    }

    public function recuperarFinalizados(): array {
        return array_filter($this->leiloes, function (Leilao $leilao) {
            return $leilao->estaFinalizado();
        });
    }

    public function atualiza(Leilao $leilao): void {

    }
}

class EncerradorTest extends TestCase {
    public function testLeiloesComMaisDeUmaSemanaDevemSerEncerrados() {
        $fiat147 = new Leilao('Fiat 147 0KM',
            new \DateTimeImmutable('8 days ago'),
        );

        $variante = new Leilao('Variant 1972 0KM',
            new \DateTimeImmutable('10 days ago'),
        );

        $leilaoDao = new LeilaoDaoMock();
        $leilaoDao->salva($fiat147);
        $leilaoDao->salva($variante);

        $encerrador = new Encerrador($leilaoDao);
        $encerrador->encerra();

        //assert
        $leiloes = $leilaoDao->recuperarFinalizados();
        static::assertCount(2, $leiloes);
        static::assertEquals('Fiat 147 0KM', $leiloes[0]->recuperarDescricao());
        static::assertEquals('Variant 1972 0KM', $leiloes[1]->recuperarDescricao());
    }
}


