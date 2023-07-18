<?php

use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\DuskTestCase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(TestCase::class)->in('Feature');
uses(TestCase::class)->in('Unit');
uses(TestCase::class)->in('Integration');
uses(DuskTestCase::class)->in('Browser');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function loginAsUser(User $user = null): User
{
    $user = $user ?? User::factory()->create();
    test()->actingAs($user);

    return $user;
}

function mockAS400()
{
    DB::shouldReceive([
        'connection' => DB::shouldReceive('connection')->andReturnSelf(),
        'table' => DB::shouldReceive('table')->andReturnSelf(),
        'select' => DB::shouldReceive('select')->andReturnSelf(),
        'where' => DB::shouldReceive('where')->andReturnSelf(),
        'whereRaw' => DB::shouldReceive('whereRaw')->andReturnSelf(),
        'get' => DB::shouldReceive('get')->andReturn(collect([])),
        'whereIn' => DB::shouldReceive('whereIn')->andReturnSelf(),
        'orderBy' => DB::shouldReceive('orderBy')->andReturnSelf(),
        'groupBy' => DB::shouldReceive('groupBy')->andReturnSelf(),
        'join' => DB::shouldReceive('join')->andReturnSelf(),
        'on' => DB::shouldReceive('on')->andReturnSelf(),
        'leftJoin' => DB::shouldReceive('leftJoin')->andReturnSelf(),
        'whereBetween' => DB::shouldReceive('whereBetween')->andReturnSelf(),
        'whereYear' => DB::shouldReceive('whereYear')->andReturnSelf(),
        'whereNull' => DB::shouldReceive('whereNull')->andReturnSelf(),
        'whereNotIn' => DB::shouldReceive('whereNotIn')->andReturnSelf(),
        'raw' => DB::shouldReceive('raw')->andReturnSelf(),
    ]);
}

// Custom assertions
function assertHasManyUsing($relatedModel, $relationship, $foreignKey): void
{
    expect($relationship)->toBeInstanceOf(HasOne::class);
    expect($relationship->getRelated())->toBeInstanceOf($relatedModel);
    expect($relationship->getForeignKeyName())->toEqual($foreignKey);
    expect(Schema::hasColumns($relationship->getRelated()->getTable(), [$foreignKey]))->toBeTrue();
}

function assertBelongsToUsing($relatedModel, $relationship, $foreignKey): void
{
    expect($relationship)->toBeInstanceOf(BelongsTo::class);
    expect($relationship->getRelated())->toBeInstanceOf($relatedModel);
    expect($foreignKey)->toEqual($relationship->getForeignKeyName());
    expect(Schema::hasColumns($relationship->getParent()->getTable(), [$foreignKey]))->toBeTrue();
}

function disableDownload()
{
    test()->setOutputCallback(fn () => '');
}

// Export helpers
function createExport(string $routeName, string $exportClass, string $action = 'POST', array $params = [])
{
    $mockRequest = Request::create(
        route('exports.export', ['name' => $routeName]), $action, $params);

    $export = app($exportClass);

    $export->export($mockRequest);

    return $export;
}

function getCellValue($column, $row, $sheet)
{
    return $sheet->getCellByColumnAndRow($column, $row)->getValue();
}
