<?php

namespace App\Models;

use App\Models\Concerns\ResolvesImageUrl;
use App\Support\HomeSections;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['slug', 'title', 'content'])]
class Page extends Model
{
    use ResolvesImageUrl;

    protected function casts(): array
    {
        return [
            'content' => 'array',
        ];
    }

    /**
     * Read a plain value out of the content JSON without the null-coalescing
     * noise repeating in every view.
     */
    public function get(string $key, mixed $default = ''): mixed
    {
        return data_get($this->content, $key, $default);
    }

    /**
     * A key holding a repeatable list — cards, stats, paragraphs.
     *
     * Always an array, so a @foreach over a key that was never saved renders
     * nothing instead of raising "foreach() argument must be of type array".
     * That matters because content is a JSON blob: adding a field to an admin
     * form leaves every existing row without it until someone hits Save.
     */
    public function list(string $key): array
    {
        $value = data_get($this->content, $key);

        return is_array($value) ? $value : [];
    }

    /**
     * Same, for a key holding an image path — resolved to a usable URL the
     * way the model image columns are.
     */
    public function imageUrl(string $key): ?string
    {
        return $this->resolveImageUrl(data_get($this->content, $key));
    }

    /**
     * A homepage section's eyebrow or heading, falling back to the original
     * copy so a section never renders blank if its key was never saved.
     */
    public function section(string $key, string $field = 'heading'): ?string
    {
        $stored = data_get($this->content, "sections.{$key}.{$field}");

        return $stored !== null && $stored !== ''
            ? $stored
            : HomeSections::default($key, $field);
    }
}
