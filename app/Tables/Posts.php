<?php

namespace App\Tables;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\QueryBuilder;
use ProtoneMedia\Splade\AbstractTable;
use ProtoneMedia\Splade\Facades\Toast;
use Spatie\QueryBuilder\AllowedFilter;

class Posts extends AbstractTable
{
    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the user is authorized to perform bulk actions and exports.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        return true;
    }

    /**
     * The resource or query builder.
     *
     * @return mixed
     */
    public function for()
    {
        return Post::query();
    }

    /**
     * Configure the given SpladeTable.
     *
     * @param \ProtoneMedia\Splade\SpladeTable $table
     * @return void
     */
    public function configure(SpladeTable $table)
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                Collection::wrap($value)->each(function ($value) use ($query) {
                    $query
                        ->orWhere('title', 'LIKE', "%{$value}%")
                        ->orWhere('slug', 'LIKE', "%{$value}%");
                });
            });
        });

        $posts = QueryBuilder::for(Post::class)
            ->defaultSort('title')
            ->allowedSorts(['title', 'slug', 'created_at'])
            ->allowedFilters(['category_id', 'title', 'slug', 'created_at', $globalSearch]);

        $categories = Category::pluck('name', 'id')->toArray();

        $table
            ->withGlobalSearch(columns: ['title'])
            ->column(key: 'title', canBeHidden: false, sortable: true)
            ->column(key: 'slug', canBeHidden: true, sortable: true)
            ->column(key: 'created_at', canBeHidden: true, sortable: true)
            ->column('action', exportAs: false)
            ->selectFilter('category_id', $categories)
            ->withGlobalSearch(columns: ['title'])
            ->export(label: 'Posts Excel')
            ->bulkAction(
                label: 'Touch timestamp',
                each: fn (Post $post) => $post->touch(),
                before: fn () => info('Touching the selected projects'),
                after: fn () => Toast::info('Timestamps updated!')
            )
            ->bulkAction(
                label: 'Delete Categories',
                each: fn (Post $post) => $post->delete(),
                after: fn () => Toast::title('Posts deleted.')
            )
            ->paginate(12);

            // ->searchInput()
            // ->selectFilter()
            // ->withGlobalSearch()
            // ->bulkAction()
            // ->export()
    }
}
