<?php 

namespace LeilaoAlura\Tests\Service;

use PHPUnit\Framework\TestCase;
use LeilaoAlura\Model\Leilao;
use LeilaoAlura\Service\Encerrador;
use LeilaoAlura\Dao\Leilao as LeilaoDao;
use LeilaoAlura\Service\EnviadorEmail;


class EncerradorTest extends TestCase {
    private $encerrador;
    private $enviadorEmail;
    private $leilaoFiat147;
    private $leilaoVariante;

    protected function setUp(): void
    {
        $this->leilaoFiat147 = new Leilao('Fiat 147 0KM',
        new \DateTimeImmutable('8 days ago'),
        );
    
        $this->leilaoVariante = new Leilao('Variant 1972 0KM',
            new \DateTimeImmutable('10 days ago'),
        );
    
        $leilaoDao = $this->createMock(LeilaoDao::class);
        $leilaoDao->method('recuperarNaoFinalizados')
            ->willReturn([$this->leilaoFiat147, $this->leilaoVariante]);
        $leilaoDao->method('recuperarFinalizados')
            ->willReturn([$this->leilaoFiat147, $this->leilaoVariante]);
    
        $leilaoDao->expects($this->exactly(2))
            ->method('atualiza')
            ->with($this->logicalOr($this->leilaoFiat147, $this->leilaoVariante));
    
        $this->enviadorEmail = $this->createMock(EnviadorEmail::class);
    
        $this->encerrador = new Encerrador($leilaoDao, $this->enviadorEmail);
    }
    public function testLeiloesComMaisDeUmaSemanaDevemSerEncerrados() {
        $this->encerrador->encerra();

        //assert
        $leiloes = [$this->leilaoFiat147, $this->leilaoVariante];
        static::assertCount(2, $leiloes);
        self::assertTrue($leiloes[0]->estaFinalizado());
        self::assertTrue($leiloes[1]->estaFinalizado());
        static::assertEquals('Fiat 147 0KM', $leiloes[0]->recuperarDescricao());
        static::assertEquals('Variant 1972 0KM', $leiloes[1]->recuperarDescricao());
    }

    public function testDeveContinuarOProcessamentoAoEncontrarErroAoEnviarEmail() 
    {
        $e = new \DomainException('Erro ao enviar e-mail');

        $this->enviadorEmail->expects($this->exactly(2))
            ->method('notificadorTerminoLeilao')
            ->willThrowException($e);
        
        $this->encerrador->encerra();

    }

    public function testSoDeveEnviarLeilaoPorEmailAposFinalizado() 
    {
        $this->enviadorEmail->expects($this->exactly(2))
            ->method('notificadorTerminoLeilao')
            ->willReturnCallback(function(Leilao $leilao) {
                self::assertTrue($leilao->estaFinalizado());
            });

        $this->encerrador->encerra();
    }
}


