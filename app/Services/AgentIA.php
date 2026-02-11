<?php

namespace App\Services; // Ajuste o namespace conforme a pasta onde salvou o arquivo

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AgenteIA
{
    public function makeDescription($descricao)
    {
        // 1. Configurações Iniciais
        set_time_limit(120);
        $apiKey = config('app.openai_api_key', env('OPENAI_API_KEY'));
        $assistantId = 'asst_EMSneBT4LxQPGjXocKTcs4PU';
        
        $headers = [
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type'  => 'application/json',
            'OpenAI-Beta'   => 'assistants=v2', 
        ];

        // Opção para ignorar erro SSL local (remover em produção)
        $options = ['verify' => false]; 

        // ---------------- PASSO 1: CRIAR THREAD ----------------
        // Corrigido: Usando options e apenas uma chamada
        $threadResponse = Http::withOptions($options)
            ->withHeaders($headers)
            ->post('https://api.openai.com/v1/threads');
        
        // Verifica se criou a thread com sucesso antes de continuar
        if ($threadResponse->failed()) {
            return "Erro ao conectar com a OpenAI: " . $threadResponse->body();
        }

        $threadId = $threadResponse->json('id');
        
        // ---------------- PASSO 2: ADICIONAR MENSAGEM ----------------
        Http::withOptions($options)
            ->withHeaders($headers)
            ->post("https://api.openai.com/v1/threads/{$threadId}/messages", [
                'role' => 'user',
                'content' => $descricao,
            ]);

        // ---------------- PASSO 3: EXECUTAR (RUN) ----------------
        $runResponse = Http::withOptions($options)
            ->withHeaders($headers)
            ->post("https://api.openai.com/v1/threads/{$threadId}/runs", [
                'assistant_id' => $assistantId,
            ]);

        $runId = $runResponse->json()['id'];
        $status = 'queued';
    
        // ---------------- PASSO 4: AGUARDAR (LOOP) ----------------
        $tentativas = 0;
        while ($status != 'completed') {
            if ($tentativas > 30) { 
                return "Erro: O assistente demorou muito para responder.";
            }

            sleep(1); 
            $tentativas++;

            $checkResponse = Http::withOptions($options)
                ->withHeaders($headers)
                ->get("https://api.openai.com/v1/threads/{$threadId}/runs/{$runId}");
            
            $status = $checkResponse->json()['status'];

            if ($status == 'failed' || $status == 'cancelled') {
                return "Erro: Falha na execução do assistente.";
            }
        }

        // ---------------- PASSO 5: BUSCAR RESPOSTA ----------------
        $messagesResponse = Http::withOptions($options)
            ->withHeaders($headers)
            ->get("https://api.openai.com/v1/threads/{$threadId}/messages");

        $mensagens = $messagesResponse->json()['data'];

        $respostaAssistente = "Nenhuma resposta encontrada.";
        foreach ($mensagens as $msg) {
            if ($msg['role'] === 'assistant') {
                $respostaAssistente = $msg['content'][0]['text']['value'];
                break;
            }
        }

        // Retornamos apenas o texto da resposta para quem chamou a função
        return $respostaAssistente;
    }
}