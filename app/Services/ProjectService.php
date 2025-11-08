<?php
namespace App\Services;
use Imagick;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;     


class  ProjectService {
    public function createDocument($dados){
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
      
        $this->anexarImagem($docPreenchido,$dados)->saveAs($caminhoCompletoSaida);
 
        return $caminhoCompletoSaida;
    }


    private function preencherDocument($arquivo, $dados){
        /**Preencher o Arquivo com os dados da Sprint */
        $i = 0;
        $arquivo->cloneRow('CARD', count($dados));
        foreach ($dados as $key => $value) {
            $i++;
            $arquivo->setValue('CARD#'.$i,$value->COD_CASO);
            $arquivo->setValue('DESCRICAO#'.$i,$value->CAS_RESUMO);
            $arquivo->setValue('SOLICITACAO#'.$i,$value->CAS_RESUMO);
            $arquivo->setValue('SPRINT#'.$i,$value->Sprint);
        }
        return $arquivo;
    }


    private function anexarImagem($arquivo, $dados){
        $nomeDoBloco = 'ANEXO_BLOCO'; 
        $i = 0;
        $anexos = []; 
        $arquivo->cloneBlock($nomeDoBloco, count($dados), true, true);
        foreach ($dados as $key => $value) {
            $i++;
            if ($value->UPR_ARQUIVO) {
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
        $nomeBase = $anexos->COD_CASO;
        try {
            // 2. Lê o Conteúdo Binário do PDF (do registro atual)
            $imagick->readImageBlob($anexos->UPR_ARQUIVO);
            
        } catch (\ImagickException $e) {
            // Logar e pular para o próximo registro se este PDF falhar
            \Log::error("Falha ao processar PDF para o resumo: {$anexos->CAS_RESUMO}", ['erro' => $e->getMessage()]);
            
            // Limpar o objeto Imagick e passar para o próximo registro
            $imagick->clear();
        }
        foreach ($imagick as $i => $imagemPag) {
            $indicePagina = $i + 1; // Para ter a contagem em 1
            $outputFileName = $nomeBase . '_pag_' . $indicePagina . '.jpeg';
            $outputPath = $tmpDirectory.$outputFileName;
            $imagemPag->setImageFormat('jpeg');              // Define o formato
            $imagemPag->setResolution(300,300);
            $imagemPag->setImageCompressionQuality(100);      // Define a qualidade
            $imagemPag->writeImage($outputPath); 
            // 4. Armazena o caminho
            $todosArquivosGerados[] = [
                'resumo' => $anexos->CAS_RESUMO,
                'pagina' => $indicePagina,
                'caminho' => $outputPath
            ];
        }
        // 5. Limpa a instância do Imagick após terminar um PDF
        $imagick->clear();
        return $outputPath;
    }
}