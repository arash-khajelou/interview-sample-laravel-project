<?php

namespace Modules\Person\Controller;

use Carbon\Carbon;
use Common\BaseController;
use Common\MessageFactory;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Person\Exceptions\PersonNotFoundException;
use Modules\Person\Model\PersonService;
use Modules\Person\Requests\StorePersonRequest;
use Modules\Person\Requests\UpdatePersonRequest;

class PersonController extends BaseController
{
    public function index(): JsonResponse
    {
        $persons = PersonService::getAll();
        return MessageFactory::jsonResponse([], 200, $persons);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $person = PersonService::getById($id);
            return MessageFactory::jsonResponse([], 200, $person);
        } catch (PersonNotFoundException $e) {
            return MessageFactory::jsonResponse(["api.person.query.not_found"], 404, []);
        }
    }

    public function store(StorePersonRequest $request): JsonResponse
    {
        try {
            $person = PersonService::createPerson(
                $request->get("is_active"),
                $request->get("first_name"),
                $request->get("last_name"),
                $request->get("social_id"),
                Carbon::createFromFormat("Y-m-d", $request->get("birth_date")),
                $request->get("mobile_number"),
                $request->get("mobile_description"),
                $request->get("email"),
                $request->get("email_description"),
            );
            return MessageFactory::jsonResponse(["api.person.create.success"], 200, $person);
        } catch (Exception $e) {
            return MessageFactory::jsonResponse(["api.person.create.failed"], 400, []);
        }

    }

    public function update(UpdatePersonRequest $request, int $id): JsonResponse
    {
        try {
            PersonService::updatePerson(
                $id,
                $request->get("is_active"),
                $request->get("first_name"),
                $request->get("last_name"),
                $request->get("social_id"),
                Carbon::createFromFormat("Y-m-d", $request->get("birth_date")),
                $request->get("mobile_number"),
                $request->get("mobile_description"),
                $request->get("email"),
                $request->get("email_description"),
            );
            return MessageFactory::jsonResponse(["api.person.update.success" => ["id" => $id]], 200, []);
        } catch (PersonNotFoundException $e) {
            return MessageFactory::jsonResponse(["api.person.update.not_found" => ["id" => $id]], 400, []);
        } catch (Exception $e) {
            return MessageFactory::jsonResponse(["api.person.update.failed" => ["id" => $id], $e->getMessage()], 400, []);
        }
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        try {
            PersonService::deletePerson($id);
            return MessageFactory::jsonResponse(["api.person.delete.success" => ["id" => $id]], 200, []);
        } catch (PersonNotFoundException $e) {
            return MessageFactory::jsonResponse(["api.person.delete.not_found" => ["id" => $id]], 400, []);
        } catch (Exception $e) {
            return MessageFactory::jsonResponse(["api.person.delete.failed" => ["id" => $id]], 400, []);
        }
    }

    public function activate(Request $request, int $id): JsonResponse
    {
        try {
            PersonService::activatePerson($id);
            return MessageFactory::jsonResponse(["api.person.activation.success" => ["id" => $id]], 200, []);
        } catch (PersonNotFoundException $e) {
            return MessageFactory::jsonResponse(["api.person.activation.not_found" => ["id" => $id]], 400, []);
        } catch (Exception $e) {
            return MessageFactory::jsonResponse(["api.person.activation.failed" => ["id" => $id]], 400, []);
        }
    }

    public function deactivate(Request $request, int $id): JsonResponse
    {
        try {
            PersonService::deactivatePerson($id);
            return MessageFactory::jsonResponse(["api.person.deactivation.success" => ["id" => $id]], 200, []);
        } catch (PersonNotFoundException $e) {
            return MessageFactory::jsonResponse(["api.person.deactivation.not_found" => ["id" => $id]], 400, []);
        } catch (Exception $e) {
            return MessageFactory::jsonResponse(["api.person.deactivation.failed" => ["id" => $id]], 400, []);
        }
    }
}
