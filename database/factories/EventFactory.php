<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => rand(1,50),
            'event_name' => $this->faker->name,
            'description' => $this->faker->sentence,
            'event_date' => now(),
            'status' => 1,
            'created_at' => now(),
        ];
    }
}
