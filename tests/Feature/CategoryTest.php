<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Scopes\IsActiveScope;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    public function testInsert()
    {
        $category = new Category();
        $category->id = '001';
        $category->name = 'Gadget';
        $result = $category->save();

        self::assertTrue($result);
    }

    public function testInsertManyCategories()
    {
        $categories = [];
        for($i = 1; $i <= 10; $i++){
            $categories[] = [
                'id'=> "00$i",
                'name' => "Name $i",
                'is_active' => true
            ];
        }
        $result = Category::insert($categories);
        self::assertTrue($result);

        $total = Category::count();
        self::assertEquals(10 , $total);
    }

    public function testFind()
    {
        $this->seed(CategorySeeder::class);

        $category = Category::find('001');
        self::assertNotNull($category);
        self::assertEquals('001', $category->id);
        self::assertEquals('Gadget', $category->name);
        self::assertEquals('Gadget Category', $category->description);
    }

    public function testUpdate()
    {
        $this->seed(CategorySeeder::class);
        $category = Category::find('001');
        $category->name = "Food";
        $result = $category->update();
        self::assertTrue($result);
    }

    public function testSelect()
    {
        for($i = 1; $i <= 10; $i++){
            if($i > 5){
                $category = new Category();
                $category->id = "$i";
                $category->name = "Category $i";
                $category->description = "Description $i";
                $category->is_active = true;
                $category->save(); 
            }else{
                $category = new Category();
                $category->id = "$i";
                $category->name = "Category $i";
                $category->is_active = true;
                $category->save();
            }
        }

        $categories = Category::whereNull('description')->get();
        self::assertEquals(5, count($categories));
        $categories->each(function($item){
            self::assertNull($item->description);
            // kalo pengen update description yang masih null
            $item->description = "Updated Description";
            $item->save(); // atau pake update() juga bisa
        });
    }

    public function testUpdateMany()
    {
        $categories = [];
        for($i = 1; $i <= 10; $i++){
            $categories[] = [
                'id' => "$i",
                'name' => "Category $i",
                'is_active' => true
            ];
        }
        $result = Category::insert($categories);
        self::assertTrue($result);

        Category::whereNull('description')->update([
            'description' => 'Updated Description'
        ]);
        $result = Category::where('description', 'Updated Description')->count();
        self::assertEquals(10, $result);
        $result = Category::find(2);
        self::assertEquals(2, $result->id);
    }

    public function testDelete()
    {
        $this->seed(CategorySeeder::class);
        $category = Category::find('001');
        $result = $category->delete();
        self::assertTrue($result);

        $total = Category::count();
        self::assertEquals(1, $total);
    }

    public function testDeleteMany()
    {
        $categories = [];
        for($i = 1; $i <= 10; $i++){
            $categories[] = [
                'id' => "$i",
                'name' => "Category $i",
                'is_active' => true
            ];
        }
        $result = Category::insert($categories);
        self::assertTrue($result);

        $total = Category::count();
        self::assertEquals(10, $total);

        Category::whereNull('description')->delete();
        $total = Category::count();
        self::assertEquals(0, $total);
    }

    public function testRemoveGlobalScope()
    {
        $category = new Category();
        $category->id = '001';
        $category->name = 'Food';
        $category->description = 'Food Category';
        $category->is_active = false;
        $category->save();

        $category = Category::query()->find('001');
        self::assertNull($category);
        // self::assertNotNull($category);
        // $category->each(function($item){
        //     // var_dump((bool)$item->is_active);
        //     $item->is_active = false;
        //     $item->save();
        // });
        // $category = Category::query()->find('001');
        // self::assertNull($category);

        $category = Category::withoutGlobalScopes([IsActiveScope::class])->find('001');
        self::assertNotNull($category);
    }

}
