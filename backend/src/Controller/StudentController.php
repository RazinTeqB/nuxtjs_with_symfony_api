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
        $student = new Student();
        $data = json_decode($request->getContent(), true);

        $student->setName($data["name"]);
        $student->setEmail($data["email"]);
        $student->setGender($data["gender"]);
        $dob = $data["dob"];
        if (!empty(trim($dob))) {
            $student->setDob(new \DateTime($dob));
        }
        $uploadFile = $request->files->get("image");
        if ($uploadFile) {
            // throw new BadRequestHttpException("No file uploaded");
            $student->setImage($fileUploader->upload($uploadFile));
        }

        return $student;
    }
}
