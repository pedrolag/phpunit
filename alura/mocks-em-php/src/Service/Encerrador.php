<?php

namespace Alura\Leilao\Service;

use Alura\Leilao\Dao\Leilao as LeilaoDao;

class Encerrador
{
    private $dao;
    private $enviadorEmail;

    /**
     * É necessário agora passar um DAO para o construtor
     * do serviço. Desta forma podemos executar os testes
     * passando uma imitação da lógica que interage com o 
     * banco de dados.
     */
    public function __construct(LeilaoDao $dao, EnviadorEmail $enviadorEmail)
    {
        $this->dao = $dao; 
        $this->enviadorEmail = $enviadorEmail; 
    }

    public function encerra()
    {
        $leiloes = $this->dao->recuperarNaoFinalizados();

        foreach ($leiloes as $leilao) {
            if ($leilao->temMaisDeUmaSemana()) {
                try {

                    $leilao->finaliza();
                    $this->dao->atualiza($leilao);
                    $this->enviadorEmail->notificarTerminoLeilao($leilao);

                } catch (\DomainException $e) {

                    error_log($e->getMessage());
                    
                }
            }
        }
    }
}
