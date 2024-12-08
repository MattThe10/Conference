<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class ArticlesExport implements FromArray
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $formatted_data = [];

        $formatted_data[] = [
            'Article ID',
            'Article Title',
            'Status',
            'Author ID',
            'Author Name',
            'Author Email',
            'Role',
            'University',
            'Faculty',
        ];

        foreach ($this->data as $article) {
            foreach ($article['authors'] as $author) {
                $formatted_data[] = [
                    $article['id'],
                    $article['title'],
                    $article['status'],
                    $author['id'],
                    $author['name'] . ' ' . $author['surname'],
                    $author['email'],
                    $author['role'],
                    $author['university'],
                    $author['faculty'],
                ];
            }
        }

        return $formatted_data;
    }
}
