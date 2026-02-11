<?php
namespace App\Services;
use Illuminate\Support\Facades\DB;
use Imagick;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use App\Models\Projects\Sprint;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;     
use App\Services\AgenteIA;

class  ProjectService {
    public function createDocument($dados, $codSprint){
		ini_set('max_execution_time', 600);
		set_time_limit(600); 
		ini_set('memory_limit', '512M');
        $nomeArquivoSaida = 'Atesto_Final_' . time() . '.docx';
        $caminhoCompletoSaida = base_path().'/public/tmp/' . $nomeArquivoSaida;
        Storage::makeDirectory('outputs');
        if(!$dados || empty($dados)){
            return false;
        }
        $nomeArquivoFinal = 'Documento_PDFs_Juntos_' . time() . '.docx';
        $docAtesto = new TemplateProcessor(base_path().'/public/documents/modeloAtesto.docx');  
        $docAtesto->setValue('DATA', date('d/m/Y'));
        $docPreenchido = $this->preencherDocument($docAtesto, $dados);
        $this->anexarImagem($docPreenchido, $codSprint)->saveAs($caminhoCompletoSaida);
        //$docPreenchido->saveAS($caminhoCompletoSaida);
 
        return $caminhoCompletoSaida;
    }


    private function preencherDocument($arquivo, $dados){
        /**Preencher o Arquivo com os dados da Sprint */
        $i = 0;
        $arquivo->cloneRow('CARD', count($dados));
        $resumo = collect($dados)->pluck('CAS_DESCRICAO')->toJson();
        $agente = new AgenteIA();
        $descricao = json_decode($agente->MakeDescription($resumo));
        foreach ($dados as $key => $value) {
            $i++;
            $arquivo->setValue('CARD#'.$i,$value->COD_CASO);
            $arquivo->setValue('DESCRICAO#'.$i, $descricao[$i-1] ?? '');
            $arquivo->setValue('SOLICITACAO#'.$i,$value->CAS_RESUMO);
            $arquivo->setValue('SPRINT#'.$i,$value->Sprint);
        }
        return $arquivo; 
    }

    private function anexarImagem($arquivo, $codSprint){
        $nomeDoBloco = 'ANEXO_BLOCO'; 
        $i = 0;
        $anexos = []; 
        $dados = $this->queryAnexos($codSprint);
        $arquivo->cloneBlock($nomeDoBloco, count($dados), true, true);
		set_time_limit(600);
        foreach ($dados as $key => $value) {
            $i++;
			$formatArquivo = strtoupper(bin2hex(substr($value->UPR_ARQUIVO, 0, 4)));
		
            if ($value->UPR_ARQUIVO && ($formatArquivo == '25504446' || $formatArquivo == '89504E47' ||  $formatArquivo == 'FFD8FF') ) {
                $caminhoImagem = $this->criarAnexo($value);
                $arquivo->setValue('DESCRICAO_ANEXO#'.$i,  'Card: '.$value->COD_CASO);
                if (file_exists($caminhoImagem)) {
                    $arquivo->setImageValue('ANEXO#'.$i, [
                        'path'      => $caminhoImagem,
                        'width'     => 600, // Largura
                        'height'    =>600  ,
                        'ratio'     => false,
                        'alignment' => 'center',
                    ]);
                }
            }else{
                    $arquivo->setValue('DESCRICAO_ANEXO#'.$i, '');
                    $arquivo->setValue('ANEXO#'.$i, ' ');
            }
        }
          
        return $arquivo;
         
    } 

    private function criarAnexo($anexos){
        $tmpDirectory = base_path().'/public/tmp/';
        if(!is_dir($tmpDirectory)){
            mkdir($tmpDirectory,0755, true);
        }
        $imagick = new \Imagick();

        $nomeBase = $anexos->UPR_COD;
        $outputPath = false;
        try {
            // Definir resolução antes de ler o blob melhora a qualidade das páginas
            $imagick->setResolution(150,150);
            // Lê o Conteúdo Binário do PDF (do registro atual)
            $imagick->readImageBlob($anexos->UPR_ARQUIVO);

            // Garante que cada frame esteja em PNG e com boa qualidade
            foreach ($imagick as $frame) {
                $frame->setImageFormat('png');
                $frame->setImageCompressionQuality(100);
            }

            // Junta todas as páginas em uma única imagem empilhada verticalmente
            $combined = $imagick->appendImages(true);
            $combined->setImageFormat('png');
            $combined->setImageCompressionQuality(100);

            $outputFileName = $nomeBase . '_pag_all.png';
            $outputPath = $tmpDirectory . $outputFileName;
            $combined->writeImage($outputPath);

            // Limpeza do objeto combinado
            $combined->clear();
        } catch (\ImagickException $e) {
            // Logar e retornar false para que o chamador ignore este anexo
            \Log::error("Falha ao processar PDF para o resumo: {$anexos->UPR_COD}", ['erro' => $e->getMessage()]);
            $imagick->clear();
            return false;
        }

        // Limpa a instância do Imagick após terminar um PDF
        $imagick->clear();
        return $outputPath;
    }

    private function queryAnexos($SprintCod){
        $query = 
            DB::connection('connectionSig')->select("SELECT 
            C.COD_CASO,
			A.UPR_COD,
            A.UPR_ARQUIVO
            FROM SUP_VERSAO V
            INNER JOIN SUP_CASO C ON C.CAS_COD_VERSAO = V.COD_VERSAO

            INNER  JOIN SUP_UPLOAD_REQUISITO  A ON C.COD_CASO = A.COD_CASO
            WHERE C.CAS_CAT = 2  AND A.UPR_TIPO IN ('png', 'pdf','jpg') AND V.COD_VERSAO = ".$SprintCod);
        return $query;
    }
}