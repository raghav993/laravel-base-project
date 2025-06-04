<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory as Faker;
class DynamicModelSeeder extends Seeder
{
    protected $faker;
    protected $foreignKeyCache = [];
    public function __construct()
    {
        $this->faker = Faker::create();
    }
    public function runForModel(string $modelClass, int $count = 10)
    {
        if (!class_exists($modelClass)) {
            throw new \InvalidArgumentException("Model class {$modelClass} does not exist.");
        }
        $model = new $modelClass;
        $table = $model->getTable();
        $columns = DB::getSchemaBuilder()->getColumnListing($table);
        $data = [];
        for ($i = 0; $i < $count; $i++) {
            $row = [];
            foreach ($columns as $column) {
                if ($column === $model->getKeyName()) {
                    continue;
                }
                if (in_array($column, ['created_at', 'updated_at'])) {
                    $row[$column] = now();
                    continue;
                }
                if ($this->isForeignKey($table, $column)) {
                    $row[$column] = $this->getRandomForeignKeyId($table, $column);
                    continue;
                }
                $fakerValue = $this->getFakerValueForColumn($column);
                if ($fakerValue !== null) {
                    $row[$column] = $fakerValue;
                }
            }
            $data[] = $row;
        }
        DB::table($table)->insert($data);
        $this->command->info("Inserted {$count} records into {$table} table.");
    }
    protected function isForeignKey($table, $column)
    {
        return str_ends_with($column, '_id');
    }
    protected function getRandomForeignKeyId($table, $column)
    {
        $referencedTable = str_replace('_id', '', $column) . 's';
        if (!DB::getSchemaBuilder()->hasTable($referencedTable)) {
            return null;
        }
        return DB::table($referencedTable)->inRandomOrder()->value('id');
    }
    protected function getFakerValueForColumn($column)
    {
        $column = strtolower($column);
        if (str_ends_with($column, '_at') || str_contains($column, 'datetime')) {
            return $this->faker->dateTime();
        }
        if (str_contains($column, 'email')) {
            return $this->faker->unique()->safeEmail();
        }
        if (str_contains($column, 'password')) {
            return bcrypt('password');
        }
        if (str_contains($column, 'token')) {
            return bin2hex(random_bytes(20));
        }
        if (str_contains($column, 'name')) {
            return $this->faker->name();
        }
        if (str_contains($column, 'phone')) {
            return $this->faker->phoneNumber();
        }
        if (str_contains($column, 'address')) {
            return $this->faker->address();
        }
        if (str_contains($column, 'city')) {
            return $this->faker->city();
        }
        if (str_contains($column, 'state')) {
            return $this->faker->state();
        }
        if (str_contains($column, 'zip') || str_contains($column, 'postcode')) {
            return $this->faker->postcode();
        }
        if (str_contains($column, 'country')) {
            return $this->faker->country();
        }
        if (str_contains($column, 'description') || str_contains($column, 'content') || str_contains($column, 'body')) {
            return $this->faker->text();
        }
        if (str_contains($column, 'price') || str_contains($column, 'amount')) {
            return $this->faker->randomFloat(2, 10, 1000);
        }
        if (str_contains($column, 'quantity')) {
            return $this->faker->numberBetween(1, 100);
        }
        if (str_contains($column, 'status') || str_contains($column, 'active') || str_contains($column, 'published') || str_contains($column, 'is_') || str_contains($column, 'has_')) {
            return $this->faker->boolean();
        }
        if (str_contains($column, 'date')) {
            return $this->faker->date();
        }
        if (str_contains($column, 'time')) {
            return $this->faker->time();
        }
        if (str_contains($column, 'image') || str_contains($column, 'avatar')) {
            return $this->faker->imageUrl();
        }
        if (str_contains($column, 'url') || str_contains($column, 'link')) {
            return $this->faker->url();
        }
        if (str_contains($column, 'title')) {
            return $this->faker->sentence();
        }
        if (str_contains($column, 'slug')) {
            return $this->faker->slug();
        }
        return null;
    }
}
