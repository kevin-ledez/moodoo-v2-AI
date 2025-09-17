<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;

#[Route('/admin')]
class ImageController extends AbstractController
{
    private string $imagesDirectory;

    public function __construct()
    {
        $this->imagesDirectory = __DIR__ . '/../../../public/images';
    }

    #[Route('/images', name: 'admin_image_index', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        // Gestion de l'upload
        if ($request->isMethod('POST')) {
            $uploadedFile = $request->files->get('image');
            
            if ($uploadedFile instanceof UploadedFile) {
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
                
                try {
                    $uploadedFile->move(
                        $this->imagesDirectory,
                        $newFilename
                    );
                    
                    $this->addFlash('success', 'Image uploadée avec succès');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image: ' . $e->getMessage());
                }
            }
        }
        
        // Récupération des images
        $images = [];
        $finder = new Finder();
        $finder->files()->in($this->imagesDirectory)->depth('== 0');
        
        foreach ($finder as $file) {
            $images[] = [
                'name' => $file->getFilename(),
                'path' => '/images/' . $file->getFilename(),
                'size' => $this->formatBytes($file->getSize()),
                'date' => $file->getCTime()
            ];
        }
        
        // Tri par date (les plus récentes en premier)
        usort($images, function($a, $b) {
            return $b['date'] - $a['date'];
        });
        
        return $this->render('admin/image/index.html.twig', [
            'images' => $images,
        ]);
    }
    
    #[Route('/images/delete/{filename}', name: 'admin_image_delete', methods: ['POST'])]
    public function delete(Request $request, string $filename): Response
    {
        if ($this->isCsrfTokenValid('delete'.$filename, $request->request->get('_token'))) {
            $filesystem = new Filesystem();
            $filePath = $this->imagesDirectory . '/' . $filename;
            
            if ($filesystem->exists($filePath)) {
                try {
                    $filesystem->remove($filePath);
                    $this->addFlash('success', 'Image supprimée avec succès');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erreur lors de la suppression de l\'image: ' . $e->getMessage());
                }
            } else {
                $this->addFlash('error', 'Image non trouvée');
            }
        }
        
        return $this->redirectToRoute('admin_image_index');
    }
    
    private function formatBytes(int $size, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }
        
        return round($size, $precision) . ' ' . $units[$i];
    }
}