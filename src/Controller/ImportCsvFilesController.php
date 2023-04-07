<?php

namespace App\Controller;




use App\Entity\Site;
use App\Entity\User;
use App\Form\ImportType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\UnavailableStream;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/csv-import-admin', name: 'csv_import_admin_')]
class ImportCsvFilesController extends AbstractController
{

    /**
     * @throws UnavailableStream
     * @throws Exception
     * @throws ORMException
     */
    #[Route('/', name: 'import')]
    public function import(Request $request, EntityManagerInterface $entityManager,UserPasswordHasherInterface $hasher)
    {
        $defaultPassword = 'defaultPassword123';
        $form = $this->createForm(ImportType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $csvFile = $form['importFile']->getData();
            $csvReader = Reader::createFromPath($csvFile->getPathname(), 'r');
            $csvReader->setHeaderOffset(0);


            foreach ($csvReader as $row) {
                $user = new User();
                $site = new Site();

                $user->setSite($site->setName($row['site_id']));
                $user->setEmail($row['email']);
                if (isset($row['roles'])) {
                    $user->setRoles([$row['roles']]);
                } else {
                    $user->setRoles(['ROLE_USER']);
                    if (isset($row['password'])) {
                        $encodedPassword = $hasher->hashPassword($user, $row['password']);
                    } else {
                        $plainPassword = $defaultPassword;
                        $encodedPassword = $hasher->hashPassword($user, $plainPassword);
                    }
                    $user->setPassword($encodedPassword);
                    $user->setFirstName($row['first_name']);
                    $user->setLastName($row['last_name']);
                    $user->setPhone($row['phone']);
                    $user->setActive($row['active']);
                    $user->setAdministrator($row['administrator']);
                    $user->setUsername($row['username']);
                    if (isset($row['image_url'])) {
                        $user->setImageUrl($row['image_url']);
                    } else {
                        $defaultImageUrl = 'public/img/default-hangout-256.png';
                        $user->setImageUrl($defaultImageUrl);
                    }

                    $entityManager->persist($site);
                    $entityManager->persist($user);


                }

                $entityManager->flush();
                $this->addFlash('success', 'Fichier CSV importé avec succès !');
                return $this->redirectToRoute('admin');
            }


    }
        return $this->render('import_csv_files/import.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
