<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Templates extends Model
{
    use HasFactory;

    public function company()
    {
        return $this->belongsTo(Companies::class);
    }

    public function templateCompany()
    {
        return $this->belongsTo(Companies::class, 'template_company', 'id');
    }

    public function promotion()
    {
        return $this->belongsTo(Promotions::class);
    }

    public static function getTemplates()
    {
        $userCompanies = session('companies_array', []);

        Log::info('Empresas del usuario: ' . implode(', ', $userCompanies));

        if (empty($userCompanies)) {
            return [];
        }

        $query = self::with(['company', 'promotion'])
            ->whereIn('template_company', $userCompanies);
        $templates =  $query->orderByDesc('created_at')->get();
        $data = [];
        foreach ($templates as $row) {
            $data[] = [
                'id' => $row->id,
                'company' =>  optional($row->company)->name,
                'company_id' =>  $row->company_id,
                'promotion' =>  optional($row->promotion)->name,
                'promotion_id' =>  $row->promotion_id,
                'text' => $row->content,
                'issue_date' => $row->created_at,
                'template_company_id' => $row->template_company,
                'template_company' => optional($row->templateCompany)->name,
            ];
        }
        return $data;
    }
}
