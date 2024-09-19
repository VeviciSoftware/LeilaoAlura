<?php

namespace LeilaoAlura\Integration\Dao;
use LeilaoAlura\Infra\ConnectionCreator;
use PHPUnit\Framework\TestCase;
use LeilaoAlura\Dao\Leilao as LeilaoDao;
use LeilaoAlura\Model\Leilao;
use PHPUnit\Metadata\DataProvider;

class LeilaoDaoTest extends TestCase
{
    private static \PDO $pdo;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = new \PDO('sqlite::memory:');
        self::$pdo->exec('create table leiloes (
            id INTEGER primary key,
            descricao TEXT,
            finalizado BOOL,
            dataInicio TEXT
        );');
    }

    protected function setUp(): void
    {
        self::$pdo->beginTransaction();
    }

    /**
     * @dataProvider leiloes
     */
    public function testBuscaLeiloesNaoFinalizados(array $leiloes)
    {
        // arrange
        $leilaoDao = new LeilaoDao(self::$pdo);
        foreach ($leiloes as $leilao) {
            $leilaoDao->salva($leilao);
        }
    
        // act
        $leiloes = $leilaoDao->recuperarNaoFinalizados();
    
        // assert
        self::assertCount(1, $leiloes);
        self::assertContainsOnlyInstancesOf(Leilao::class, $leiloes);
        self::assertSame(
            'Variante 0Km',
            $leiloes[0]->recuperarDescricao()
        );
        self::assertFalse($leiloes[0]->estaFinalizado());
    }

    /**
     * @dataProvider leiloes
     */
    public function testBuscaLeiloesFinalizados(array $leiloes)
    {
        // arrange
        $leilaoDao = new LeilaoDao(self::$pdo);

        foreach ($leiloes as $leilao) {
            $leilaoDao->salva($leilao);
        }

        // act
        $leiloes = $leilaoDao->recuperarFinalizados();

        // assert
        self::assertCount(1, $leiloes);
        self::assertContainsOnlyInstancesOf(Leilao::class, $leiloes);
        self::assertSame(
            'Fusca 1972',
            $leiloes[0]->recuperarDescricao()
        );
    }

    protected function tearDown(): void
    {
        self::$pdo->rollBack();
    }

    public static function leiloes(): array
    {
        $naoFinalizado = new Leilao('Variante 0Km');
        $finalizado = new Leilao('Fusca 1972');
        $finalizado->finaliza();
    
        return [
            [[$naoFinalizado, $finalizado]]
        ];
    }
}