<?php

namespace App\Tables;

use App\Models\Category;
use Illuminate\Http\Request;
use \Maatwebsite\Excel\Excel;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\AbstractTable;
use ProtoneMedia\Splade\Facades\Toast;

class Categories extends AbstractTable
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
        return Category::query();
    }

    /**
     * Configure the given SpladeTable.
     *
     * @param \ProtoneMedia\Splade\SpladeTable $table
     * @return void
     */
    public function configure(SpladeTable $table)
    {
        $table
            ->withGlobalSearch(columns: ['name'])
            ->column(key: 'name', canBeHidden: false, sortable: true)
            ->column(key: 'slug', canBeHidden: true, sortable: true)
            ->column(key: 'created_at', canBeHidden: true, sortable: true)
            ->column('action', exportAs: false)
            // ->export(label: 'Posts Excel')
            ->bulkAction(
                label: 'Touch timestamp',
                each: fn (Category $category) => $category->touch(),
                before: fn () => info('Touching the selected projects'),
                after: fn () => Toast::info('Timestamps updated!')
            )
            ->bulkAction(
                label: 'Delete Categories',
                each: fn (Category $category) => $category->delete(),
                after: fn () => Toast::title('Categories deleted.')
            )
            ->paginate(12);

            // ->searchInput()
            // ->selectFilter()
            // ->withGlobalSearch()
            // ->bulkAction()
            // ->export()
    }
}
