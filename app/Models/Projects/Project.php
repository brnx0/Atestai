<?php
namespace App\Models\Projects;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $connection = 'connectionSig';
    protected $primaryKey = 'COD_PROJETO';
    
    // Supondo que a tabela seja "projetos" ou "SUP_PROJETO", se for diferente, favor ajustar.
    // protected $table = 'SUP_PROJETO';
}