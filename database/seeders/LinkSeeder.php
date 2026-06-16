<?php

namespace Database\Seeders;

use App\Models\Link;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Adicionar links de exemplo para o primeiro usuário
        $user = User::first();
        
        if ($user) {
            $links = [
                [
                    'title' => 'Meu Portfolio',
                    'url' => 'https://example.com/portfolio',
                    'icon' => 'website',
                    'description' => 'Veja meus trabalhos e projetos',
                    'position' => 0,
                    'active' => true,
                ],
                [
                    'title' => 'GitHub',
                    'url' => 'https://github.com',
                    'icon' => 'github',
                    'description' => 'Meus projetos no GitHub',
                    'position' => 1,
                    'active' => true,
                ],
                [
                    'title' => 'LinkedIn',
                    'url' => 'https://linkedin.com',
                    'icon' => 'linkedin',
                    'description' => 'Conecte-se comigo',
                    'position' => 2,
                    'active' => true,
                ],
                [
                    'title' => 'Instagram',
                    'url' => 'https://instagram.com',
                    'icon' => 'instagram',
                    'description' => 'Siga meus projetos',
                    'position' => 3,
                    'active' => true,
                ],
            ];

            foreach ($links as $link) {
                Link::create([
                    'user_id' => $user->id,
                    ...$link
                ]);
            }
        }
    }
}

