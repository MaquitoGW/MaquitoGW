<?php

namespace App\Http\Controllers\admin;

use App\Models\Customization;
use Illuminate\Http\Request;

class CustomizationController extends AdminController
{
    public function index()
    {
        return view('admin.customization', [
            'customization' => fn($config, $else = null) => $this->search($config, $else),
            'search' => fn($config, $else = null) => $this->search($config, $else),
        ]);
    }

    public function update(Request $request)
    {
        if ($request->boolean('reset_theme_colors')) {
            Customization::whereIn('config', $this->themeConfigKeys())->delete();

            return back()
                ->with('success', 'Cores padrao restauradas com sucesso')
                ->withCookie(cookie('app-theme', 'system', 525600));
        }

        $encoded = $request->query('encode') === 'true';

        foreach ($request->except('_token') as $config => $value) {
            if (in_array($config, $this->ignoredThemeBackgroundKeys(), true)) {
                continue;
            }

            Customization::updateOrCreate(
                ['config' => $config],
                [
                    'value' => $encoded ? base64_encode((string) $value) : $value,
                    'encode' => $encoded,
                ]
            );
        }

        $response = back()->with('success', 'Configuracoes atualizadas com sucesso');

        if ($request->filled('theme_mode')) {
            $response->withCookie(cookie('app-theme', $request->input('theme_mode'), 525600));
        }

        return $response;
    }

    private function themeConfigKeys(): array
    {
        return [
            'theme_mode',
            'admin_theme',
            'theme_selection',
            'color_primary',
            'color_secondary',
            'theme_color_primary_light',
            'admin_color_primary_light',
            'theme_color_text',
            'admin_color_text',
            'theme_color_heading',
            'admin_color_heading',
            'theme_color_muted',
            'admin_color_text_muted',
            'theme_color_button_text',
            'theme_color_border',
            'admin_color_border',
            'admin_color_background',
            'admin_color_sidebar',
            'admin_color_card',
            'admin_color_active',
            'admin_color_surface_muted',
            'admin_color_input',
            'admin_color_surface_hover',
        ];
    }

    private function ignoredThemeBackgroundKeys(): array
    {
        return [
            'admin_color_background',
            'admin_color_sidebar',
            'admin_color_card',
            'admin_color_active',
            'admin_color_surface_muted',
            'admin_color_input',
            'admin_color_surface_hover',
        ];
    }

    public function updateImages(Request $request)
    {
        foreach ($request->except('_token') as $config => $e) {
            if ($request->hasFile($config) && $request->file($config)->isValid()) {
                $image = $request->file($config);
                $imageName = md5($image->getClientOriginalName() . strtotime('now')) . '.' . $image->extension();
                $path = '/storage/images/';
                $image->move(public_path($path), $imageName);

                Customization::updateOrCreate(
                    ['config' => $config],
                    ['value' => $path . $imageName]
                );
            }
        }

        return back()->with('success', 'Imagens atualizadas com sucesso');
    }
}
