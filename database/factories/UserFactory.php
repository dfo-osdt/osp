<?php

namespace Database\Factories;

use App\Enums\Permissions\UserRole;
use App\Models\Author;
use App\Models\Expertise;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $fistName = $this->faker->firstName();
        $lastName = $this->faker->lastName();
        $email = $fistName.'.'.$lastName.'@example.com';

        return [
            'first_name' => $fistName,
            'last_name' => $lastName,
            'email' => $email,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'active' => true,
            'locale' => 'en',
        ];
    }

    /**
     * A factory for a user with a DFO email address
     */
    public function isFromDFO()
    {
        return $this->state(function (array $attributes) {
            return [
                'email' => $attributes['first_name'].'.'.$attributes['last_name'].'@dfo-mpo.gc.ca',
            ];
        });
    }

    /**
     * Every user has an author record
     */
    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            // does an author record already exist for this user?
            $author = Author::updateOrCreate(
                [
                    'email' => $user->email,
                    'user_id' => null,
                ],
                [
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'organization_id' => 1,
                    'user_id' => $user->id,
                ]
            );

            $author->expertises()->attach(Expertise::factory()->count(random_int(1, 3))->create());

            // by default a new user is an author
            $user->assignRole('author');
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'active' => false,
                'email_verified_at' => null,
                'email_verification_token' => User::generateEmailVerificationToken(),
            ];
        });
    }

    /**
     * An invited user is a user that has been invited to join the system
     */
    public function invited()
    {
        return $this->state(function (array $attributes) {
            return [
                'active' => false,
                'email_verified_at' => null,
                'email_verification_token' => null,
                'password' => Str::random(20),
                'new_password_required' => true,
            ];
        });
    }

    /**
     * Assign roles to the user
     *
     * @param  UserRole[]  $roles
     */
    public function withRoles(array $roles)
    {
        $roleValues = collect($roles)->map(fn (UserRole $role) => $role->value)->toArray();

        return $this->afterCreating(function (User $user) use ($roles) {
            $user->assignRole($roles);
        });
    }

    /**
     * User is an editor
     */
    public function editor()
    {
        return $this->withRoles([UserRole::EDITOR]);
    }

    public function chiefEditor()
    {
        return $this->withRoles([UserRole::CHIEF_EDITOR]);
    }
}
