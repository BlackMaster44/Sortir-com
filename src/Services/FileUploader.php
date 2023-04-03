<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{

    public function __construct(
        private SluggerInterface $slugger,
        private $targetDirectory,
    ){}

    public function upload(UploadedFile $file): string
    {
        $origFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFileName = $this->slugger->slug($origFileName);
        $newFileName = $safeFileName.'-'.uniqid().'.'.$file->guessExtension();
        try{
            $file->move($this->getTargetDirectory(), $newFileName);
        } catch (FileException $e){
            var_dump($e);
            die();
        }
        return $newFileName;
    }
    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}