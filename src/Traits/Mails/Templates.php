<?php

declare(strict_types=1);

namespace Shopper\Framework\Traits\Mails;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

use function dirname;

trait Templates
{
    public static function getTemplatesFile(): string
    {
        $file = config('shopper.mails.mailables_dir') . 'templates.json';
        if (! file_exists($file)) {
            if (! file_exists(config('shopper.mails.mailables_dir'))) {
                mkdir(config('shopper.mails.mailables_dir'));
            }
            file_put_contents($file, '[]');
        }

        return $file;
    }

    public static function deleteTemplate($templateSlug): bool
    {
        $template = self::getTemplates()
            ->where('template_slug', $templateSlug)->first();

        if ($template !== null) {
            self::saveTemplates(self::getTemplates()->reject(fn ($value) => $value->template_slug === $template->template_slug));

            $template_view = 'shopper::mails.templates.' . $templateSlug;
            $template_plaintext_view = $template_view . '_plain_text';

            if (View::exists($template_view)) {
                unlink(View($template_view)->getPath());

                if (View::exists($template_plaintext_view)) {
                    unlink(View($template_plaintext_view)->getPath());
                }

                return true;
            }
        }

        return false;
    }

    public static function saveTemplates(Collection $templates): void
    {
        file_put_contents(self::getTemplatesFile(), $templates->toJson());
    }

    public static function updateTemplate(Request $request): \Illuminate\Http\JsonResponse
    {
        $template = self::getTemplates()
            ->where('template_slug', $request->templateslug)->first();

        if (! $template) {
            return response()->json([
                'status' => 'failed',
                'message' => __('Template not found'),
            ]);
        }

        if (! preg_match('/^[a-zA-Z0-9-_\\s]+$/', (string) $request->title)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Template name not valid',
            ]);
        }

        $templateName = Str::camel(preg_replace('/\s+/', '_', (string) $request->title));

        if (self::getTemplates()->contains('template_slug', '=', $templateName)) {
            return response()->json([
                'status' => 'failed',
                'message' => __('Template name already exists'),
            ]);
        }

        $oldForm = self::getTemplates()->reject(fn ($value) => $value->template_slug === $template->template_slug);
        $newForm = array_merge($oldForm->toArray(), [
            [...(array) $template, 'template_slug' => $templateName, 'template_name' => $request->title, 'template_description' => $request->description],
        ]);

        self::saveTemplates(collect($newForm));

        $template_view = 'shopper::mails.templates.' . $request->templateslug;
        $template_plaintext_view = $template_view . '_plain_text';

        if (View::exists($template_view)) {
            $viewPath = View($template_view)->getPath();

            rename($viewPath, dirname((string) $viewPath) . "/{$templateName}.blade.php");

            if (View::exists($template_plaintext_view)) {
                $textViewPath = View($template_plaintext_view)->getPath();

                rename($textViewPath, dirname((string) $viewPath) . "/{$templateName}_plain_text.blade.php");
            }
        }

        return response()->json([
            'status' => 'ok',
            'message' => __('Updated Successfully'),
            'template_url' => route('viewTemplate', ['templatename' => $templateName]),
        ]);
    }

    public static function getTemplate(string $templateSlug): Collection
    {
        $template = self::getTemplates()
            ->where('template_slug', $templateSlug)->first();

        if ($template !== null) {
            $template_view = 'templates.' . $template->template_slug;
            $template_plaintext_view = $template_view . '_plain_text';

            if (View::exists($template_view)) {
                $viewPath = View($template_view)->getPath();
                $textViewPath = View($template_plaintext_view)->getPath();

                return collect([
                    'template' => self::templateComponentReplace(file_get_contents($viewPath), true),
                    'plain_text' => View::exists($template_plaintext_view) ? file_get_contents($textViewPath) : '',
                    'slug' => $template->template_slug,
                    'name' => $template->template_name,
                    'description' => $template->template_description,
                    'template_type' => $template->template_type,
                    'template_view_name' => $template->template_view_name,
                    'template_skeleton' => $template->template_skeleton,
                ]);
            }
        }

        return collect();
    }

    public static function getTemplates(): Collection
    {
        return collect(json_decode(file_get_contents(self::getTemplatesFile()), null, 512, JSON_THROW_ON_ERROR));
    }

    /**
     * Create template from the request.
     */
    public static function createTemplate(Request $request): ?RedirectResponse
    {
        if (! preg_match('/^[a-zA-Z0-9-_\\s]+$/', (string) $request->template_name)) {
            session()->flash('error', __('Template name not valid'));

            return null;
        }

        $view = 'shopper::templates.' . $request->template_name;

        $templateName = Str::camel(preg_replace('/\s+/', '_', (string) $request->template_name));

        if (! view()->exists($view) && ! self::getTemplates()->contains('template_slug', '=', $templateName)) {
            self::saveTemplates(self::getTemplates()
                ->push([
                    'template_name' => $request->template_name,
                    'template_slug' => $templateName,
                    'template_description' => $request->template_description,
                    'template_type' => $request->template_type,
                    'template_view_name' => $request->template_view_name,
                    'template_skeleton' => $request->template_skeleton,
                ]));

            $dir = resource_path('views/vendor/shopper/templates');

            if (! File::isDirectory($dir)) {
                File::makeDirectory($dir, 0755, true);
            }

            file_put_contents($dir . "/{$templateName}.blade.php", self::templateComponentReplace($request->content));

            // file_put_contents($dir."/{$templatename}_plain_text.blade.php", $request->plain_text);

            session()->flash('success', __('Template successfully created!'));

            return redirect()->route('shopper.settings.mails');
        }

        session()->flash('error', __('Template not created'));

        return null;
    }

    public static function getTemplateSkeletons(): Collection
    {
        return collect(config('shopper.mails.skeletons'));
    }

    public static function getTemplateSkeleton($type, $name, $skeleton)
    {
        $skeletonView = "shopper::mails.skeletons.{$type}.{$name}.{$skeleton}";

        if (view()->exists($skeletonView)) {
            $skeletonViewPath = View($skeletonView)->getPath();
            $templateContent = file_get_contents($skeletonViewPath);

            return [
                'type' => $type,
                'name' => $name,
                'skeleton' => $skeleton,
                'template' => self::templateComponentReplace($templateContent, true),
                'view' => $skeletonView,
                'view_path' => $skeletonViewPath,
            ];
        }
    }

    public static function markdownedTemplateToView($save = true, $content = '', $viewPath = '', $template = false): array|bool|string|null
    {
        if ($template && View::exists('shopper::mails.templates.' . $viewPath)) {
            $viewPath = View('shopper::mails.templates.' . $viewPath)->getPath();
        }

        $replaced = self::templateComponentReplace($content);

        if (! $save) {
            return $replaced;
        }

        return ! (file_put_contents($viewPath, $replaced) === false);
    }

    public static function previewMarkdownViewContent($simpleview, $content, $viewName, $template = false, $namespace = null)
    {
        $previewtoset = self::markdownedTemplateToView(false, $content);
        $dir = dirname(__FILE__, 3) . '/resources/views/draft';
        $viewName = $template ? $viewName . '_template' : $viewName;

        if (file_exists($dir)) {
            file_put_contents($dir . "/{$viewName}.blade.php", $previewtoset);

            if ($template) {
                $instance = null;
            } else {
                if (self::handleMailableViewDataArgs($namespace) !== null) {
                    $instance = self::handleMailableViewDataArgs($namespace);
                } else {
                    $instance = new $namespace;
                }
            }

            return self::renderPreview($simpleview, 'shopper::mails.draft.' . $viewName, $template, $instance);
        }

        return false;
    }

    /**
     * @throws \Throwable
     */
    public static function previewMarkdownHtml($instance, $view): string
    {
        return self::renderPreview($instance, $view);
    }

    public static function getMailableTemplateData($mailableName)
    {
        $mailable = self::getMailable('name', $mailableName);

        if ($mailable->isEmpty()) {
            return false;
        }

        $templateData = collect($mailable->first())
            ->only([
                'markdown',
                'view_path',
                'text_view_path',
                'text_view',
                'view_data',
                'data',
                'namespace',
            ])
            ->all();

        $templateExists = $templateData['view_path'] !== null;
        $textTemplateExists = $templateData['text_view_path'] !== null;

        if ($templateExists) {
            return collect($templateData)->union([

                'text_template' => $textTemplateExists ? file_get_contents($templateData['text_view_path']) : null,
                'template' => file_get_contents($templateData['view_path']),
                'markdowned_template' => self::markdownedTemplate($templateData['view_path']),
                'template_name' => $templateData['markdown'] ?? $templateData['data']->view,
                'is_markdown' => $templateData['markdown'] !== null ? true : false,

            ])->all();
        }

        return $templateData;
    }

    protected static function templateComponentReplace($content, $reverse = false): array|string|null
    {
        if ($reverse) {
            $patterns = [
                '/@component/i',
                '/@endcomponent/i',
                '/@yield/',
                '/@section/',
                '/@endsection/',
                '/@extends/',
                '/@parent/',
                '/@slot/',
                '/@endslot/',
            ];

            $replacements = [
                '[component]: # ',
                '[endcomponent]: # ',
                '[yield]: # ',
                '[section]: # ',
                '[endsection]: # ',
                '[extends]: # ',
                '[parent]: # ',
                '[slot]: # ',
                '[endslot]: # ',
            ];
        } else {
            $patterns = [
                '/\[component]:\s?#\s?/i',
                '/\[endcomponent]:\s?#\s?/i',
                '/\[yield]:\s?#\s?/i',
                '/\[section]:\s?#\s?/i',
                '/\[endsection]:\s?#\s?/i',
                '/\[extends]:\s?#\s?/i',
                '/\[parent]:\s?#\s?/i',
                '/\[slot]:\s?#\s?/i',
                '/\[endslot]:\s?#\s?/i',
            ];

            $replacements = [
                '@component',
                '@endcomponent',
                '@yield',
                '@section',
                '@endsection',
                '@extends',
                '@parent',
                '@slot',
                '@endslot',
            ];
        }

        return preg_replace($patterns, $replacements, (string) $content);
    }

    protected static function markdownedTemplate($viewPath): array|string|null
    {
        $viewContent = file_get_contents($viewPath);

        return self::templateComponentReplace($viewContent, true);
    }
}
