<?php

namespace Alura\Leilao\Tests\Service;

use Alura\Leilao\Dao\Leilao as LeilaoDao;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Service\Encerrador;
use Alura\Leilao\Service\EnviadorEmail;
use PHPUnit\Framework\TestCase;

class EncerradorTest extends TestCase
{
	private $encerrador;

	/** @var MockObject */
	private $enviadorEmail;

	private $leilao1;
	private $leilao2;

	protected function setUp(): void
	{
		// Cria o primeiro leilão
		$this->leilao1 = new Leilao(
			'Fiat 147 0KM',
			new \DateTimeImmutable('8 days ago')
		);

		// Cria o segundo leilão
		$this->leilao2 = new Leilao(
			'Variant 1972 0KM',
			new \DateTimeImmutable('10 days ago')
		);

		// Cria um mock da classe LeilaoDao
		$leilaoDAO = $this->createMock(LeilaoDao::class);

		// Imita o método "recuperarNaoFinalizados"
		$leilaoDAO->method('recuperarNaoFinalizados')
			->willReturn([$this->leilao1, $this->leilao2]);

		// Imita o método "recuperarFinalizados"
		$leilaoDAO->method('recuperarFinalizados')
			->willReturn([$this->leilao1, $this->leilao2]);

		// Espera que o método "atualiza" seja chamado uma vez
		// para cada leilão
		$leilaoDAO->expects($this->exactly(2))
			->method('atualiza')
			->withConsecutive(
				[$this->leilao1],
				[$this->leilao2]
			);

		// Cria um mock da classe EnviadorEmail
		$this->enviadorEmail = $this->createMock(EnviadorEmail::class);
		
		// Encerra os leilões
		$this->encerrador = new Encerrador($leilaoDAO, $this->enviadorEmail);
	}

	public function testLeiloesComMaisDeUmaSemanaDevemSerEncerrados()
	{
		
		$this->encerrador->encerra();

		// Busca os leilões finalizados
		$leiloesFinalizados = [$this->leilao1, $this->leilao2];

		// Testa se os leilões que foram finalizados estão corretos
		self::assertCount(2, $leiloesFinalizados);
		self::assertTrue($leiloesFinalizados[0]->estaFinalizado());
		self::assertTrue($leiloesFinalizados[1]->estaFinalizado());
	}

	public function testDeveContinuarOProcessamentoAoEncontrarErroAoEnviarEmail()
	{
		$e = new \DomainException('Erro ao enviar e-mail');
		$this->enviadorEmail->expects($this->exactly(2))
			->method('notificarTerminoLeilao')
			->willThrowException($e);
			
		$this->encerrador->encerra();
	}

	public function testSoDeveEnviarLeilaoPorEmailQuandoFinalizado()
	{
		$this->enviadorEmail->expects($this->exactly(2))
			->method('notificarTerminoLeilao')

			// Este método é utilizado quando é necessário fazer 
			// alguma validação mais complexa, onde podemos montar
			// a função de validação dos parâmetros que foram passados
			// na chamada da função "notificarTerminoLeilao"
			->willReturnCallback(function (Leilao $leilao) {
				static::assertTrue($leilao->estaFinalizado());
			});

		$this->encerrador->encerra();
	}
}