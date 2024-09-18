<?php

namespace LeilaoAlura\Integration\Dao;
use LeilaoAlura\Infra\ConnectionCreator;
use PHPUnit\Framework\TestCase;
use LeilaoAlura\Dao\Leilao as LeilaoDao;
use LeilaoAlura\Model\Leilao;

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

    public function testInsercaoEBuscaDevemFuncionar()
    {
        // arrange
        $leilao = new Leilao('Variante 0Km');
        $leilaoDao = new LeilaoDao(self::$pdo);
        $leilaoDao->salva($leilao);

        // act
        $leiloes = $leilaoDao->recuperarNaoFinalizados();

        // assert
        self::assertCount(1, $leiloes);
        self::assertContainsOnlyInstancesOf(Leilao::class, $leiloes);
        self::assertSame(
            'Variante 0Km',
            $leiloes[0]->recuperarDescricao()
        );
    }

    protected function tearDown(): void
    {
        self::$pdo->rollBack();
    }
}