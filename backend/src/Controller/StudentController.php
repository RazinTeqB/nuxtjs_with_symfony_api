<?php

namespace App\Controller;

use App\Entity\Student;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
final class StudentController extends AbstractController
{
    public function __invoke(Request $request,  FileUploader $fileUploader): Student
    {
        $uploadFile = $request->files->get("image");
        if (!$uploadFile) {
            throw new BadRequestHttpException("No file uploaded");
        }

        $student = new Student();
        $student->setName($request->get("name"));
        $student->setEmail($request->get("email"));
        $student->setGender($request->get("gender"));
        $dob = $request->get("dob");
        if (!empty(trim($dob))) {
            $student->setDob(new \DateTime($dob));
        }
        $student->setImage($fileUploader->upload($uploadFile));

        return $student;
    }
}
