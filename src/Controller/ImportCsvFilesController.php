<?php

namespace App\Controller;




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
    public function import(Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(ImportType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $csvFile = $form['importFile']->getData();
            $csvReader = Reader::createFromPath($csvFile->getPathname(), 'r');
            $csvReader->setHeaderOffset(0);

            foreach ($csvReader as $row) {

                $entityManager->persist($csvReader);
                $entityManager->flush();

            }

            $this->addFlash('success', 'Fichier CSV importé avec succès !');
            return $this->redirectToRoute('admin');
        }

        return $this->render('import_csv_files/import.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
