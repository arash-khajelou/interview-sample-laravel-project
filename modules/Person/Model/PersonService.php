<?php

namespace Modules\Person\Model;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Modules\Person\Exceptions\PersonNotFoundException;

class PersonService
{
    /**
     * @throws PersonNotFoundException
     */
    public static function getById(int $id): PersonDAO
    {
        $person = PersonDAO::find($id);
        if ($person == null)
            throw new PersonNotFoundException("The person with id $id not found.");
        return $person;
    }

    public static function createPerson(bool   $is_active,
                                        string $first_name,
                                        string $last_name,
                                        string $social_id,
                                        Carbon $birth_date,
                                        string $mobile_number,
                                        string $mobile_description,
                                        string $email,
                                        string $email_description): ?PersonDAO
    {
        return PersonDAO::create([
            "is_active" => $is_active,
            "first_name" => $first_name,
            "last_name" => $last_name,
            "social_id" => $social_id,
            "birth_date" => $birth_date,
            "mobile_number" => $mobile_number,
            "mobile_description" => $mobile_description,
            "email" => $email,
            "email_description" => $email_description
        ]);
    }

    /**
     * @throws PersonNotFoundException
     */
    public static function updatePerson(int    $id,
                                        bool   $is_active,
                                        string $first_name,
                                        string $last_name,
                                        string $social_id,
                                        Carbon $birth_date,
                                        string $mobile_number,
                                        string $mobile_description,
                                        string $email,
                                        string $email_description): bool
    {
        $person = PersonService::getById($id);
        return $person->update([
            "is_active" => $is_active,
            "first_name" => $first_name,
            "last_name" => $last_name,
            "social_id" => $social_id,
            "birth_date" => $birth_date,
            "mobile_number" => $mobile_number,
            "mobile_description" => $mobile_description,
            "email" => $email,
            "email_description" => $email_description
        ]);
    }

    /**
     * @throws PersonNotFoundException
     */
    public static function deletePerson(int $id): ?bool
    {
        $person = PersonService::getById($id);
        return $person->delete();
    }

    /**
     * @return Collection|PersonDAO[]
     */
    public static function getAll(): Collection|array
    {
        return PersonDAO::all();
    }

    /**
     * @throws PersonNotFoundException
     */
    public static function activatePerson(int $id): void
    {
        $person = PersonService::getById($id);
        if ($person->is_active)
            return;
        $person->update([
            "is_active" => true
        ]);
    }

    /**
     * @throws PersonNotFoundException
     */
    public static function deactivatePerson(int $id): void
    {
        $person = PersonService::getById($id);
        if (!$person->is_active)
            return;
        $person->update([
            "is_active" => false
        ]);
    }
}
