<?php

namespace App\Http\Controllers;

use App\Helpers\PaginationQuery;
use App\Services\AuthService;
use App\Services\SubjectService;
use App\Transformers\SubjectTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class SubjectController extends Controller
{
    use Helpers;

    private $authService;
    private $subjectService;

    public function __construct(AuthService $authService,
                                SubjectService $subjectService) {
        $this->authService = $authService;
        $this->subjectService = $subjectService;
    }

    public function create(Request $request) {
        $this->authService->authorizeUser(["admin", "akashic", "sservice"]);

        $this->validate(
            $request,
            [
                'name'         => 'required|string|min:2|max:200',
            ]
        );

        $user = $this->subjectService->create($request);
        return $this->response->item($user, new SubjectTransformer());
    }

    public function findById(Request $request, $subject_id)
    {
        $subject = $this->subjectService->findById($subject_id);

        if ($subject == null) {
            throw new BadRequestHttpException("Subject with given id does not exist.");
        }

        return $this->response->item($subject, new SubjectTransformer());
    }

    public function findAll(Request $request)
    {
        $paginationQuery = (new PaginationQuery())->assemble($request);
        $paginationData = $this->subjectService->findAll($paginationQuery);

        return $paginationData->createResponse(new SubjectTransformer());
    }

    public function update(Request $request, $subject_id) {
        $this->authService->authorizeUser(["akashic", "admin"]);

        $this->validate(
            $request,
            [
                'name'         => 'required|string|min:2|max:200',
            ]
        );

        $uc = $this->subjectService->update($request, $subject_id);
        return $this->response->item($uc, new SubjectTransformer());
    }

    public function delete($subject_id) {
        $this->authService->authorizeUser(["admin", "akashic", "sservice"]);

        $this->subjectService->delete($subject_id);
    }
}
