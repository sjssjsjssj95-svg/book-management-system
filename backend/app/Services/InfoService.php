<?php

namespace App\Services;
use App\Models\Category;
use App\Models\Info;
use Illuminate\Support\Facades\Hash;

class InfoService
{   
    public function getAllInfo()
    {
        $infos = Info::select()
        ->get()
        ->map(function ($info) {
            return [
                'id'          => $info->id,
                'title'       => $info->title,
                'content'     => $info->content,
                'created_at'  => $info->created_at,
            ];
        });
        return $infos;
    }
}