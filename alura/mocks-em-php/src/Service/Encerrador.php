<?php

namespace Alura\Leilao\Service;

use Alura\Leilao\Dao\Leilao as LeilaoDao;

class Encerrador
{
    private $dao;

    /**
     * É necessário agora passar um DAO para o construtor
     * do serviço. Desta forma podemos executar os testes
     * passando uma imitação da lógica que interage com o 
     * banco de dados.
     */
    public function __construct(LeilaoDao $dao)
    {
        $this->dao = $dao; 
    }

    public function encerra()
    {
        $leiloes = $this->dao->recuperarNaoFinalizados();

        foreach ($leiloes as $leilao) {
            if ($leilao->temMaisDeUmaSemana()) {
                $leilao->finaliza();
                $this->dao->atualiza($leilao);
            }
        }
    }
}
